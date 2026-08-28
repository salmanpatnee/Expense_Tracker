<script setup>
import { computed } from 'vue';
import { Doughnut } from 'vue-chartjs';
import { Chart as ChartJS, ArcElement, Tooltip, Legend, DoughnutController } from 'chart.js';
import { PALETTE } from '../palette';

ChartJS.register(ArcElement, Tooltip, Legend, DoughnutController);

const props = defineProps({
    totals: {
        type: Array,
        default: () => [],
    },
});

const chartData = computed(() => ({
    labels: props.totals.map((row) => row.name),
    datasets: [
        {
            data: props.totals.map((row) => row.total),
            backgroundColor: props.totals.map((_, index) => PALETTE[index % PALETTE.length]),
            borderColor: '#FFFFFF',
            borderWidth: 2,
        },
    ],
}));

const chartOptions = {
    responsive: true,
    plugins: {
        legend: {
            position: 'bottom',
            labels: {
                font: { family: '"Source Sans 3", "Segoe UI", sans-serif' },
                color: '#1C1F26',
            },
        },
        tooltip: {
            callbacks: {
                label(context) {
                    return `${context.label}: $${Number(context.parsed).toFixed(2)}`;
                },
            },
        },
    },
};
</script>

<template>
    <div>
        <Doughnut v-if="totals.length" :data="chartData" :options="chartOptions" />
        <p v-else class="text-center font-body text-small text-muted">No data yet.</p>
    </div>
</template>
