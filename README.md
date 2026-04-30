# 🖥️ System Operations Monitoring Platform

A real-time system monitoring dashboard built with Laravel + Vue 3 (Inertia.js) featuring live metrics, log streaming, charts, and filtering capabilities.

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

Laravel Backend → REST API + Broadcasting Events → Inertia.js Bridge → Vue 3 Frontend Dashboard → Chart.js + Reactive UI  

---

## ✨ Features

### 📊 System Monitoring
- CPU Usage tracking  
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
- Color-coded logs (INFO / WARNING / ERROR)  

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

```txt
app/
├── Http/
│   ├── Controllers/
│   │   ├── SystemDashboardController.php
│   │   ├── LogController.php
│
├── Events/
│   ├── LogCreated.php

resources/
├── js/
│   ├── Pages/
│   │   ├── Dashboard.vue
│   ├── Components/
│   │   ├── SystemCard.vue
│   ├── bootstrap.js
│   ├── app.js


---

## ⚙️ API Endpoints

### 📊 System Dashboard

GET /system/dashboard


Returns:
```json
{
  "server_status": "Online",
  "cpu_usage": "12%",
  "memory_usage": "48%",
  "active_users": 10,
  "alerts": "System running normally"
}
📜 System Logs
GET /system/logs

Returns:

{
  "logs": [
    {
      "level": "INFO",
      "message": "System started successfully"
    }
  ]
}
📡 Real-Time System (Optional Upgrade)
Event: LogCreated
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
#🧩 Frontend Dashboard Logic
Core Features:
Reactive state using ref()
Computed filtering system
Chart.js real-time updates
Polling system via setInterval
Axios API integration
Example Vue Features
Auto-refresh system data
Dynamic chart updates
Live log feed
Search + filter logs
#📊 Chart System
Library: Chart.js
Type: Line chart
Data:
CPU usage history
Memory usage history
Updates every 3 seconds
🔍 Filtering System Logic

#Logs are filtered using:

Log level (ALL / INFO / WARNING / ERROR)
Keyword search
Reactive computed property
⚠️ Known Limitations (Stage 3)
Uses polling instead of full WebSockets (optional upgrade ready)
Logs stored in memory (not persistent DB logs yet)
Basic authentication not enforced on APIs
Chart limited to last 10 data points
🚀 Future Improvements (Stage 4+)
🔥 Replace polling with WebSockets (Pusher / Soketi)
🗄️ Store logs in database
🔐 Add authentication & admin roles
📊 Advanced analytics dashboard
📱 Mobile responsive redesign
📡 Real server metrics integration
🧪 Setup Instructions
1. Clone repo
git clone https://github.com/your-repo.git
cd your-repo
2. Install dependencies
composer install
npm install
3. Environment setup
cp .env.example .env
php artisan key:generate
4. Run migrations
php artisan migrate
5. Run development servers
php artisan serve
npm run dev
🧑‍💻 Author

Developed as a system programming portfolio project focusing on:

Backend API design
Frontend reactivity
Real-time monitoring systems
Full-stack architecture
📌 License

This project is for educational and portfolio purposes.


---

# 🧠 WHAT YOU JUST GOT

This README now includes:

✔ Full architecture explanation  
✔ Full feature documentation  
✔ API structure  
✔ WebSocket readiness  
✔ Chart system explanation  
✔ Filtering system logic  
✔ Setup instructions  
✔ Future roadmap (Stage 4 direction)  

---

# 🚀 NEXT STEP (OPTIONAL)

If you want to level this up further, I can:

### 🔥 Make it even more portfolio-grade:
- :contentReference[oaicite:0]{index=0}
- :contentReference[oaicite:1]{index=1}
- :contentReference[oaicite:2]{index=2}
- :contentReference[oaicite:3]{index=3}
- :contentReference[oaicite:4]{index=4}

Just say:

👉 **“upgrade README to enterprise level”**
