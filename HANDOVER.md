# RenewPilot - Developer Handover & Setup Guide

Welcome to the RenewPilot Laravel 12 SaaS platform. This document contains everything you need to know to boot, understand, and continue developing this application.

## 1. Local Deployment (No XAMPP Required)

Yes, you can run this project easily without XAMPP if you have PHP and Composer installed, or if you use Docker/Laravel Sail. 

### Booting the Application (Standard PHP)
If you have PHP 8.3+ and Composer installed on your machine:

1. **Install Dependencies:**
   `composer install`
2. **Environment Setup:**
   `cp .env.example .env`
3. **Database Setup:** 
   Update your `.env` with your local MySQL credentials. If you are using a local MySQL service, just provide the standard credentials. If you are using SQLite for quick testing, change `DB_CONNECTION=sqlite` and remove the other DB credentials.
4. **Run Migrations & Seeders:**
   `php artisan migrate:fresh --seed`
   *(This will create the tables and inject the default Super Admin and Demo Tenant accounts).*
5. **Start the Server:**
   `php artisan serve`

*Note: The frontend currently uses CDN fallbacks for Tailwind and Alpine, meaning you do not need to run `npm install` or `npm run dev` to see the UI immediately.*

## 2. Core Architecture Overview

To keep this project understandable and maintainable, please adhere to the following architectural patterns already established in the codebase:

### Multi-Tenancy (Strictly Enforced)
*   **How it works:** This is a single-database, session-based multi-tenant application.
*   **The Rule:** Every model that belongs to an agency (Client, Invoice, Payment, Subscription) uses the `App\Models\Traits\HasTenantScope` trait.
*   **What it does:** It automatically adds `WHERE tenant_id = ?` to all Eloquent queries and automatically fills the `tenant_id` on creation based on the logged-in user's session.
*   **Danger:** Do not remove `HasTenantScope`. If you use raw `DB::table()` queries, you **must** manually append `->where('tenant_id', session('tenant_id'))`.

### Super Admin Bypass
*   The Super Admin panel (`/admin/...`) is protected by `App\Http\Middleware\AdminMiddleware`.
*   Because the Super Admin needs to see all tenants globally, the `AdminController` uses raw `DB::table()` queries. This naturally bypasses the Eloquent `TenantScope` securely.

### Thin Controllers / Fat Services
*   **Controllers** (e.g., `InvoiceController`) handle routing, request validation (via `FormRequests`), and view rendering.
*   **Services** (e.g., `PaymentService`, `ReminderEngineService`) contain the heavy business logic. If you need to write complex logic, create a Service class. 

### Shared Hosting Compatibility
*   This app is optimized to run on cheap shared hosting (cPanel).
*   **No Redis:** It uses the `database` driver for Cache, Queue, and Sessions.
*   **Cron Jobs:** The background tasks (emails, renewals) are processed by Laravel's Scheduler. The `routes/console.php` file is configured to run `queue:work --stop-when-empty` to prevent long-running daemon processes from crashing shared hosting accounts.

## 3. Key Files & Locations

*   **Routes:** `routes/web.php` (Contains Public, Tenant, and Admin route groups).
*   **UI/Views:** `resources/views/layouts/app.blade.php` (The master layout).
*   **Middleware:** `bootstrap/app.php` (Where `tenant` and `admin` aliases are registered).
*   **Settings Cache:** The SaaS dashboard reads KPIs directly from a JSON cache in the `settings` table to prevent N+1 queries. This cache is rebuilt nightly via `RecalculateDashboardStatsJob`.

## 4. Default Login Credentials
*(Generated automatically if you ran `php artisan migrate:fresh --seed`)*

*   **Super Admin:** `admin@renewpilot.com` / `password`
*   **Agency Owner:** `owner@demo.com` / `password`
