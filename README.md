# 🎓 Student Dev Dashboard

A full-stack, database-driven web application designed to help student developers track daily study hours, log programming tasks, and monitor learning progress with dynamic statistical updates.

---

## 📽️ Showcase & Preview

[![Preview Dashboard Overview](https://img.shields.io/badge/Preview-Dashboard__Overview-0078D4?style=for-the-badge&logo=windows&logoColor=white)](./assets/preview.png)
[![Watch Demo](https://img.shields.io/badge/Watch_Demo-Site__Showcase-6B5B95?style=for-the-badge&logo=loom&logoColor=white)](YOUR_LOOM_OR_YOUTUBE_VIDEO_LINK_HERE)

> 💡 *Click the badges above to view the interface screenshots or watch the full site demonstration and breakdown.*

## 📌 Overview

Because GitHub Pages hosts static web assets only, **this application requires a local PHP server engine and a MySQL database to execute**. 

To test and run this project locally, download the project (`student-dashboard`) in (`C:\xampp\htdocs`), open **XAMPP (apache)** and click the project's folder, .

---

## 📦 Direct Download

[![Download](https://img.shields.io/badge/Download-Student__Dashboard.rar-0078D4?style=for-the-badge&logo=winrar&logoColor=white)](https://drive.google.com/drive/folders/15VIUgf7xU2Ychs-FjVT5pAKQQ8CJ1kVA?usp=drive_link)

*(Click the badge above to download the project or you can just download it from github).*

---

## ✨ Features

- **Task & Time Logging:** Record project topics alongside exact hours spent.
- **Dynamic Stats Calculation:** Real-time database queries automatically aggregate total completed tasks and cumulative study hours.
- **Server-Side Data Processing:** Uses HTTP POST requests handled directly by PHP to process input and update MySQL database records.
- **Relational Database Storage:** Powered by MySQL with structured table schemas and relational queries.

---

## 🛠️ Tech Stack & Requirements

- **Frontend:** HTML5, CSS3, JavaScript (ES6 / Fetch API)
- **Backend:** PHP 8.x
- **Database:** MySQL / MariaDB
- **Tools Needed:** 
  * [XAMPP](https://www.apachefriends.org/index.html) (Apache + MySQL)

---

## 🗄️ Project File Layout

```text
student-dashboard/
├── index.php             # Main UI dashboard layout
├── api.php               # Server-side PHP script (Database connection & CRUD operations)
├── app.js                # Frontend JavaScript logic (Fetch requests & DOM updates)
├── style.css             # App styling & responsive layout
├── student_dashboard.sql # MySQL database schema dump for phpMyAdmin import
