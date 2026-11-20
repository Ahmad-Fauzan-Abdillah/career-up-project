import './bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;

Alpine.data('trainingCountdown', (startAt) => ({
    seconds: Math.max(0, Math.floor((new Date(startAt).getTime() - Date.now()) / 1000)),
    label: '',
    timer: null,
    init() {
        const update = () => {
            const days = Math.floor(this.seconds / 86400);
            const hours = Math.floor((this.seconds % 86400) / 3600);
            const minutes = Math.floor((this.seconds % 3600) / 60);
            const seconds = this.seconds % 60;
            this.label = this.seconds > 0
                ? `Mulai dalam ${days} hari ${hours} jam ${minutes} menit ${seconds} detik`
                : 'Pelatihan dimulai';
            if (this.seconds > 0) this.seconds--;
        };

        update();
        this.timer = window.setInterval(update, 1000);
    },
    destroy() {
        if (this.timer) window.clearInterval(this.timer);
    },
}));

Alpine.start();

const applicantChart = document.getElementById('applicant-chart');

if (applicantChart) {
    new Chart(applicantChart, {
        type: 'line',
        data: {
            labels: JSON.parse(applicantChart.dataset.labels),
            datasets: [{
                label: 'Pelamar',
                data: JSON.parse(applicantChart.dataset.values),
                borderColor: '#124143',
                backgroundColor: 'rgba(18, 65, 67, 0.12)',
                fill: true,
                tension: 0.35,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
        },
    });
    document.getElementById('applicant-chart-skeleton')?.remove();
}

document.addEventListener('submit', (event) => {
    const form = event.target;
    if (!(form instanceof HTMLFormElement) || form.dataset.submitting === 'true') {
        if (form instanceof HTMLFormElement) event.preventDefault();
        return;
    }

    if (form.method.toLowerCase() === 'get') return;

    const submitter = event.submitter;
    if (submitter instanceof HTMLButtonElement && submitter.name) {
        const hiddenValue = document.createElement('input');
        hiddenValue.type = 'hidden';
        hiddenValue.name = submitter.name;
        hiddenValue.value = submitter.value;
        form.append(hiddenValue);
    }

    form.dataset.submitting = 'true';
    form.setAttribute('aria-busy', 'true');

    if (submitter instanceof HTMLButtonElement) {
        submitter.disabled = true;
        submitter.classList.add('is-loading');
        submitter.setAttribute('aria-busy', 'true');
    }
});
