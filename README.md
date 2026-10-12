# PocketRader - Pokemon Card Trading Platform & Marketplace

<p align="center">
  <img src="public/images/side_logo" alt="PocketRader Logo" width="220" />
</p>

<p align="center">
  A full-featured web-based Pokemon Trading Card Game (TCG) marketplace and collector platform built with <strong>Laravel 12</strong>, <strong>MySQL</strong>, and <strong>Midtrans Snap</strong> payment gateway.
</p>

---

## 📖 Complete Documentation
For exhaustive technical specifications, Entity Relationship Diagrams (ERD), controller workflows, database schemas, and route indexes, please refer to:
👉 **[PROJECT_DOCUMENTATION.md](PROJECT_DOCUMENTATION.md)**

---

## 🚀 Key Highlights & Features

- **Multi-Step KYC & OTP Registration:**
  - 3-step registration flow: Personal details $\rightarrow$ 6-digit email OTP verification $\rightarrow$ Government ID upload (KTP / SIM / Passport).
  - Strict middleware guards (`verified.transaction`) ensuring only KYC-verified users can buy or sell cards.
- **Steam Market-Style Trading:**
  - Card sets and expansions browser (`Genetic Apex`, `Mythical Island`, `Mega Rising`, `Crimson Blaze`).
  - Card detail pages featuring 30-day dynamic price charts, active market listings, condition badges, and sales history.
  - Multi-item and single-item P2P listing purchases with atomic database transactions (`DB::transaction`).
- **Inventory Binder & Lock/Unlock:**
  - Separate views for tradeable and locked cards to prevent accidental listings.
  - One-click listing creation directly from inventory.
- **In-Platform Wallet & Midtrans Payment Gateway:**
  - Midtrans Snap integration for wallet top-ups via GoPay, Virtual Accounts, QRIS, and Credit Cards.
  - Secure webhook endpoint with SHA-512 signature validation and automatic IDR-to-USD currency conversion.
- **P2P Live Messaging & Notifications:**
  - Direct buyer-seller chat system with real-time polling, avatars, and unread counts.
  - Comprehensive notification center for orders, sales revenues, wishlist changes, and system announcements.
- **Admin Management Portal (`/admin`):**
  - Identity verification request review (approve or reject with automatic file clean-up).
  - User moderation with account suspension and ban toggles.
  - Expansion set management and card catalog creation with automatic card count recalculations.
- **Analytics & Platform Insights (`/home`):**
  - Leaderboards for Top Seller, Top Buyer, Most Traded Card, and platform GMV.
  - Individual user sales and purchase statistics.

---

## 🛠️ Tech Stack

- **Backend:** Laravel 12 (PHP 8.2+)
- **Database:** MySQL / MariaDB (`pocketraderdb`)
- **Frontend:** Blade Templating, jQuery, Bootstrap Grid, LineIcons (`lni`), Chart.js
- **Payment Gateway:** Midtrans Snap API (Sandbox)
- **Mailing:** Laravel Mailable (`OtpEmail`)

---

## ⚡ Quick Start

### 1. Requirements
- PHP 8.2+
- Composer 2.x
- MySQL / MariaDB
- Node.js 18+ & NPM

### 2. Setup Instructions

```bash
# Clone the repository
git clone https://github.com/fertan15/BackEndWebProgramming-Project.git
cd BackEndWebProgramming-Project

# Install backend dependencies
composer install

# Install frontend dependencies
npm install

# Copy environment file and generate application key
cp .env.example .env
php artisan key:generate
```

### 3. Database Setup
Create a database named `pocketraderdb` and import the provided SQL dump:
```bash
mysql -u root -p pocketraderdb < pocketraderdb.sql
```

Update your `.env` database settings:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pocketraderdb
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run Development Servers
```bash
# Concurrently launch server, queue, logs, and Vite:
composer run dev

# Or run standard Laravel serve:
php artisan serve
```

Visit the application at: `http://localhost:8000`

---

## 📂 Project Structure Overview

```
├── app/
│   ├── Http/Controllers/    # Application controllers (Auth, Cards, Checkout, Chat, Admin, etc.)
│   ├── Http/Middleware/     # Auth & KYC transaction verification guards
│   ├── Mail/                # Mailable classes (OtpEmail)
│   └── Models/              # Eloquent models (Users, Cards, Listings, OrderItems, etc.)
├── pocketraderdb.sql        # Database schema and seed data
├── PROJECT_DOCUMENTATION.md # Comprehensive technical documentation & ERD
├── public/                  # Static assets & file uploads (register KYC documents)
├── resources/views/         # Blade views (Admin, Auth, Cards, Chat, Checkout, etc.)
└── routes/web.php           # Application web routes and API endpoints
```

---

## 📄 License
This project was developed for the **BackEnd Web Programming (BWP)** Course, Semester 3.
All Pokemon trademarks and card images are copyright of Nintendo, Creatures Inc., and GAME FREAK Inc.
