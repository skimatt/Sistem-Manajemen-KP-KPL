<?= $this->include('partials/head') ?>

<body class="bg-slate-50 dark:bg-slate-950 font-sans text-slate-900 dark:text-slate-100 antialiased min-h-screen overflow-hidden">
    <!-- Alpine Layout Handler Wrapper -->
    <div x-data="dashboardLayout()" class="relative flex h-screen w-screen overflow-hidden bg-slate-50 dark:bg-slate-950">
        <!-- Sidebar Navigation (Sticky/Fixed on Left) -->
        <?= $this->include('partials/sidebar') ?>

        <!-- Main Workspace Area (Independently Scrollable) -->
        <div class="flex flex-1 flex-col h-screen overflow-y-auto min-w-0 transition-all duration-300">
            <!-- Topbar (Sticky Header) -->
            <?= $this->include('partials/topbar') ?>

            <!-- Main Page Content Area -->
            <main class="flex-1 p-4 md:p-6 lg:p-8 max-w-7xl w-full mx-auto pb-12">
                <!-- Session Flash Message SweetAlerts -->
                <?= $this->include('partials/flash-message') ?>
                
                <!-- Main Dynamic Section Yield -->
                <?= $this->renderSection('content') ?>
            </main>

            <!-- Global Footer -->
            <?= $this->include('partials/footer') ?>
        </div>
    </div>

    <!-- Scripts CDN and Alpine Init -->
    <?= $this->include('partials/scripts') ?>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
