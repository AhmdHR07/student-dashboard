# 🎓 Student Dev Dashboard

A full-stack CRUD application built to track daily learning tasks, study hours, and software engineering topics.

---

## 🚀 Live Demo & Links

- **Live Demo:** [Link to hosted app](https://your-app.infinityfreeapp.com) _(Optional)_
- **Database Schema:** [`db.sql`](./db.sql)

---

## ✨ Features

- **Task & Time Tracking:** Log project topics alongside exact hours spent.
- **Dynamic Stats Card:** Automatically totals task count and cumulative study time via SQL queries.
- **Client-Side Validation:** JavaScript checks to prevent empty entries or invalid hour ranges before form post.
- **Server-Side CRUD:** PHP script handling structured database inserts and query rendering.

---

## 🛠️ Tech Stack

- **Frontend:** HTML5, CSS3, JavaScript (ES6)
- **Backend:** PHP 8.x
- **Database:** MySQL / MariaDB (XAMPP Environment)

---

## 🗄️ Database Setup Instructions

1. Open **phpMyAdmin** or your MySQL client.
2. Create a new database named `student_dashboard`.
3. Import or execute the contents of the [`db.sql`](./db.sql) file provided in this repository.
4. Ensure your local PHP server settings in `api.php` match your database credentials:
   ```php
   $con = mysqli_connect("localhost", "root", "", "student_dashboard");
   ```
