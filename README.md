# Swift 3PL Platform

Swift 3PL Platform 是一個現代化的第三方物流 (3PL) 管理系統，採用 Monorepo 結構，整合後端 (基於 Laravel 12 Skeleton / PHP 8.3 / Tenancy 4.0) 和前端 (Vue 3)。它專注於電商訂單整合 (OMS)、倉儲管理 (WMS)、揀貨出貨速度優化、多租戶隔離、報表分析 (Excel/Chart.js) 和物流 API 串接，適合快速部署和展示完整 3PL 流程的 MVP (Minimum Viable Product)。

## 🧱 系統架構

```
+-------------------------------------------------------------+
|                       Multi-Tenant 3PL Monorepo Platform    |
+-------------------------------------------------------------+
| Frontend Layer (Vue 3 + Pinia + Tailwind CSS + Axios)       |
| - Partner Portal: OrderImport.vue (訂單匯入)                |
| - Admin Console: Inventory.vue (庫存查詢), Report.vue (報表)|
| - Components: LoadingSpinner.vue (載入動畫)                 |
+-------------------------------------------------------------+
| Backend Layer (Laravel 12 / PHP 8.3 / Tenancy 4.0)          |
| - OMS: OrderController.php, OrderImportRequest.php          |
| - WMS: Inventory.php (Redis 模擬即時查詢)                  |
| - Fulfillment: PickingService.php (批次/分區/路徑優化)     |
|                ShippingService.php, BlackCatAdapter.php     |
| - Analytics: ReportController.php, OrdersExport.php         |
| - API/Webhook: api.php, ShipmentCreated.php, SendWebhookNotification.php |
| - Auth: tenancy.php, sanctum.php                           |
+-------------------------------------------------------------+
| Data Layer                                                  |
| - MySQL: Landlord (tenants), Tenant (orders, order_items, inventories) |
| - Redis: Cache/Queue (default, picking_speed, webhook_high_priority) |
+-------------------------------------------------------------+
| Deployment Layer                                            |
| - Docker: App, Nginx, MySQL, Redis, Queue Worker           |
| - CI/CD: GitHub Actions (main.yml)                         |
+-------------------------------------------------------------+
```

### 架構說明
- **前端層**：Vue 3 SPA，採用 Pinia 狀態管理、Tailwind CSS 樣式、Axios 串接後端 API（可配置 `VITE_API_BASE_URL`）。提供 Partner Portal (訂單匯入) 和 Admin Console (庫存查詢、報表視覺化)。
- **後端層**：
  - **OMS**：訂單整合，支援多平台訂單匯入，Transaction 確保一致性。
  - **WMS**：庫存管理，支援儲位分區和即時查詢 (Redis 模擬)。
  - **Fulfillment Engine**：揀貨速度優化 (批次/分區/路徑排序，Queue 異步)，物流 API 串接 (模擬黑貓)。
  - **Analytics**：Excel 報表導出，Chart.js 視覺化揀貨效率。
  - **API/Webhook**：RESTful API 提供訂單/庫存端點，Webhook 通知出貨狀態（`ShipmentCreated` 事件觸發 `SendWebhookNotification` 監聽器）。
  - **多租戶**：Tenancy 4.0 實現資料庫分離，支援租戶隔離。
- **數據層**：MySQL 儲存訂單/庫存，Redis 支援快取和隊列 (default, picking_speed, webhook_high_priority)。
- **部署層**：Docker 容器化 (App, Nginx, MySQL, Redis, Queue Worker)，GitHub Actions 實現 CI/CD。

## 🌟 系統亮點
1. **多租戶隔離**：基於 Stancl/Tenancy 4.0，實現資料庫分離，確保租戶數據獨立，適用於多客戶 3PL 場景。
2. **高效揀貨引擎**：`PickingService.php` 實現批次揀貨、分區排序、路徑優化 (usort + 座標)，降低倉庫移動時間，提升履約速度。
3. **異步處理**：使用 Laravel Queue (Redis) 處理揀貨任務 (`AssignPickingTask.php`) 和 Webhook 通知 (webhook_high_priority 隊列)，確保高吞吐量。
4. **訂單整合**：支援多平台訂單匯入 (`OrderController.php`)，驗證嚴格 (`OrderImportRequest.php`)，Transaction 保證一致性。
5. **即時庫存查詢**：`Inventory.php` 模擬 Redis 即時查詢，支援儲位分區 (`getZoneAttribute`)，適用於 WMS 場景。
6. **報表與分析**：`ReportController.php` 提供 Excel 導出 (`OrdersExport.php`) 和 Chart.js 視覺化 (`Report.vue`)，展示揀貨效率和訂單統計。
7. **物流串接**：`ShippingService.php` 採用適配器模式 (`BlackCatAdapter.php`)，模擬物流 API，觸發 `ShipmentCreated` 事件和 Webhook 通知。
8. **現代化技術棧**：Laravel 12 (Security 支持至 2027-02-04 <grok:render type="render_inline_citation"><argument name="citation_id">0</argument></grok:render>), PHP 8.3, Vue 3.4，確保長期穩定性。
9. **容器化部署**：Docker Compose 整合 App/Nginx/MySQL/Redis/Queue，支援快速部署，GitHub Actions 實現 CI/CD 自動化。
10. **前端體驗**：Vue 3 SPA 提供直觀 UI (`OrderImport.vue` 動態表單，`Inventory.vue` 查詢，`Report.vue` 圖表)，Tailwind CSS 確保響應式設計。
11. **可擴展性**：預留退貨邏輯 (Order status 'cancelled')、AI 路徑優化 (`PickingService` 座標可升級 A*)、國際物流 (Adapters 陣列)。

## 📂 專案結構
```
swift-3pl-platform
├── .github/
│   └── workflows/main.yml           # GitHub Actions CI/CD
├── backend/
│   ├── app/
│   │   ├── Models/                 # Order, OrderItem, Inventory, Tenant
│   │   ├── Services/               # PickingService, ShippingService
│   │   ├── Adapters/               # BlackCatAdapter
│   │   ├── Http/
│   │   │   ├── Controllers/Api/    # OrderController, ReportController
│   │   │   └── Requests/           # OrderImportRequest
│   │   ├── Jobs/                   # AssignPickingTask
│   │   ├── Events/                 # ShipmentCreated
│   │   ├── Listeners/              # SendWebhookNotification
│   │   ├── Exports/                # OrdersExport
│   │   └── Providers/              # EventServiceProvider
│   ├── database/
│   │   ├── factories/              # OrderFactory
│   │   ├── seeders/                # DemoDatabaseSeeder
│   │   └── migrations/
│   │       ├── landlord/           # tenants 遷移
│   │       └── tenant/             # orders, order_items, inventories
│   ├── routes/                     # api.php, web.php, tenant/web.php
│   ├── config/                     # tenancy.php, queue.php, sanctum.php
│   ├── public/                     # index.php
│   ├── bootstrap/                  # Laravel 核心檔案
│   ├── artisan                     # Laravel CLI
│   ├── .env.example                # 環境配置
│   ├── composer.json               # 後端依賴
│   ├── Dockerfile                  # PHP 8.3 容器
│   └── nginx.conf                  # Nginx 配置
├── frontend/
│   ├── src/
│   │   ├── views/                  # OrderImport.vue, Inventory.vue, Report.vue
│   │   ├── components/             # LoadingSpinner.vue
│   │   ├── App.vue                 # 主組件
│   │   ├── main.js                 # Vue 入口
│   │   └── router.js               # 路由
│   ├── public/
│   │   └── index.html              # HTML 模板
│   ├── .env                        # 前端環境配置
│   ├── package.json                # 前端依賴
│   ├── vite.config.js              # Vite 配置
│   └── tailwind.config.js          # Tailwind CSS 配置
├── docker-compose.yml              # Docker 服務協調
└── README.md                       # 本文件
```

## 🚀 快速開始

### 前置條件
- Docker 和 Docker Compose
- Node.js (18+)
- PHP 8.3 和 Composer
- Git

### 1. 初始化 Laravel Skeleton
專案後端基於 Laravel 12 Skeleton，需先初始化核心檔案：
```bash
# 克隆專案
git clone <repository-url>
cd swift-3pl-platform

# 初始化 Laravel 專案
cd backend
composer create-project laravel/laravel . --prefer-dist
```

### 2. 安裝與設置
```bash
# 複製環境配置
cp backend/.env.example backend/.env

# 配置前端環境
cat > frontend/.env << 'EOF'
VITE_API_BASE_URL=http://localhost:8000
EOF
```

### 3. 後端啟動 (Docker)
```bash
# 安裝依賴
docker compose run --rm app composer install --working-dir=/var/www/html

# 啟動所有服務 (App, Nginx, MySQL, Redis, Queue)
docker compose up -d --build

# 初始化資料庫 (Landlord 遷移)
docker compose exec app php artisan migrate --path=database/migrations/landlord --force

# 初始化測試數據
docker compose exec app php artisan db:seed --class=DemoDatabaseSeeder

# 檢查 Queue Worker 日誌
docker compose logs -f queue

# 若需重啟 Queue Worker
docker compose restart queue
```

### 4. 前端啟動
```bash
cd frontend
npm install
npm run dev
```

### 5. 訪問應用
- **前端**：`http://localhost:3000` (Vite 預設端口)
- **後端 API**：`http://localhost:8000/api`
- **報表**：`http://localhost:8000/tenant/3pl_demo_co/report/excel`

## 🧪 測試指引
1. **訂單匯入**：
   - 訪問 `http://localhost:3000/orders/import`，提交訂單 (Tenant ID: `3pl_demo_co`)。
   - 檢查 Queue Worker 日誌：`docker logs swift_queue_worker` (應顯示 `AssignPickingTask` 執行)。
2. **庫存查詢**：
   - 訪問 `http://localhost:3000/inventory`，輸入 Tenant ID (`3pl_demo_co`) 和 SKU (`SKU-001`)。
   - 確認即時庫存顯示 (Redis 模擬)。
3. **報表導出**：
   - 訪問 `http://localhost:8000/tenant/3pl_demo_co/report/excel`，下載 Excel。
   - 訪問 `http://localhost:3000/report`，查看 Chart.js 圖表。
4. **Webhook 測試**：
   - 查詢資料庫取得訂單 ID (e.g., ID=1)。
   - 使用 Postman 呼叫：`POST http://localhost:8000/api/shipping/3pl_demo_co/1`。
   - 檢查 Queue Worker 日誌：`docker logs swift_queue_worker`，確認 `ShipmentCreated` 事件觸發 `SendWebhookNotification` 監聽器。
5. **API 認證 (Sanctum)**：
   - 取得 Token：
     ```bash
     curl -X POST http://localhost:8000/api/login \
     -H "Content-Type: application/json" \
     -d '{"email":"test@example.com","password":"password"}'
     ```
   - 攜帶 Token 測試 API：
     ```bash
     curl -X POST http://localhost:8000/api/orders/import \
     -H "Authorization: Bearer <token>" \
     -H "Content-Type: application/json" \
     -d '{"tenant_id":"3pl_demo_co","platform_order_id":"WEB-123","shipping_address":"台北市信義區","items":[{"sku":"SKU-001","quantity":2}]}'
     ```

## 📡 CI/CD 流程
專案使用 GitHub Actions 實現自動化 CI/CD (`.github/workflows/main.yml`)：
- **觸發**：Push 或 Pull Request 到 `main` 分支。
- **流程**：
  1. Checkout 程式碼。
  2. 構建 Docker 映像 (`swift-3pl-app`)。
  3. 啟動 MySQL/Redis 服務。
  4. 執行 `composer install`。
  5. 運行遷移 (`php artisan migrate`) 和種子 (`php artisan db:seed`)。
  6. 執行單元測試 (預留 `phpunit` 或 `pest`)。
- **驗證**：推送程式碼到 GitHub，檢查 Actions 標籤，確認 build 成功。

## 📝 開發注意事項
- **環境變數**：
  - 後端：檢查 `backend/.env`，確保 `DB_HOST=mysql_db`, `REDIS_HOST=redis`。
  - 前端：檢查 `frontend/.env`，確保 `VITE_API_BASE_URL=http://localhost:8000`。
- **Queue Worker**：保持 `swift_queue_worker` 運行，處理揀貨/Webhook 任務。
- **Axios 配置**：前端 `main.js` 可設置 Axios 基礎 URL：
  ```javascript
  import axios from 'axios';
  axios.defaults.baseURL = import.meta.env.VITE_API_BASE_URL;
  ```
- **擴展**：
  - 退貨：擴展 `Order.php` status 為 'cancelled'。
  - AI 優化：`PickingService.php` 座標可升級為 A* 演算法。
  - 國際物流：新增 Adapters (e.g., `FedExAdapter.php`)。

## 📜 技術棧
- **後端**：Laravel 12, PHP 8.3, Stancl/Tenancy 4.0, Maatwebsite/Excel, Predis
- **前端**：Vue 3.4, Pinia, Vue Router, Axios, Tailwind CSS, Chart.js
- **資料庫**：MySQL 8.0, Redis (alpine)
- **部署**：Docker, Nginx (alpine), GitHub Actions

## 🤝 貢獻
歡迎提交 Issue 或 Pull Request！請參考 `.github/workflows/main.yml` 執行測試後提交。

## 📬 聯繫
如有問題，請聯繫 <your-email@example.com> 或開啟 GitHub Issue。