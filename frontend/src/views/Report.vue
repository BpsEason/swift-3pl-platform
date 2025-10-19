<template>
  <div class="p-6 bg-gray-50 min-h-screen">
    <h1 class="text-3xl font-bold mb-8 text-blue-700">📈 WMS 效率分析報表 (API: /tenant/:tenant/report/efficiency)</h1>
    <div class="bg-white p-6 rounded-xl shadow-lg">
        <h2 class="text-xl font-semibold mb-4 text-blue-600">主要指標 (Tenant: 3pl_demo_co)</h2>
        
        <div class="grid grid-cols-3 gap-6 mb-8">
            <div class="bg-blue-50 p-4 rounded-lg shadow-sm">
                <p class="text-sm text-gray-500">總訂單數</p>
                <p class="text-2xl font-bold text-blue-800">{{ metrics.total_orders }}</p>
            </div>
            <div class="bg-blue-50 p-4 rounded-lg shadow-sm">
                <p class="text-sm text-gray-500">已完成揀貨任務</p>
                <p class="text-2xl font-bold text-blue-800">{{ metrics.picking_tasks_completed }}</p>
            </div>
            <div class="bg-blue-50 p-4 rounded-lg shadow-sm">
                <p class="text-sm text-gray-500">成功導出 Excel 報表</p>
                <a href="http://localhost:8000/tenant/3pl_demo_co/report/excel" target="_blank" class="text-blue-500 hover:underline">點擊導出 (Mock API)</a>
            </div>
        </div>

        <h2 class="text-xl font-semibold mb-4 text-blue-600">揀貨平均時間分析</h2>
        <div class="relative h-96">
            <canvas ref="chartCanvas"></canvas>
        </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Chart from 'chart.js/auto';

const chartCanvas = ref(null);
const metrics = ref({
    total_orders: '...',
    picking_tasks_completed: '...',
});
const chartData = ref(null);

async function fetchReportData() {
    try {
        const url = 'http://localhost:8000/tenant/3pl_demo_co/report/efficiency';
        const response = await axios.get(url);
        
        metrics.value = response.data.metrics;
        chartData.value = response.data.chart_data;
        
        if (chartData.value) {
            renderChart();
        }
    } catch (error) {
        console.error('Failed to fetch report data:', error);
    }
}

function renderChart() {
    new Chart(chartCanvas.value, {
        type: 'line',
        data: {
            labels: chartData.value.labels,
            datasets: [{
                label: '平均揀貨時間 (秒)',
                data: chartData.value.datasets[0].data,
                borderColor: '#1e40af',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: false,
                    title: {
                        display: true,
                        text: '時間 (秒)'
                    }
                }
            }
        }
    });
}

onMounted(() => {
    fetchReportData();
});
</script>
