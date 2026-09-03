<x-app-layout>
    @vite('resources/css/dashboard.css')

    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Panel de control</h2></x-slot>

    <div class="py-12 dash">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="dash-header">
                <div>
                    <p class="dash-header__eyebrow">Convivencia digital</p>
                    <h1 class="dash-header__title">Panel de control</h1>
                    <p class="dash-header__subtitle">Resumen de casos reportados y su estado actual</p>
                </div>
            </div>

            <div class="dash-kpis">
                <div class="dash-kpi">
                    <p class="dash-kpi__label">Total casos</p>
                    <p class="dash-kpi__value">{{ $totales['total'] }}</p>
                </div>
                <div class="dash-kpi dash-kpi--amber">
                    <p class="dash-kpi__label">Abiertos</p>
                    <p class="dash-kpi__value">{{ $totales['abiertos'] }}</p>
                </div>
                <div class="dash-kpi dash-kpi--green">
                    <p class="dash-kpi__label">Resueltos</p>
                    <p class="dash-kpi__value">{{ $totales['resueltos'] }}</p>
                </div>
                <div class="dash-kpi dash-kpi--red">
                    <p class="dash-kpi__label">Críticos</p>
                    <p class="dash-kpi__value">{{ $totales['criticos'] }}</p>
                </div>
            </div>

            <div class="dash-panels">
                <div class="dash-panel">
                    <h3 class="dash-panel__title">Casos por estado</h3>
                    <div class="dash-panel__canvas-wrap">
                        <canvas id="chartEstado"></canvas>
                    </div>
                </div>
                <div class="dash-panel">
                    <h3 class="dash-panel__title">Casos por tipo</h3>
                    <div class="dash-panel__canvas-wrap">
                        <canvas id="chartTipo"></canvas>
                    </div>
                </div>
                <div class="dash-panel dash-panel--wide">
                    <h3 class="dash-panel__title">Casos por mes</h3>
                    <div class="dash-panel__canvas-wrap">
                        <canvas id="chartMes"></canvas>
                    </div>
                </div>
            </div>

            <div class="dash-actions">
                <a href="{{ route('instituciones.index') }}" class="dash-btn dash-btn--ghost">Gestionar instituciones</a>
                <a href="{{ route('casos.index') }}" class="dash-btn dash-btn--primary">Ver todos los casos</a>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.4/chart.umd.min.js"></script>
    <script>
        const porEstado = @json($porEstado);
        const porTipo = @json($porTipo);
        const porMes = @json($porMes);

        new Chart(document.getElementById('chartEstado'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(porEstado),
                datasets: [{ data: Object.values(porEstado), backgroundColor: ['#f59e0b','#3b82f6','#8b5cf6','#f97316','#10b981','#6b7280'] }]
            },
            options: { maintainAspectRatio: false }
        });

        new Chart(document.getElementById('chartTipo'), {
            type: 'bar',
            data: {
                labels: Object.keys(porTipo),
                datasets: [{ label: 'Casos', data: Object.values(porTipo), backgroundColor: '#4f46e5', borderRadius: 6 }]
            },
            options: { maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
        });

        new Chart(document.getElementById('chartMes'), {
            type: 'line',
            data: {
                labels: Object.keys(porMes),
                datasets: [{ label: 'Casos por mes', data: Object.values(porMes), borderColor: '#4f46e5', backgroundColor: 'rgba(79,70,229,0.1)', fill: true, tension: 0.3 }]
            },
            options: { maintainAspectRatio: false }
        });
    </script>
</x-app-layout>
