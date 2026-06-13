<header class="sticky top-0 z-30 flex h-16 w-full items-center justify-between border-b border-slate-200/80 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 backdrop-blur px-4 md:px-6 shadow-sm transition-colors duration-300">
    <!-- Left: Toggles & Mock Tabs (Screenshot Style) -->
    <div class="flex items-center gap-3">
        <!-- Hamburger Mobile Toggle -->
        <button @click="toggleSidebarMobile()" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 md:hidden p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition focus:outline-none">
            <i class="ti ti-menu-2 text-lg"></i>
        </button>

        <!-- Sidebar Collapse Desktop Toggle -->
        <button @click="toggleSidebarDesktop()" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 hidden md:block p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition focus:outline-none">
            <i class="ti text-lg" :class="isSidebarCollapsed ? 'ti-layout-sidebar-expand' : 'ti-layout-sidebar-collapse'"></i>
        </button>

        <!-- Vertical Divider -->
        <div class="h-5 w-px bg-slate-200 dark:bg-slate-800 hidden md:block"></div>

        <!-- Mock Tabs -->
        <div class="hidden sm:flex items-center gap-1">
            <a href="<?= base_url(esc(session()->get('role') ?? 'mahasiswa') . '/dashboard') ?>" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-800 dark:text-slate-200 bg-slate-100/80 dark:bg-slate-800/80">
                Default layout
            </a>
            <span class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-400 dark:text-slate-500 hover:text-slate-600 cursor-not-allowed">
                Akademik
            </span>
            <span class="text-slate-300 dark:text-slate-700 text-xs px-1 hover:text-slate-600 cursor-pointer">
                <i class="ti ti-plus"></i>
            </span>
        </div>
    </div>

    <!-- Right: Help, Theme Switcher, Notifications, User Menu -->
    <div class="flex items-center gap-3 md:gap-4">
        <!-- Help text button (mocking Screenshot style) -->
        <button onclick="Swal.fire({icon: 'info', title: 'Pusat Bantuan', text: 'Silakan hubungi admin atau koordinator KP/KPL prodi Anda jika menemui kendala.', confirmButtonColor: '#3b82f6'})" 
                class="hidden md:flex items-center gap-1 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 text-xs font-semibold">
            <i class="ti ti-help-circle text-base"></i> Help
        </button>

        <!-- Theme Switcher Button (Sun/Moon) -->
        <button @click="toggleTheme()" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition focus:outline-none" title="Ubah Tema">
            <!-- Show sun icon when in dark mode -->
            <i class="ti text-lg" :class="theme === 'dark' ? 'ti-sun text-amber-500' : 'ti-moon'"></i>
        </button>

        <!-- Notifications Bell -->
        <div class="relative">
            <button class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <i class="ti ti-bell text-lg"></i>
            </button>
            <span class="absolute top-1.5 right-1.5 flex h-1.5 w-1.5">
                <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-blue-500"></span>
            </span>
        </div>

        <!-- Vertical Divider -->
        <div class="h-5 w-px bg-slate-200 dark:bg-slate-800"></div>

        <!-- Profile circular avatar & dropdown -->
        <div x-data="{ isOpen: false }" class="relative">
            <button @click="isOpen = !isOpen" @click.outside="isOpen = false" class="flex items-center gap-2 text-left p-0.5 rounded-full hover:bg-slate-50 dark:hover:bg-slate-800 transition focus:outline-none">
                <!-- Circular Avatar (Mocking layout avatar) -->
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 dark:bg-blue-500 text-white font-bold text-xs shadow-sm select-none">
                    <?= strtoupper(substr(session()->get('name') ?? 'U', 0, 2)) ?>
                </div>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="isOpen" 
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-48 origin-top-right rounded-xl bg-white dark:bg-slate-800 py-1.5 shadow-lg ring-1 ring-black ring-opacity-5 dark:ring-white dark:ring-opacity-5 focus:outline-none border border-slate-100 dark:border-slate-700" 
                 x-cloak>
                <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-700">
                    <div class="text-xs font-bold text-slate-800 dark:text-slate-200 leading-none">
                        <?= esc(session()->get('name') ?? 'User') ?>
                    </div>
                    <div class="text-[10px] text-slate-400 dark:text-slate-500 font-bold mt-1 uppercase tracking-wider">
                        <?= esc(session()->get('role') ?? 'Role') ?>
                    </div>
                </div>
                
                <a href="<?= base_url(esc(session()->get('role') ?? 'mahasiswa') . '/profile') ?>" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    <i class="ti ti-user text-sm text-slate-400 dark:text-slate-500"></i> Profil Saya
                </a>
                <a href="<?= base_url('logout') ?>" class="flex items-center gap-2.5 px-4 py-2 text-xs text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 transition border-t border-slate-100 dark:border-slate-700">
                    <i class="ti ti-logout text-sm text-red-400"></i> Keluar / Log Out
                </a>
            </div>
        </div>
    </div>
</header>
