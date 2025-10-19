<template>
  <div id="app" class="flex h-screen bg-gray-100">
    <nav class="w-64 bg-indigo-800 text-white p-4 space-y-2 flex-shrink-0">
      <div class="text-2xl font-bold mb-6 border-b border-indigo-700 pb-3">Swift 3PL Portal</div>
      <router-link to="/" class="block px-3 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 transition duration-150 ease-in-out">
        🚚 訂單匯入
      </router-link>
      <router-link to="/inventory" class="block px-3 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 transition duration-150 ease-in-out">
        📦 庫存查詢
      </router-link>
      <router-link to="/report" class="block px-3 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 transition duration-150 ease-in-out">
        📈 效率報表
      </router-link>
    </nav>

    <main class="flex-grow overflow-auto">
      <router-view></router-view>
    </main>
  </div>
</template>

<script setup>
// Vue 3 Composition API setup
</script>

<style>
/* 確保 tailwind 能夠被正確編譯 */
@tailwind base;
@tailwind components;
@tailwind utilities;
</style>
