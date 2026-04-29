# 🖥️ System Monitoring Dashboard (Laravel + Vue 3)

A system programming learning project built with Laravel 10, Vue 3 (Inertia.js), and Axios that simulates a real-time system monitoring dashboard using polling architecture.

This project demonstrates backend API development, frontend reactivity, system design separation, and monitoring-style UI patterns.

---

# 🚀 Features

## 📊 System Metrics Dashboard
- CPU usage (simulated)
- Memory usage tracking
- Active users counter
- Server status indicator
- System alerts panel

## 📡 Live Log Viewer (Polling-based)
- Auto-refresh every 3 seconds
- Real-time-like log updates (polling)
- Color-coded log levels:
  - 🔴 ERROR
  - 🟡 WARNING
  - 🟢 INFO

## 🔄 Auto Refresh System
- Axios polling system
- Laravel API responses
- Vue 3 reactive updates

---

# 🧠 Architecture

Vue 3 (Inertia.js Frontend)
        ↓
Axios Polling (every 3 seconds)
        ↓
Laravel Routes
        ↓
Controllers
        ↓
JSON API Response
        ↓
Vue Reactive UI Updates

---

# 🛠️ Tech Stack

- Laravel 10
- Vue 3 (Composition API)
- Inertia.js
- Axios
- Tailwind CSS
- PHP 8.1

---

# 📁 Project Structure

app/
├── Http/
│   └── Controllers/
│       ├── SystemDashboardController.php
│       └── LogController.php

resources/
├── js/
│   ├── Pages/
│   │   └── Dashboard.vue
│   └── Components/
│       └── SystemCard.vue

routes/
└── web.php

---

# 🔌 API ENDPOINTS

## System Dashboard

GET /system/dashboard

Response:
{
  "server_status": "Online",
  "cpu_usage": "12%",
  "memory_usage": "48%",
  "active_users": 12,
  "alerts": "System Running Normally"
}

---

## System Logs

GET /system/logs

Response:
{
  "logs": [
    "[INFO] System started",
    "[WARNING] High memory usage",
    "[ERROR] Disk threshold reached"
  ]
}

---

# ⚙️ INSTALLATION

## 1. Clone Repository

git clone https://github.com/your-username/system-dashboard.git
cd system-dashboard

---

## 2. Install Dependencies

composer install
npm install
npm run dev

---

## 3. Setup Environment

cp .env.example .env
php artisan key:generate

---

## 4. Run Server

php artisan serve

Open:
http://127.0.0.1:8000/dashboard

---

# 🧪 HOW IT WORKS

1. Vue loads dashboard via Inertia
2. Axios requests Laravel APIs every 3 seconds
3. Laravel returns system metrics + logs
4. Vue updates UI reactively

---

# 🧠 WHAT YOU LEARN FROM THIS PROJECT

- Laravel API development
- Vue 3 reactive UI system
- Frontend-backend separation
- Polling-based monitoring systems
- Log visualization techniques
- System architecture design

---

# 🔮 FUTURE UPGRADES (STAGE 3+)

- WebSockets (real-time updates)
- Laravel events & broadcasting
- Live system metrics streaming
- AI-based anomaly detection
- Production-grade monitoring system

---

# 📌 STATUS

Stage 2: Stable ✔
Polling System: Working ✔
Real-time: Not yet implemented ❌
WebSockets: Planned 🔜

---

# 👨‍💻 AUTHOR

Built as a system programming learning project focused on backend architecture, frontend reactivity, and scalable system design.
