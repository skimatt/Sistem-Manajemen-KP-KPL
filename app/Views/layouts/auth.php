<?= $this->include('partials/head') ?>

<body class="bg-slate-50 dark:bg-slate-950 font-sans text-slate-900 dark:text-slate-100 antialiased min-h-screen flex items-center justify-center p-4 transition-colors duration-300">
    <div class="w-full max-w-md">
        <?= $this->renderSection('content') ?>
    </div>
    
    <?= $this->include('partials/scripts') ?>
</body>
</html>
