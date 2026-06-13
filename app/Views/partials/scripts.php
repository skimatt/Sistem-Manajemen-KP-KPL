<!-- Alpine.js Collapse Plugin -->
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>

<!-- Alpine.js Core -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@latest/dist/cdn.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@latest"></script>

<!-- Global Layout Helpers (Alpine.js State) -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dashboardLayout', () => ({
            isSidebarOpen: false, // For mobile drawer toggle
            isSidebarCollapsed: localStorage.getItem('sidebar_collapsed') === 'true', // For desktop toggle
            theme: localStorage.getItem('theme') || 'light', // Light or Dark theme
            activeGroup: localStorage.getItem('active_sidebar_group') || null, // Active collapsible menu group

            init() {
                // Apply correct theme on initialization
                this.applyTheme();
            },
            
            toggleSidebarDesktop() {
                this.isSidebarCollapsed = !this.isSidebarCollapsed;
                localStorage.setItem('sidebar_collapsed', this.isSidebarCollapsed);
            },
            
            toggleSidebarMobile() {
                this.isSidebarOpen = !this.isSidebarOpen;
            },

            toggleTheme() {
                this.theme = this.theme === 'light' ? 'dark' : 'light';
                localStorage.setItem('theme', this.theme);
                this.applyTheme();
            },

            applyTheme() {
                if (this.theme === 'dark') {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            },

            toggleGroup(groupName) {
                if (this.activeGroup === groupName) {
                    this.activeGroup = null;
                } else {
                    this.activeGroup = groupName;
                }
                localStorage.setItem('active_sidebar_group', this.activeGroup || '');
            }
        }));
    });
</script>
