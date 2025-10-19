import { createApp } from 'vue'
import { createRouter } from 'vue-router'
import App from './App.vue'
import { router } from './router'
import './style.css' // Tailwind CSS 引入點

const app = createApp(App)

app.use(router)

// 由於 Pinia 未在後端藍圖中被強調，此處省略 Pinia 初始化，但已在 package.json 中保留
// app.use(createPinia()) 

app.mount('#app')
