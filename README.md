# TradeCore 🚀

> **TradeCore** is a modern, modular Enterprise Resource Planning (ERP) platform designed to streamline Sales, Procurement, Inventory, and Accounting operations. It leverages a **Laravel** backend for robust business logic alongside a **FastAPI** AI microservice for OCR document processing, automated invoice parsing, and intelligent analytics.

## 🌟 Key Features

### 🛒 Sales & CRM

* Quotation & Sales Order Management.

* Customer profiles & credit limit controls.

* Point of Sale (POS) interface support.

* Automatic revenue & tax journal postings.

### 📦 Inventory & Warehouse Management

* Multi-warehouse tracking & internal transfers.

* Barcode/QR code generation & scanning.

* Batch & expiry date tracking.

* Automated reorder point alerts.

### 📜 Procurement & Purchasing

* Purchase Requests (PR) & Purchase Orders (PO) workflow.

* Multi-level approval pipelines.

* Automated Three-Way Matching (PO, Receipt, & Invoice).

### 💰 Accounting & Financials

* Dynamic Chart of Accounts (COA) with Cost Centers.

* Real-time automated General Ledger entries.

* Financial statements: Balance Sheet, Income Statement, & Trial Balance.

## 🛠️ Tech Stack

* **Primary Backend:** PHP 8.x, Laravel 13.x

* **Database:**  MySQL

* **Cache & Queue:** Redis

* **Frontend:** Blade / Inertia.js (Vue.js or React) + Tailwind CSS

## 🏗️ Architecture Overview

```
 +------------------------+               +--------------------------+
 |                        |  REST / gRPC  |                          |
 |    Laravel Backend     | ------------> |   FastAPI AI Engine      |
 |  (Business Logic/DB)   | <------------ |  (OCR, ML, Forecasting)  |
 |                        |     JSON      |                          |
 +------------------------+               +--------------------------+
             |                                         |
             v                                         v
     [ Database / Storage ]                    [ Machine Learning Models ]

```

## ⚙️ Installation & Setup

### Prerequisites

* PHP `>= 8.2` & Composer

* Node.js & npm

* MySQL

### 1. Backend (Laravel) Setup

```
# Clone the repository
git clone https://github.com/your-username/TradeCore.git
cd TradeCore

# Install PHP dependencies
composer install

# Install Frontend dependencies
npm install && npm run dev

# Environment setup
cp .env.example .env
php artisan key:generate

# Configure your database in .env, then run migrations & seeders
php artisan migrate --seed

# Start Laravel development server
php artisan serve

```

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.