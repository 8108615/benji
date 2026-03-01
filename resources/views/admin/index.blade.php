<x-layouts.app :title="'Sistema de Prestamos y Cobranzas - Admin'">

    <div class="mb-6">
        <flux:heading size="xl" level="1">Bienvenido al Sistema</flux:heading>
        <flux:text class="mt-2 text-gray-600 dark:text-gray-400">
            Resumen general del sistema de préstamos y cobranzas
        </flux:text>
    </div>

    <flux:separator variant="subtle" />

    <br>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Total Roles -->
        <div
            class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-3
             shadow-md hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <flux:text class="text-gray-600 dark:text-gray-400 text-sm font-medium">Total Roles</flux:text>
                    <flux:heading size="lg" level="3" class="mt-2 text-gray-900 dark:text-white">
                        {{ $total_roles ?? 0 }}</flux:heading>
                    <flux:text class="text-green-600 dark:text-green-400 text-xs mt-2">
                        <i class="fas fa-arrow-up mr-1"></i>{{ $rolesNuevosMes ?? 0 }} nuevos este mes
                    </flux:text>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                    <i class="fas fa-shield-alt text-green-600 dark:text-green-400 text-2xl"></i>
                </div>
            </div>
            <div class="h-12" style="margin-top:-25px">
                <canvas id="chartRoles" class="w-full block" height="48"></canvas>
            </div>
        </div>

        <!-- Total Usuarios -->
        <div
            class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-3
             shadow-md hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <flux:text class="text-gray-600 dark:text-gray-400 text-sm font-medium">Total Usuarios</flux:text>
                    <flux:heading size="lg" level="3" class="mt-2 text-gray-900 dark:text-white">
                        {{ $totalUsuarios ?? 0 }}</flux:heading>
                    <flux:text class="text-violet-600 dark:text-violet-400 text-xs mt-2">
                        <i class="fas fa-arrow-up mr-1"></i>{{ $usuariosNuevosMes ?? 0 }} nuevos este mes
                    </flux:text>
                </div>
                <div class="p-3 bg-violet-100 dark:bg-violet-900/30 rounded-lg">
                    <i class="fas fa-users text-violet-600 dark:text-violet-400 text-2xl"></i>
                </div>
            </div>
            <div class="h-12" style="margin-top:-25px">
                <canvas id="chartUsuarios" class="w-full block" height="48"></canvas>
            </div>
        </div>

        <!-- Total Clientes -->
        <div
            class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-3
             shadow-md hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <flux:text class="text-gray-600 dark:text-gray-400 text-sm font-medium">Total Clientes</flux:text>
                    <flux:heading size="lg" level="3" class="mt-2 text-gray-900 dark:text-white">
                        {{ $totalClientes ?? 0 }}</flux:heading>
                    <flux:text class="text-blue-600 dark:text-blue-400 text-xs mt-2">
                        <i class="fas fa-arrow-up mr-1"></i>{{ $clientesNuevosMes ?? 0 }} nuevos este mes
                    </flux:text>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                    <i class="fas fa-users text-blue-600 dark:text-blue-400 text-2xl"></i>
                </div>
            </div>
            <div class="h-12" style="margin-top:-25px">
                <canvas id="chartClientes" class="w-full block" height="48"></canvas>
            </div>
        </div>



    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div
            class="lg:col-span bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4
             shadow-md hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <flux:text class="text-gray-600 dark:text-gray-400 text-sm font-medium">Cartera Activa</flux:text>
                     <flux:heading size="lg" level="3" class="mt-2 text-gray-900 dark:text-white">
                        {{ $ajuste->divisa }} {{ number_format($carteraActivaTotal ?? 0, 2) }}</flux:heading>
                    <flux:text class="text-emerald-600 dark:text-emerald-400 text-xs mt-2">
                        Total Prestado: {{ number_format($montoPrestadoTotal ?? 0, 2) }} <br>
                        Total Recuperado:
                        {{ number_format($capitalRecuperadoTotal ?? 0, 2) }}
                    </flux:text>
                    <flux:text class="text-amber-600 dark:text-amber-400 text-xs mt-1">
                        Saldo Pendiente: {{ number_format($saldoPendienteTotal ?? 0, 2) }}
                    </flux:text>
                </div>
                <div class="p-3 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg">
                    <i class="fas fa-wallet text-emerald-600 dark:text-emerald-400 text-2xl"></i>
                </div>
            </div>
            <div class="h-52">
                <canvas id="chartCartera" class="w-full h-full"></canvas>
            </div>
        </div>
    </div>




    <script defer src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (() => {
            let chart, raf;
            const cfg = () => ({
                type: "line",
                data: {
                    labels: ["S1", "S2", "S3", "S4", "S5"],
                    datasets: [{
                        data: [10, 15, 12, 20, 25],
                        borderColor: "#3B82F6",
                        backgroundColor: "rgba(59,130,246,.12)",
                        borderWidth: 2,
                        fill: true,
                        tension: .4,
                        pointRadius: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            display: false,
                            min: 0
                        },
                        x: {
                            display: false
                        }
                    }
                }
            });

            const init = () => {
                const el = document.getElementById("chartClientes");
                if (!el) return;
                chart?.destroy?.();
                chart = new Chart(el, cfg());
            };

            ["DOMContentLoaded", "livewire:load"].forEach(e => document.addEventListener(e, init));
            init();

            new MutationObserver(() => {
                if (raf) return;
                raf = requestAnimationFrame(() => {
                    raf = null;
                    init();
                });
            }).observe(document.body, {
                childList: true,
                subtree: true
            });
        })();
    </script>

    <script>
        (() => {
            let chart, raf;
            const cfg = () => ({
                type: "line",
                data: {
                    labels: ["S1", "S2", "S3", "S4", "S5"],
                    datasets: [{
                        data: [10, 15, 12, 20, 25],
                        borderColor: "#10B981",
                        backgroundColor: "rgba(16,185,129,.12)",
                        borderWidth: 2,
                        fill: true,
                        tension: .4,
                        pointRadius: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            display: false,
                            min: 0
                        },
                        x: {
                            display: false
                        }
                    }
                }
            });

            const init = () => {
                const el = document.getElementById("chartRoles");
                if (!el) return;
                chart?.destroy?.();
                chart = new Chart(el, cfg());
            };

            ["DOMContentLoaded", "livewire:load"].forEach(e => document.addEventListener(e, init));
            init();

            new MutationObserver(() => {
                if (raf) return;
                raf = requestAnimationFrame(() => {
                    raf = null;
                    init();
                });
            }).observe(document.body, {
                childList: true,
                subtree: true
            });
        })();
    </script>

    <script>
        (() => {
            let chart, raf;
            const cfg = () => ({
                type: "line",
                data: {
                    labels: ["S1", "S2", "S3", "S4", "S5"],
                    datasets: [{
                        data: [10, 15, 12, 20, 25],
                        borderColor: "#8B5CF6",
                        backgroundColor: "rgba(139,92,246,.12)",
                        borderWidth: 2,
                        fill: true,
                        tension: .4,
                        pointRadius: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            display: false,
                            min: 0
                        },
                        x: {
                            display: false
                        }
                    }
                }
            });

            const init = () => {
                const el = document.getElementById("chartUsuarios");
                if (!el) return;
                chart?.destroy?.();
                chart = new Chart(el, cfg());
            };

            ["DOMContentLoaded", "livewire:load"].forEach(e => document.addEventListener(e, init));
            init();

            new MutationObserver(() => {
                if (raf) return;
                raf = requestAnimationFrame(() => {
                    raf = null;
                    init();
                });
            }).observe(document.body, {
                childList: true,
                subtree: true
            });
        })();
    </script>

    <script>
        (() => {
            let chart, raf;
            const cfg = () => ({
                type: "doughnut",
                data: {
                    labels: ["Capital recuperado", "Saldo pendiente"],
                    datasets: [{
                        data: [{{ (float) ($capitalRecuperadoTotal ?? 0) }},
                            {{ (float) ($saldoPendienteTotal ?? 0) }}
                        ],
                        backgroundColor: ["rgba(16,185,129,.9)", "rgba(245,158,11,.85)"],
                        borderColor: ["#10B981", "#F59E0B"],
                        borderWidth: 1,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            const init = () => {
                const el = document.getElementById("chartCartera");
                if (!el) return;
                chart?.destroy?.();
                chart = new Chart(el, cfg());
            };

            ["DOMContentLoaded", "livewire:load"].forEach(e => document.addEventListener(e, init));
            init();

            new MutationObserver(() => {
                if (raf) return;
                raf = requestAnimationFrame(() => {
                    raf = null;
                    init();
                });
            }).observe(document.body, {
                childList: true,
                subtree: true
            });
        })();
    </script>


</x-layouts.app>
