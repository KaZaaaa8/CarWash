<!-- Sidebar -->
<aside class="sidebar fixed left-0 top-0 h-screen glass-effect border-r border-slate-700/50 transition-all duration-300 ease-in-out z-50 w-64" id="sidebar">
    <!-- Toggle Button -->
    <button id="sidebarCollapseBtn" class="absolute -right-3 top-8 bg-blue-500 text-white w-6 h-6 rounded-full flex items-center justify-center cursor-pointer hover:bg-blue-600 transition-colors">
        <i class='bx bx-chevron-left text-lg' id="toggleIcon"></i>
    </button>

    <!-- Logo Section -->
    <div class="p-4 border-b border-slate-700/50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-500/10 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class='bx bx-car text-2xl text-blue-500'></i>
            </div>
            <div class="sidebar-text">
                <h1 class="text-lg font-bold whitespace-nowrap">ZCarWash</h1>
                <p class="text-xs text-slate-400 whitespace-nowrap">Management System</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="p-4 space-y-2">
        <!-- Dashboard -->
        <a href="<?= $baseURL ?>/dashboard"
            class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-700/50 transition-colors <?= str_contains($_SERVER['REQUEST_URI'], '/dashboard') && !str_contains($_SERVER['REQUEST_URI'], '/dashboard/') ? 'bg-blue-500/10 text-blue-500' : 'text-slate-300' ?>">
            <i class='bx bx-grid-alt text-xl'></i>
            <span class="sidebar-text whitespace-nowrap">Dashboard</span>
        </a>

        <!-- Transaksi -->
        <a href="<?= $baseURL ?>/dashboard/transaksi"
            class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-700/50 transition-colors <?= str_contains($_SERVER['REQUEST_URI'], '/dashboard/transaksi') ? 'bg-blue-500/10 text-blue-500' : 'text-slate-300' ?>">
            <i class='bx bx-receipt text-xl'></i>
            <span class="sidebar-text whitespace-nowrap">Transaksi</span>
        </a>

        <!-- Layanan -->
        <?php if ($_SESSION['level'] == 'admin'): ?>
            <a href="<?= $baseURL ?>/dashboard/layanan"
                class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-700/50 transition-colors <?= str_contains($_SERVER['REQUEST_URI'], '/dashboard/layanan') ? 'bg-blue-500/10 text-blue-500' : 'text-slate-300' ?>">
                <i class='bx bx-car text-xl'></i>
                <span class="sidebar-text whitespace-nowrap">Layanan</span>
            </a>
        <?php endif; ?>

        <!-- Laporan Section -->
        <div class="pt-2 mt-2 border-t border-slate-700/50">
            <p class="px-4 py-2 text-xs font-medium text-slate-400 uppercase sidebar-text">Laporan</p>

            <a href="<?= $baseURL ?>/dashboard/laporan/harian"
                class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-700/50 transition-colors <?= str_contains($_SERVER['REQUEST_URI'], '/dashboard/laporan/harian') ? 'bg-blue-500/10 text-blue-500' : 'text-slate-300' ?>">
                <i class='bx bx-calendar text-xl'></i>
                <span class="sidebar-text whitespace-nowrap">Laporan Harian</span>
            </a>

            <a href="<?= $baseURL ?>/dashboard/laporan/bulanan"
                class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-700/50 transition-colors <?= str_contains($_SERVER['REQUEST_URI'], '/dashboard/laporan/bulanan') ? 'bg-blue-500/10 text-blue-500' : 'text-slate-300' ?>">
                <i class='bx bx-chart text-xl'></i>
                <span class="sidebar-text whitespace-nowrap">Laporan Bulanan</span>
            </a>
        </div>

        <!-- Admin Section -->
        <?php if ($_SESSION['level'] == 'admin'): ?>
            <div class="pt-2 mt-2 border-t border-slate-700/50">
                <p class="px-4 py-2 text-xs font-medium text-slate-400 uppercase sidebar-text">Admin</p>

                <a href="<?= $baseURL ?>/dashboard/pengguna"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-700/50 transition-colors <?= str_contains($_SERVER['REQUEST_URI'], '/dashboard/pengguna') ? 'bg-blue-500/10 text-blue-500' : 'text-slate-300' ?>">
                    <i class='bx bx-user text-xl'></i>
                    <span class="sidebar-text whitespace-nowrap">Pengguna</span>
                </a>

                <a href="<?= $baseURL ?>/dashboard/log-aktivitas"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-700/50 transition-colors <?= str_contains($_SERVER['REQUEST_URI'], '/dashboard/log-aktivitas') ? 'bg-blue-500/10 text-blue-500' : 'text-slate-300' ?>">
                    <i class='bx bx-history text-xl'></i>
                    <span class="sidebar-text whitespace-nowrap">Log Aktivitas</span>
                </a>
            </div>
        <?php endif; ?>
    </nav>
</aside>

<!-- Mobile Overlay -->
<div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm md:hidden sidebar-overlay hidden" id="sidebarOverlay"></div>

<script>
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const topNav = document.getElementById('topNav');
    const sidebarCollapseBtn = document.getElementById('sidebarCollapseBtn');
    const toggleIcon = document.getElementById('toggleIcon');
    const sidebarTexts = document.querySelectorAll('.sidebar-text');
    const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    let isCollapsed = false;

    // Desktop sidebar toggle
    sidebarCollapseBtn.addEventListener('click', () => {
        isCollapsed = !isCollapsed;

        if (isCollapsed) {
            sidebar.style.width = '80px';
            mainContent.style.marginLeft = '80px';
            topNav.style.width = 'calc(100% - 80px)';
            toggleIcon.classList.remove('bx-chevron-left');
            toggleIcon.classList.add('bx-chevron-right');
            sidebarTexts.forEach(text => {
                text.style.opacity = '0';
                text.style.visibility = 'hidden';
            });
        } else {
            sidebar.style.width = '256px';
            mainContent.style.marginLeft = '256px';
            topNav.style.width = 'calc(100% - 256px)';
            toggleIcon.classList.remove('bx-chevron-right');
            toggleIcon.classList.add('bx-chevron-left');
            sidebarTexts.forEach(text => {
                text.style.opacity = '1';
                text.style.visibility = 'visible';
            });
        }
    });

    // Mobile sidebar toggle
    mobileSidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
        sidebarOverlay.classList.toggle('hidden');
    });

    // Close sidebar when clicking overlay
    sidebarOverlay.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
        sidebarOverlay.classList.add('hidden');
    });
</script>