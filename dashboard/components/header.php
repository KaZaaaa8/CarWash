<?php
// Prevent direct access
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

// Base URL Configuration
$baseURL = "http://" . $_SERVER['HTTP_HOST'] . "/zcarwash";
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZCarWash - Sistem Manajemen Pencucian Kendaraan</title>

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1e293b;
        }

        ::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }

        /* Glass Effect */
        .glass-effect {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        /* Sidebar Transition */
        .sidebar-text {
            transition: opacity 0.3s ease;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white min-h-screen">
    <!-- Main Content Wrapper -->
    <div class="flex">
        <!-- Content Area -->
        <main class="flex-1 transition-all duration-300 ease-in-out ml-0 md:ml-64" id="mainContent">
            <!-- Top Navigation -->
            <nav class="fixed top-0 right-0 w-full md:w-[calc(100%-256px)] glass-effect border-b border-slate-700/50 z-40" id="topNav">
                <div class="flex items-center justify-between px-4 py-3">
                    <!-- Left Side -->
                    <div class="flex items-center gap-3">
                        <button id="mobileSidebarToggle" class="md:hidden text-2xl">
                            <i class='bx bx-menu'></i>
                        </button>
                        <div class="text-sm breadcrumbs hidden md:block">
                            <span class="text-slate-400">Dashboard</span>
                        </div>
                    </div>

                    <!-- Right Side -->
                    <div class="flex items-center gap-4">
                        <!-- Notifications -->
                        <div class="relative">
                            <button class="p-2 hover:bg-slate-700/50 rounded-lg transition-colors">
                                <i class='bx bx-bell text-xl'></i>
                            </button>
                        </div>

                        <!-- User Menu -->
                        <div x-data="{ isOpen: false }" class="relative">
                            <button @click="isOpen = !isOpen"
                                class="flex items-center gap-2 p-2 hover:bg-slate-700/50 rounded-lg transition-colors">
                                <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center">
                                    <i class='bx bx-user text-blue-500'></i>
                                </div>
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-medium"><?= $_SESSION['nama'] ?></p>
                                    <p class="text-xs text-slate-400 capitalize"><?= $_SESSION['level'] ?></p>
                                </div>
                                <i class='bx bx-chevron-down text-xl text-slate-400'></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="isOpen"
                                @click.away="isOpen = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-48 glass-effect border border-slate-700/50 rounded-lg shadow-xl">
                                <div class="p-2 space-y-1">
                                    <a href="<?= $baseURL ?>/dashboard/profile"
                                        class="flex items-center gap-2 px-4 py-2 text-sm text-slate-300 hover:bg-slate-700/50 rounded-lg transition-colors">
                                        <i class='bx bx-user-circle'></i>
                                        Profile
                                    </a>
                                    <a href="<?= $baseURL ?>/dashboard/settings"
                                        class="flex items-center gap-2 px-4 py-2 text-sm text-slate-300 hover:bg-slate-700/50 rounded-lg transition-colors">
                                        <i class='bx bx-cog'></i>
                                        Settings
                                    </a>
                                    <hr class="border-slate-700/50">
                                    <a href="<?= $baseURL ?>/logout.php"
                                        class="flex items-center gap-2 px-4 py-2 text-sm text-red-400 hover:bg-red-500/10 rounded-lg transition-colors">
                                        <i class='bx bx-log-out'></i>
                                        Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="pt-16">