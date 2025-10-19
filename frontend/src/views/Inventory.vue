<template>
  <div class="p-6 bg-gray-50 min-h-screen">
    <h1 class="text-3xl font-bold mb-8 text-green-700">📦 庫存即時查詢 (API: /api/inventory/:tenantId/:sku)</h1>
    <div class="bg-white p-6 rounded-xl shadow-lg">
        <form @submit.prevent="fetchInventory" class="space-y-4">
            <div class="grid grid-cols-3 gap-4">
                <input v-model="tenant_id" type="text" placeholder="租戶 ID (e.g., 3pl_demo_co)" required class="col-span-1 border-gray-300 rounded-md p-2 border">
                <input v-model="sku" type="text" placeholder="SKU (e.g., SKU-001)" required class="col-span-1 border-gray-300 rounded-md p-2 border">
                <button type="submit" :disabled="loading" class="col-span-1 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 disabled:bg-green-300">
                    <LoadingSpinner v-if="loading" class="inline w-4 h-4 mr-2 text-white animate-spin" />
                    {{ loading ? '查詢中...' : '查詢庫存' }}
                </button>
            </div>
        </form>

        <div v-if="inventoryData" class="mt-8 p-4 bg-green-50 border-l-4 border-green-500 text-green-800">
            <p class="text-lg font-bold mb-2">查詢結果:</p>
            <p><strong>租戶:</strong> {{ inventoryData.tenant }}</p>
            <p><strong>SKU:</strong> {{ inventoryData.sku }}</p>
            <p><strong>當前數量:</strong> <span class="text-xl font-extrabold">{{ inventoryData.quantity }}</span></p>
            <p><strong>狀態:</strong> {{ inventoryData.status }}</p>
        </div>
        
        <p v-if="errorMessage" class="text-red-600 mt-4">{{ errorMessage }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import LoadingSpinner from '../components/LoadingSpinner.vue';

const tenant_id = ref('3pl_demo_co');
const sku = ref('SKU-001');
const inventoryData = ref(null);
const loading = ref(false);
const errorMessage = ref('');

async function fetchInventory() {
    loading.value = true;
    errorMessage.value = '';
    inventoryData.value = null;

    const url = `http://localhost:8000/api/inventory/${tenant_id.value}/${sku.value}`;

    try {
        const response = await axios.get(url);
        inventoryData.value = response.data;
    } catch (error) {
        errorMessage.value = '庫存查詢失敗。請確認後端已啟動。';
    } finally {
        loading.value = false;
    }
}
</script>
