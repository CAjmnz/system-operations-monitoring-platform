# 🖥️ System Operations Monitoring Platform
 
A real-time system monitoring dashboard built with **Laravel + Vue 3 (Inertia.js)** featuring live metrics, log streaming, charts, and filtering capabilities.
 
---
 
## 🚀 Project Overview
 
This project simulates a server monitoring system that displays:
 
- CPU usage
- Memory usage
- Active users
- System alerts
- Live logs
- Historical performance charts
---
 
## 🧠 System Architecture
 
```
Laravel Backend → REST API + Broadcasting Events → Inertia.js Bridge → Vue 3 Frontend Dashboard → Chart.js + Reactive UI
```
 
---
 
## ✨ Features
 
### 📊 System Monitoring
- CPU usage tracking
- Memory usage tracking
- Active user count
- Server status indicator
- System alerts
### 📈 Data Visualization
- Real-time line chart (Chart.js)
- CPU history tracking
- Memory history tracking
- Auto-updating graph every few seconds
### 📜 Live Logs System
- Live system logs feed
- Auto-refresh polling system
- Color-coded logs (`INFO` / `WARNING` / `ERROR`)
### 🔍 Log Filtering System
- Filter by log level
- Search logs by keyword
- Combined filtering
---
 
## 🛠️ Tech Stack
 
**Backend**
- Laravel 10+
- Broadcasting (Events)
- REST API
**Frontend**
- Vue 3 (Composition API)
- Inertia.js
- Axios
- Chart.js
**Styling**
- Tailwind CSS
---
 
## 📁 Project Structure
 
```
app/
├── Http/
│   └── Controllers/
│       ├── SystemDashboardController.php
│       └── LogController.php
└── Events/
    └── LogCreated.php
 
resources/
└── js/
    ├── Pages/
    │   └── Dashboard.vue
    ├── Components/
    │   └── SystemCard.vue
    ├── bootstrap.js
    └── app.js
```
 
---
 
## ⚙️ API Endpoints
 
### 📊 `GET /system/dashboard`
 
Returns current system metrics.
 
```json
{
  "server_status": "Online",
  "cpu_usage": "12%",
  "memory_usage": "48%",
  "active_users": 10,
  "alerts": "System running normally"
}
```
 
### 📜 `GET /system/logs`
 
Returns recent system logs.
 
```json
{
  "logs": [
    {
      "level": "INFO",
      "message": "System started successfully"
    }
  ]
}
```
 
### 📡 Real-Time Broadcasting (Optional Upgrade)
 
The `LogCreated` event is ready for WebSocket integration via Pusher or Soketi.
 
```php
class LogCreated implements ShouldBroadcast
{
    public $message;
 
    public function __construct($message)
    {
        $this->message = $message;
    }
 
    public function broadcastOn()
    {
        return new Channel('logs');
    }
 
    public function broadcastAs()
    {
        return 'log.created';
    }
}
```
 
---
 
## 🧩 Frontend Dashboard
 
**Core features:**
- Reactive state using `ref()`
- Computed filtering system
- Chart.js real-time updates
- Polling system via `setInterval`
- Axios API integration
**Chart system:**
- Library: Chart.js
- Type: Line chart
- Tracks: CPU usage history and memory usage history
- Updates every 3 seconds
- Stores last 10 data points
**Log filtering:**
- Filter by level: `ALL` / `INFO` / `WARNING` / `ERROR`
- Keyword search
- Driven by a reactive computed property
---
 
## ⚠️ Known Limitations
 
- Uses polling instead of full WebSockets (upgrade path is ready)
- Logs stored in memory, not persisted to a database
- No authentication enforced on API endpoints
- Chart limited to the last 10 data points
---
 
## 🚀 Future Improvements
 
- 🔥 Replace polling with WebSockets (Pusher / Soketi)
- 🗄️ Store logs in a database
- 🔐 Add authentication and admin roles
- 📊 Advanced analytics dashboard
- 📱 Mobile-responsive redesign
- 📡 Real server metrics integration
---
 
## 🧪 Setup Instructions
 
```bash
# 1. Clone the repository
git clone https://github.com/your-repo.git
cd your-repo
 
# 2. Install dependencies
composer install
npm install
 
# 3. Set up environment
cp .env.example .env
php artisan key:generate
 
# 4. Run migrations
php artisan migrate
 
# 5. Start development servers
php artisan serve
npm run dev
```
 
---
 
## 🧑‍💻 About
 
Developed as a system programming portfolio project, focusing on:
 
- Backend API design
- Frontend reactivity with Vue 3
- Real-time monitoring systems
- Full-stack architecture
---
 
> 📌 This project is for educational and portfolio purposes.
 
