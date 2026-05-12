# 🖥️ System Operations Monitoring Platform

![Laravel](https://img.shields.io/badge/Laravel-10+-red)
![Vue](https://img.shields.io/badge/Vue-3-42b883)
![Inertia](https://img.shields.io/badge/Inertia.js-Enabled-purple)
![Tailwind](https://img.shields.io/badge/TailwindCSS-Styled-38bdf8)
![Status](https://img.shields.io/badge/Project-Portfolio-blue)

A real-time system monitoring dashboard built with Laravel + Vue 3 (Inertia.js) that tracks system performance, logs, and alerts with near real-time updates.

---

## 🚀 Overview

This project simulates a server monitoring system with live metrics and system visibility features.

It tracks:
- CPU usage  
- Memory usage  
- Active users  
- System alerts  
- System logs  
- Performance history (charts)  

---

## 🧠 Architecture

Laravel Backend (Controllers + Middleware + API Routes)  
→ REST API Layer (/system/dashboard, /api/logs)  
→ Vue 3 Frontend (Inertia.js)  
→ Reactive UI + Polling System (3s interval)  
→ Chart.js Visualization + Alert System  

---

## ✨ Features

### Authentication System
- Login / logout
- Session-based auth
- Role-based access (admin/user)
- Protected routes

### System Monitoring
- CPU usage tracking
- Memory usage tracking
- Active users tracking
- Server status (ONLINE / DEGRADED / OFFLINE)
- Threshold-based alerts

### Alert System
- Auto-generated alerts
- Levels: INFO / WARNING / CRITICAL
- Auto-resolve when system normalizes
- Cache-based cooldown (prevents spam alerts)

### System Logs
- Live log viewing
- Manual log creation (testing)
- Levels: INFO / WARNING / ERROR
- Filter + search support

### Real-Time Charts
- Chart.js integration
- CPU + Memory history
- Auto-refresh every 3 seconds
- Stores last 50 data points

---

## 🛠️ Tech Stack

Backend:
- Laravel 10+
- REST API
- Middleware logging system
- MySQL

Frontend:
- Vue 3 (Composition API)
- Inertia.js
- Axios
- Chart.js

UI:
- Tailwind CSS

---

## 📁 Project Structure

app/
├── Http/
│   ├── Controllers/
│   │   ├── SystemDashboardController.php
│   │   ├── LogController.php
│   │   └── Pages/
│   └── Middleware/
│       └── LogSystemUsage.php
├── Models/
│   ├── SystemAlert.php
│   ├── SystemUsage.php
│   └── SystemLog.php

resources/
└── js/
    ├── Pages/
    │   ├── Dashboard.vue
    │   ├── SystemUsage.vue
    │   └── SystemLogs.vue
    ├── Components/
    └── app.js

---

## ⚙️ API Endpoints

GET /api/logs
[
  {
    "id": 1,
    "level": "INFO",
    "message": "System initialized",
    "created_at": "2026-05-12 10:00:00"
  }
]

🚨 Alerts Behavior
CPU ≥ 70% → WARNING
CPU ≥ 90% → CRITICAL
Memory thresholds similar
Auto-resolves when values normalize

🧩 Frontend Behavior
Polling every 3 seconds
Vue reactive state
Chart.js live updates
Alert rendering system
Computed UI status colors

⚠️ Known Limitations
No WebSockets (polling only)
Logs are not real-time streaming
Metrics are simulated (not real OS metrics)
No analytics persistence layer

🚀 Future Improvements
WebSockets (Laravel Reverb / Pusher)
Event-driven architecture upgrade
Real system metrics integration
Advanced analytics dashboard
Mobile UI optimization
Stronger RBAC system

🧪 Setup Instructions

git clone https://github.com/CAjmnz/system-operations-monitoring-platform.git
cd system-operations-monitoring-platform

composer install
npm install

cp .env.example .env
php artisan key:generate

php artisan migrate

php artisan serve
npm run dev

🧑‍💻 About
Built for learning system design and full-stack architecture using:

Laravel backend engineering
Vue 3 reactive UI design
Real-time monitoring concepts
Event-driven system thinking

📌 Educational / Portfolio Project
