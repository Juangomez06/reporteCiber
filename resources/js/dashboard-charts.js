import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', () => {
    const dataEl = document.getElementById('dashboard-data');
    if (!dataEl) return;

    const porEstado = JSON.parse(dataEl.dataset.porEstado || '{}');
    const porTipo = JSON.parse(dataEl.dataset.porTipo || '{}');
    const porMes = JSON.parse(dataEl.dataset.porMes || '{}');

    const elEstado = document.getElementById('chartEstado');
    const elTipo = document.getElementById('chartTipo');
    const elMes = document.getElementById('chartMes');

    if (elEstado) {
        new Chart(elEstado, {
            type: 'doughnut',
            data: {
                labels: Object.keys(porEstado),
                datasets: [{
                    data: Object.values(porEstado),
                    backgroundColor: ['#FFD166', '#457B9D', '#A8DADC', '#F4A261', '#06D6A0', '#4A5B6E'],
                }],
            },
            options: { maintainAspectRatio: false },
        });
    }

    if (elTipo) {
        new Chart(elTipo, {
            type: 'bar',
            data: {
                labels: Object.keys(porTipo),
                datasets: [{ label: 'Casos', data: Object.values(porTipo), backgroundColor: '#457B9D', borderRadius: 6 }],
            },
            options: { maintainAspectRatio: false, scales: { y: { beginAtZero: true } } },
        });
    }

    if (elMes) {
        new Chart(elMes, {
            type: 'line',
            data: {
                labels: Object.keys(porMes),
                datasets: [{
                    label: 'Casos por mes',
                    data: Object.values(porMes),
                    borderColor: '#1D3557',
                    backgroundColor: 'rgba(29,53,87,0.1)',
                    fill: true,
                    tension: 0.3,
                }],
            },
            options: { maintainAspectRatio: false },
        });
    }
});