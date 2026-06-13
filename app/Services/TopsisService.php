<?php

namespace App\Services;

class TopsisService
{
    /**
     * Calculate TOPSIS rankings for a given student registration.
     *
     * @param int $registrationId
     * @return array
     */
    public static function calculate($registrationId)
    {
        $db = \Config\Database::connect();

        // 1. Fetch registration details
        $registration = $db->table('kp_registrations')
            ->select('kp_registrations.*, student_profiles.npm, student_profiles.full_name, student_profiles.study_program_id')
            ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
            ->where('kp_registrations.id', $registrationId)
            ->where('kp_registrations.deleted_at', null)
            ->get()
            ->getRowArray();

        if (!$registration) {
            throw new \Exception('Registrasi mahasiswa tidak ditemukan.');
        }

        $periodId = $registration['period_id'];

        // 2. Fetch criteria and period weights
        $criteriaList = $db->table('topsis_criteria')
            ->where('status', 'active')
            ->get()
            ->getResultArray();

        if (empty($criteriaList)) {
            throw new \Exception('Kriteria TOPSIS belum dikonfigurasi.');
        }

        $weightsList = $db->table('topsis_weights')
            ->where('period_id', $periodId)
            ->get()
            ->getResultArray();

        // Map weights by criteria_id
        $weights = [];
        foreach ($weightsList as $w) {
            $weights[$w['criteria_id']] = floatval($w['weight']);
        }

        // If weights are not set or don't sum up to 100%, set a fallback uniform weight
        $totalWeight = array_sum($weights);
        if (empty($weights) || abs($totalWeight - 100) > 1.0) {
            $uniformWeight = 100.0 / count($criteriaList);
            foreach ($criteriaList as $c) {
                $weights[$c['id']] = $uniformWeight;
            }
        }

        // 3. Fetch alternatives (Institutions with active quotas in this period)
        $alternatives = $db->table('institution_quotas')
            ->select('institution_quotas.*, institution_profiles.name as instansi_name, institution_profiles.field as instansi_field, institution_profiles.partnership_status, institution_profiles.address')
            ->join('institution_profiles', 'institution_profiles.id = institution_quotas.institution_id')
            ->where('institution_quotas.period_id', $periodId)
            ->where('institution_quotas.status', 'active')
            ->get()
            ->getResultArray();

        if (empty($alternatives)) {
            throw new \Exception('Tidak ada instansi mitra dengan kuota aktif untuk periode ini.');
        }

        // 4. Ensure topsis_scores exist for all alternatives and criteria
        self::ensureRawScores($registrationId, $registration, $alternatives, $criteriaList);

        // 5. Build Decision Matrix (X)
        $matrix = []; // [alternative_id][criteria_id] = score
        foreach ($alternatives as $alt) {
            $altId = $alt['institution_id'];
            $matrix[$altId] = [];
            foreach ($criteriaList as $c) {
                $critId = $c['id'];
                // Fetch score from db
                $scoreRow = $db->table('topsis_scores')
                    ->where('registration_id', $registrationId)
                    ->where('institution_id', $altId)
                    ->where('criteria_id', $critId)
                    ->get()
                    ->getRowArray();
                
                $matrix[$altId][$critId] = $scoreRow ? floatval($scoreRow['score']) : 50.0;
            }
        }

        // 6. Calculate Normalization Divisors (sqrt(sum of squares for each criterion))
        $divisors = [];
        foreach ($criteriaList as $c) {
            $critId = $c['id'];
            $sumSq = 0;
            foreach ($alternatives as $alt) {
                $altId = $alt['institution_id'];
                $sumSq += pow($matrix[$altId][$critId], 2);
            }
            $divisors[$critId] = $sumSq > 0 ? sqrt($sumSq) : 1.0;
        }

        // 7. Calculate Normalized Matrix (R) and Weighted Normalized Matrix (V)
        $normalizedMatrix = [];
        $weightedMatrix = [];
        foreach ($alternatives as $alt) {
            $altId = $alt['institution_id'];
            $normalizedMatrix[$altId] = [];
            $weightedMatrix[$altId] = [];
            foreach ($criteriaList as $c) {
                $critId = $c['id'];
                $r_ij = $matrix[$altId][$critId] / $divisors[$critId];
                $normalizedMatrix[$altId][$critId] = $r_ij;
                
                // v_ij = r_ij * (weight / 100)
                $v_ij = $r_ij * ($weights[$critId] / 100.0);
                $weightedMatrix[$altId][$critId] = $v_ij;
            }
        }

        // 8. Determine Ideal Positive (A+) and Ideal Negative (A-) Solutions
        $idealPositive = [];
        $idealNegative = [];
        foreach ($criteriaList as $c) {
            $critId = $c['id'];
            $values = [];
            foreach ($alternatives as $alt) {
                $altId = $alt['institution_id'];
                $values[] = $weightedMatrix[$altId][$critId];
            }

            if ($c['type'] === 'benefit') {
                $idealPositive[$critId] = max($values);
                $idealNegative[$critId] = min($values);
            } else { // cost (lower is better)
                $idealPositive[$critId] = min($values);
                $idealNegative[$critId] = max($values);
            }
        }

        // 9. Calculate Separation Measures (D+ and D-) and Preference Values (C)
        $results = [];
        $db->transStart();
        
        // Clear old results for this registration
        $db->table('topsis_results')->where('registration_id', $registrationId)->delete();

        foreach ($alternatives as $alt) {
            $altId = $alt['institution_id'];
            
            $dPlusSum = 0;
            $dMinusSum = 0;
            foreach ($criteriaList as $c) {
                $critId = $c['id'];
                $dPlusSum += pow($weightedMatrix[$altId][$critId] - $idealPositive[$critId], 2);
                $dMinusSum += pow($weightedMatrix[$altId][$critId] - $idealNegative[$critId], 2);
            }
            $dPlus = sqrt($dPlusSum);
            $dMinus = sqrt($dMinusSum);

            $preference = ($dPlus + $dMinus) > 0 ? ($dMinus / ($dPlus + $dMinus)) : 0.0;

            $results[] = [
                'registration_id'  => $registrationId,
                'institution_id'   => $altId,
                'instansi_name'    => $alt['instansi_name'],
                'preference_value' => $preference,
                'd_plus'           => $dPlus,
                'd_minus'          => $dMinus,
            ];
        }

        // Sort by preference value descending to assign rankings
        usort($results, function ($a, $b) {
            return $b['preference_value'] <=> $a['preference_value'];
        });

        // 10. Save rankings and build Snapshot for Skripsi audit
        $rank = 1;
        $snapshot = [
            'weights'          => $weights,
            'criteria'         => $criteriaList,
            'raw_matrix'       => $matrix,
            'normalized'       => $normalizedMatrix,
            'weighted'         => $weightedMatrix,
            'ideal_positive'   => $idealPositive,
            'ideal_negative'   => $idealNegative,
            'divisors'         => $divisors,
        ];

        foreach ($results as &$res) {
            $res['rank_order'] = $rank;
            
            $db->table('topsis_results')->insert([
                'registration_id'      => $registrationId,
                'institution_id'       => $res['institution_id'],
                'preference_value'     => $res['preference_value'],
                'rank_order'           => $rank,
                'calculation_snapshot' => json_encode($snapshot),
                'calculated_at'        => date('Y-m-d H:i:s'),
                'created_at'           => date('Y-m-d H:i:s'),
            ]);
            $rank++;
        }

        $db->transComplete();

        return [
            'results'  => $results,
            'snapshot' => $snapshot,
        ];
    }

    /**
     * Ensure raw scores exist in database for a student registration across all alternatives.
     * If they do not exist, populate them with sensible default logic.
     */
    private static function ensureRawScores($registrationId, $registration, $alternatives, $criteriaList)
    {
        $db = \Config\Database::connect();
        
        // Map criteria code to id
        $criteriaMap = [];
        foreach ($criteriaList as $c) {
            $criteriaMap[$c['code']] = $c['id'];
        }

        foreach ($alternatives as $alt) {
            $altId = $alt['institution_id'];
            
            // Generate default scores
            $defaultScores = [
                'C1' => self::calculateDefaultC1($registration, $alt), // Kesesuaian Bidang
                'C2' => self::calculateDefaultC2($registration, $alt), // Kesesuaian Kemampuan
                'C3' => self::calculateDefaultC3($registration, $alt), // Kuota
                'C4' => self::calculateDefaultC4($registration, $alt), // Jarak (Cost)
                'C5' => self::calculateDefaultC5($registration, $alt), // Status Kemitraan
                'C6' => self::calculateDefaultC6($registration, $alt), // Pembimbing Lapangan
            ];

            foreach ($defaultScores as $code => $score) {
                if (!isset($criteriaMap[$code])) continue;
                $critId = $criteriaMap[$code];

                // Check if score already exists
                $existing = $db->table('topsis_scores')
                    ->where('registration_id', $registrationId)
                    ->where('institution_id', $altId)
                    ->where('criteria_id', $critId)
                    ->get()
                    ->getRow();

                if (!$existing) {
                    $db->table('topsis_scores')->insert([
                        'registration_id' => $registrationId,
                        'institution_id'  => $altId,
                        'criteria_id'     => $critId,
                        'score'           => $score,
                        'created_at'      => date('Y-m-d H:i:s'),
                        'updated_at'      => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }
    }

    // C1: Kesesuaian Bidang (Benefit)
    private static function calculateDefaultC1($reg, $alt)
    {
        $field = strtolower($alt['instansi_field'] ?? '');
        // Default based on keyword match
        if (str_contains($field, 'it') || str_contains($field, 'software') || str_contains($field, 'komputer') || str_contains($field, 'teknologi') || str_contains($field, 'developer')) {
            return 90.0;
        } elseif (str_contains($field, 'pendidikan') || str_contains($field, 'sekolah') || str_contains($field, 'dinas') || str_contains($field, 'bank')) {
            return 75.0;
        }
        return 65.0;
    }

    // C2: Kesesuaian Kemampuan (GPA based)
    private static function calculateDefaultC2($reg, $alt)
    {
        $gpa = floatval($reg['academic_gpa'] ?? 3.0);
        // Map GPA to 0-100 scale
        $score = $gpa * 25.0;
        return min(max($score, 50.0), 100.0);
    }

    // C3: Kuota Penempatan (Benefit)
    private static function calculateDefaultC3($reg, $alt)
    {
        $total = intval($alt['quota_total'] ?? 1);
        $used = intval($alt['quota_used'] ?? 0);
        $remaining = $total - $used;

        if ($remaining >= 5) return 95.0;
        if ($remaining >= 3) return 85.0;
        if ($remaining >= 1) return 70.0;
        return 30.0; // low quota/full
    }

    // C4: Jarak Lokasi (Cost - but raw value represents distance, say in km. Let's make it 2km - 45km)
    private static function calculateDefaultC4($reg, $alt)
    {
        $altId = intval($alt['institution_id']);
        // Deterministic mock distance based on ID
        $distance = ($altId * 7) % 38 + 3; // range: 3km to 40km
        return floatval($distance);
    }

    // C5: Status Kemitraan (Benefit)
    private static function calculateDefaultC5($reg, $alt)
    {
        $status = strtolower($alt['partnership_status'] ?? 'mitra');
        if ($status === 'mitra' || $status === 'aktif' || $status === 'active') {
            return 90.0;
        }
        return 60.0;
    }

    // C6: Ketersediaan Pembimbing Lapangan (Benefit)
    private static function calculateDefaultC6($reg, $alt)
    {
        // If instansi has contact person, assume PL is available
        $contact = trim($alt['contact_person'] ?? '');
        return !empty($contact) ? 85.0 : 60.0;
    }
}
