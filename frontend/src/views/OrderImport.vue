<template>
  <div class="p-6 bg-gray-50 min-h-screen">
    <h1 class="text-3xl font-bold mb-8 text-indigo-700">🚚 訂單匯入介面 (API: /api/orders/import)</h1>
    <div class="bg-white p-6 rounded-xl shadow-lg">
      <p v-if="successMessage" class="text-green-600 mb-4 font-semibold">{{ successMessage }}</p>
      <p v-if="errorMessage" class="text-red-600 mb-4 font-semibold">{{ errorMessage }}</p>

      <form @submit.prevent="submitOrder">
        <div class="grid grid-cols-2 gap-6 mb-6">
          <div>
            <label class="block text-sm font-medium text-gray-700">租戶 ID</label>
            <input v-model="form.tenant_id" type="text" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border">
            <p class="text-xs text-gray-500 mt-1">預設: 3pl_demo_co (來自 Seeder)</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">平台訂單號</label>
            <input v-model="form.platform_order_id" type="text" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border">
          </div>
        </div>

        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700">收貨地址</label>
          <textarea v-model="form.shipping_address" required rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border"></textarea>
        </div>

        <h2 class="text-xl font-semibold mb-4 text-indigo-600">商品明細</h2>
        <div v-for="(item, index) in form.items" :key="index" class="flex space-x-4 mb-4 items-center p-3 border border-gray-200 rounded-md">
          <div class="flex-grow">
            <label class="block text-xs font-medium text-gray-500">SKU</label>
            <input v-model="item.sku" type="text" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-1 border">
          </div>
          <div class="w-20">
            <label class="block text-xs font-medium text-gray-500">數量</label>
            <input v-model.number="item.quantity" type="number" required min="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-1 border text-center">
          </div>
          <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-700 text-lg">
            &times;
          </button>
        </div>
        
        <button type="button" @click="addItem" class="w-full text-center py-2 border border-dashed border-indigo-300 text-indigo-500 hover:bg-indigo-50 rounded-md transition duration-150 ease-in-out mb-6">
          + 新增商品
        </button>

        <button type="submit" :disabled="loading" class="w-full py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out disabled:bg-indigo-300">
          <LoadingSpinner v-if="loading" class="inline w-4 h-4 mr-2 text-white animate-spin" />
          {{ loading ? '匯入中 (Job Queueing)...' : '提交訂單到 WMS (API Call)' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import LoadingSpinner from '../components/LoadingSpinner.vue';

const form = ref({
  tenant_id: '3pl_demo_co',
  platform_order_id: 'WEB-' + Date.now().toString().substring(5),
  shipping_address: '台北市信義區市政府路1號',
  items: [
    { sku: 'SKU-001', quantity: 1 },
    { sku: 'SKU-002', quantity: 2 },
  ],
});

const loading = ref(false);
const successMessage = ref('');
const errorMessage = ref('');

function addItem() {
  form.value.items.push({ sku: 'SKU-' + Math.floor(Math.random() * 5 + 1).toString().padStart(3, '0'), quantity: 1 });
}

function removeItem(index) {
  form.value.items.splice(index, 1);
}

async function submitOrder() {
  loading.value = true;
  successMessage.value = '';
  errorMessage.value = '';

  try {
    const response = await axios.post('http://localhost:8000/api/orders/import', form.value);
    
    successMessage.value = response.data.message + ` 訂單ID: ${response.data.order_id}`;
    // 重設表單，方便測試下一筆
    form.value.platform_order_id = 'WEB-' + Date.now().toString().substring(5);
    
  } catch (error) {
    const errorDetails = error.response?.data?.error || error.response?.data?.message || error.message;
    errorMessage.value = '匯入失敗: ' + errorDetails;
  } finally {
    loading.value = false;
  }
}

onMounted(() => {
    form.value.platform_order_id = 'WEB-' + Date.now().toString().substring(5);
})
</script>

<style scoped>
/* Tailwind 在 App.vue 中全局引入 */
</style>
