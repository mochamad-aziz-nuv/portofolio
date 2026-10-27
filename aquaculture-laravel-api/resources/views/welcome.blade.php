<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NagroSmart • Smart Aquaculture Management Platform</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        aqua: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            900: '#134e4a',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-950 text-slate-100 font-['Inter'] min-h-screen antialiased selection:bg-teal-500 selection:text-white">
    <!-- Background Gradient Glow -->
    <div class="absolute inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[1000px] h-[500px] bg-gradient-to-tr from-teal-500/20 via-sky-500/20 to-emerald-500/10 blur-[130px]"></div>
    </div>

    <!-- Header Navbar -->
    <header class="border-b border-slate-800/80 backdrop-blur-md sticky top-0 z-50 bg-slate-950/70">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-400 to-sky-600 flex items-center justify-center font-bold text-slate-950 text-xl shadow-lg shadow-teal-500/20">
                    🌊
                </div>
                <div>
                    <span class="text-lg font-extrabold tracking-tight text-white">NagroSmart</span>
                    <span class="text-xs ml-1.5 px-2 py-0.5 rounded-full bg-teal-500/10 text-teal-400 border border-teal-500/30 font-medium">AquaFarm v1.0</span>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <a href="/admin/login" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-slate-900 bg-gradient-to-r from-teal-400 to-emerald-400 rounded-lg shadow-md hover:from-teal-300 hover:to-emerald-300 transition duration-150">
                    Masuk Admin Panel
                    <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-24">
        <div class="text-center max-w-3xl mx-auto">
            <div class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-full bg-slate-900/90 border border-slate-800 text-slate-300 text-xs mb-6">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Laravel 11 • Filament v3 • Sanctum API • Flutter Ready</span>
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white">
                Modern Aquaculture & <br class="hidden sm:inline"/>
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-teal-400 via-cyan-300 to-emerald-400">
                    Pond Management OS
                </span>
            </h1>

            <p class="mt-6 text-base sm:text-lg text-slate-400 leading-relaxed">
                Platform backend dan operasional tambak intensif udang & ikan: monitoring kualitas air (DO, pH, Salinitas, Suhu), log pakan real-time, estimasi FCR, dan manajemen siklus budidaya berbasis RESTful API.
            </p>

            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                <a href="/admin" class="px-6 py-3 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-950 font-bold shadow-lg shadow-teal-500/25 transition">
                    Buka Dashboard Filament
                </a>
                <a href="/api/health" target="_blank" class="px-6 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-700 text-slate-200 font-medium transition">
                    Test API Healthcheck (/api/health)
                </a>
            </div>
        </div>

        <!-- Demo Credentials Callout -->
        <div class="mt-12 max-w-2xl mx-auto p-5 rounded-2xl bg-slate-900/90 border border-teal-500/30 shadow-xl backdrop-blur-sm">
            <div class="flex items-start space-x-3">
                <div class="p-2 rounded-lg bg-teal-500/20 text-teal-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                </div>
                <div class="flex-1 text-sm">
                    <h3 class="font-bold text-white text-base">Kredensial Akun Demo (Filament & API)</h3>
                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-3 text-slate-300">
                        <div class="p-3 bg-slate-950 rounded-lg border border-slate-800">
                            <span class="text-xs text-teal-400 font-semibold block uppercase">Farm Admin</span>
                            <span class="font-mono text-xs block text-slate-200">admin@aquafarm.test</span>
                            <span class="font-mono text-xs block text-slate-400">password: <strong class="text-white">password</strong></span>
                        </div>
                        <div class="p-3 bg-slate-950 rounded-lg border border-slate-800">
                            <span class="text-xs text-sky-400 font-semibold block uppercase">Field Technician</span>
                            <span class="font-mono text-xs block text-slate-200">teknisi@aquafarm.test</span>
                            <span class="font-mono text-xs block text-slate-400">password: <strong class="text-white">password</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Architecture Highlights -->
        <div class="mt-16 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="p-5 rounded-xl bg-slate-900/60 border border-slate-800/80">
                <div class="text-3xl font-extrabold text-teal-400">5</div>
                <div class="font-bold text-white mt-1">Kolam Tambak</div>
                <div class="text-xs text-slate-400 mt-1">HDPE liners, beton, tandon persiapannya sudah ter-seed lengkap.</div>
            </div>

            <div class="p-5 rounded-xl bg-slate-900/60 border border-slate-800/80">
                <div class="text-3xl font-extrabold text-sky-400">4</div>
                <div class="font-bold text-white mt-1">Siklus Aktif</div>
                <div class="text-xs text-slate-400 mt-1">Udang Vaname, Windu, & Nila Salin dengan perhitungan DOC harian.</div>
            </div>

            <div class="p-5 rounded-xl bg-slate-900/60 border border-slate-800/80">
                <div class="text-3xl font-extrabold text-amber-400">480</div>
                <div class="font-bold text-white mt-1">Log Pakan Harian</div>
                <div class="text-xs text-slate-400 mt-1">Catatan pakan 30 hari (4x sehari), monitoring sisa pakan/anco.</div>
            </div>

            <div class="p-5 rounded-xl bg-slate-900/60 border border-slate-800/80">
                <div class="text-3xl font-extrabold text-emerald-400">300</div>
                <div class="font-bold text-white mt-1">Sensor Kualitas Air</div>
                <div class="text-xs text-slate-400 mt-1">Log pagi & sore (DO, pH, suhu, salinitas, amonia, turbiditas).</div>
            </div>
        </div>

        <!-- REST API Specification -->
        <section class="mt-16 bg-slate-900/50 border border-slate-800 rounded-2xl p-6 sm:p-8">
            <h2 class="text-xl font-bold text-white flex items-center">
                <span class="mr-2">🔌</span> RESTful API Endpoints (Mobile / Flutter Integration)
            </h2>
            <p class="text-sm text-slate-400 mt-1 mb-6">
                Endpoint terstandarisasi dengan autentikasi Laravel Sanctum Bearer Token.
            </p>

            <div class="space-y-3 font-mono text-xs">
                <div class="flex items-center justify-between p-3 rounded-lg bg-slate-950 border border-slate-800">
                    <div class="flex items-center space-x-3">
                        <span class="px-2 py-0.5 rounded bg-blue-500/20 text-blue-400 font-bold">POST</span>
                        <span class="text-slate-300">/api/v1/auth/login</span>
                    </div>
                    <span class="text-slate-500 text-[11px]">Kirim email & password, mengembalikan auth token</span>
                </div>

                <div class="flex items-center justify-between p-3 rounded-lg bg-slate-950 border border-slate-800">
                    <div class="flex items-center space-x-3">
                        <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 font-bold">GET</span>
                        <span class="text-slate-300">/api/v1/ponds</span>
                    </div>
                    <span class="text-slate-500 text-[11px]">Daftar kolam lengkap dengan status & siklus aktif</span>
                </div>

                <div class="flex items-center justify-between p-3 rounded-lg bg-slate-950 border border-slate-800">
                    <div class="flex items-center space-x-3">
                        <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 font-bold">GET</span>
                        <span class="text-slate-300">/api/v1/cycles/{id}/summary</span>
                    </div>
                    <span class="text-slate-500 text-[11px]">Metrik analitik siklus: DOC hari ini, akumulasi pakan & rerata DO</span>
                </div>

                <div class="flex items-center justify-between p-3 rounded-lg bg-slate-950 border border-slate-800">
                    <div class="flex items-center space-x-3">
                        <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 font-bold">GET</span>
                        <span class="text-slate-300">/api/v1/feeding-logs?cycle_id=1</span>
                    </div>
                    <span class="text-slate-500 text-[11px]">Riwayat pakan terpaginasi dengan evaluasi nafsu makan</span>
                </div>

                <div class="flex items-center justify-between p-3 rounded-lg bg-slate-950 border border-slate-800">
                    <div class="flex items-center space-x-3">
                        <span class="px-2 py-0.5 rounded bg-purple-500/20 text-purple-400 font-bold">GET</span>
                        <span class="text-slate-300">/api/v1/water-quality-logs/latest</span>
                    </div>
                    <span class="text-slate-500 text-[11px]">Kondisi air mutakhir dari semua kolam aktif (untuk widget mobile)</span>
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-slate-900 py-6 text-center text-xs text-slate-500">
        Dikembangkan oleh <span class="text-slate-300 font-medium">Mochamad Aziz Noviyanto</span> • Portfolio Project Smart Aquaculture Backend
    </footer>
</body>
</html>
