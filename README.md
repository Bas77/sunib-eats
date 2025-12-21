# 🍔 Sunib Eats
https://sunib-eats.tech/

**Sunib Eats** is a web-based platform designed to help the Binus University community (specifically around Alam Sutera) discover, review, and recommend the best local dining spots.

Built with **Laravel** and **Tailwind CSS**, it features a unique **Hot Score** algorithm to highlight trending restaurants and a comprehensive **admin dashboard** for content management.

---

## 🌟 Key Features

### 🍽️ Restaurant Discovery

Explore a curated list of restaurants with detailed information, prices, and locations.

### 🔥 Hot Restaurant Algorithm

A custom calculation engine that determines **"Hot" restaurants** based on a weighted mix of:

* **Average ratings (70%)**
* **Review popularity (30%)**

### ✍️ Community Reviews

Users can:

* Write reviews
* Rate restaurants
* Upload photos

### 👍 Voting System

Helpful / Not Helpful voting system for reviews.

### 🛡️ Role-Based Access

**User**

* Browse restaurants
* Write reviews
* Vote on reviews
* Manage profile

**Admin**

* Full dashboard access
* Manage users, restaurants, categories, promotions, and reviews

### 📱 Responsive Design

Built with **Tailwind CSS** and **Alpine.js** for a seamless mobile and desktop experience.

---

## 🛠️ Tech Stack

**Backend**

* Laravel Framework

**Frontend**

* Blade Templates
* Tailwind CSS
* Alpine.js

**Database**

* MySQL

**Build Tool**

* Vite

**Assets**

* Sass (SCSS) support

---

## 🚀 Installation & Setup

Follow these steps to set up the project locally.

### ✅ Prerequisites

* PHP >= 8.2
* Composer
* Node.js & NPM
* MySQL

---

### 1️⃣ Clone the Repository

```bash
git clone https://github.com/lightartz/sunib-eats.git
cd sunib-eats
```

---

### 2️⃣ Install Dependencies

Install PHP and JavaScript dependencies.

```bash
composer install
npm install
```

---

### 3️⃣ Environment Configuration

Copy the example environment file:

```bash
cp .env.example .env
```

Open `.env` and configure your database settings:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sunib_eats
DB_USERNAME=root
DB_PASSWORD=your_password
```

---

### 4️⃣ Generate App Key

```bash
php artisan key:generate
```

---

### 5️⃣ Database Migration & Seeding

Run migrations to create tables and seed the database with default users and dummy data.

```bash
php artisan migrate --seed
```

---

### 6️⃣ Run the Application

Start the local development server and Vite build process.

```bash
# Terminal 1: Laravel Server
php artisan serve

# Terminal 2: Vite (Asset bundling)
npm run dev
```

Visit **[http://localhost:8000](http://localhost:8000)** in your browser.

---

## 🔑 Default Credentials

The database seeder (`UserSeeder`) comes with pre-configured accounts for testing:

| Role  | Email                                     | Password |
| ----- | ----------------------------------------- | -------- |
| Admin | [admin@gmail.com](mailto:admin@gmail.com) | password |
| User  | [budi@email.com](mailto:budi@email.com)   | 12341234 |

---

## ⚙️ Console Commands

Sunib Eats uses a custom Artisan command to calculate restaurant **Hot Scores** to ensure performance scaling.

### 🔥 Calculate Hot Scores

Recalculates the trending score for all restaurants based on the formula:

```
(Avg Rating * 20 * 0.7) + (Review Count (capped at 50) * 0.3)
```

Run the command:

```bash
php artisan sunib:calculate-hot
```

> 💡 **Tip:** In a production environment, this should be scheduled to run periodically via the Laravel Scheduler.

---

## 📂 Project Structure

```
app/
├── Models/                     # Eloquent models (Restaurant, Review, User, Vote, etc.)
├── Http/Controllers/Admin/     # Controllers for Admin Dashboard (CRUD)
├── Services/RestaurantService.php
├── Console/Commands/CalculateHotRestaurants.php

routes/
└── web.php                     # Public, Auth, and Admin routes
```
