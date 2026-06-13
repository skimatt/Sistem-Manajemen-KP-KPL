<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to(base_url('login'))->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $userRole = $session->get('role');
        if (!empty($arguments)) {
            if (!in_array($userRole, $arguments)) {
                $redirectUrl = base_url('login');
                if ($userRole) {
                    $redirectUrl = base_url($userRole . '/dashboard');
                }
                return redirect()->to($redirectUrl)->with('error', 'Anda tidak memiliki hak akses untuk halaman tersebut.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
