# Laravel Filament Demo — TALL Stack Showcase

A full-stack Laravel 12 admin application built with the **TALL stack** (Tailwind CSS, Alpine.js, Livewire, Laravel) and **Filament 5** as the admin panel framework. The project demonstrates modular architecture, reactive UI components, REST API design, and real-world admin panel patterns.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.2 · Laravel 12 |
| Admin Panel | Filament 5 |
| Reactive UI | Livewire 4 |
| Client-side JS | Alpine.js (bundled with Livewire) |
| CSS | Tailwind CSS 4 |
| Modular Architecture | nWidart/laravel-modules 12 |
| Database | MySQL / SQLite |

---

## What the Application Does

### Core Domain

The application manages a simple **order management system** with three related entities:

- **Customers** — people who place orders
- **Products** — items available for purchase
- **Orders** — customer purchases containing one or more products

### Entity Relationships

```
Customer ──< Order ──< OrderItem >── Product
```

A customer can have many orders. Each order contains one or more order items. Each order item references a product and records the quantity and unit price at the time of purchase.

---

## Filament Admin Panel (`/dashboard`)

The admin panel is built with Filament 5 and provides full CRUD management for all entities.

### Customers

- Create, view, edit, and delete customers
- Fields: name, email, phone, address
- Soft deletes with trash filter and restore support
- Table shows order count per customer
- Searchable by name and email

### Products

- Create, view, edit, and delete products
- Fields: name, description, price, stock quantity
- Prices displayed in USD currency format
- Soft deletes with trash filter and restore support

### Orders

- Create, view, edit, and delete orders
- Fields: customer (searchable select), status, notes
- **Order items** are managed inline via a Repeater — selecting a product automatically fills the unit price
- Status filter: New · Processing · Shipped · Delivered · Cancelled
- Status displayed as colour-coded badges
- **Custom action**: "Update Status" button on each row opens a modal to change the order status and fires a success notification
- Soft deletes with trash filter and restore support

### Dashboard Widgets

Three widgets appear on the admin dashboard:

| Widget | Type | Description |
|---|---|---|
| Order Stats | Stats Overview | Total orders, total revenue, new orders pending, delivered orders — stat cards with sparkline |
| Orders by Status | Doughnut Chart | Visual breakdown of all orders across the five statuses |
| Orders – Last 30 Days | Line Chart | Daily order volume over the past 30 days |

---

## Reports Module (`/reports`)

A standalone module built with **nWidart/laravel-modules**, demonstrating modular architecture outside the Filament panel.

### Features

- Fully isolated module: own service provider, routes, controller, service class, Livewire component, and Blade views
- **Livewire 4** reactive component — changing the period or status filter instantly re-fetches stats from the server with no page reload (`wire:model.live`)
- **Alpine.js** tab switcher — three tabs (Overview, Daily Breakdown, REST API) toggle entirely client-side with no server round-trips (`x-data`, `@click`, `:class`)
- **`OrderReportService`** — dedicated OOP service class injected via the constructor; keeps business logic out of controllers and components
- `#[Computed]` properties on the Livewire component cache query results within a single render cycle

### Report Views

**Overview tab** — all-time order counts by status with percentage progress bars

**Daily Breakdown tab** — table of order counts per day for the selected period, with a proportional bar for each row

**REST API tab** — documents the API endpoint and provides a direct link to open it in the browser

---

## REST API

A versioned JSON API endpoint provided by the Reports module:

```
GET /api/v1/reports/orders/summary
```

**Query parameters:**

| Parameter | Type | Default | Description |
|---|---|---|---|
| `days` | integer (1–365) | `30` | Period window in days |
| `status` | string | `all` | Filter by order status |

**Example response:**

```json
{
  "summary": {
    "total_orders": 53,
    "total_revenue": 12480.50,
    "by_status": {
      "new": 10,
      "processing": 8,
      "shipped": 12,
      "delivered": 18,
      "cancelled": 5
    }
  },
  "filtered": {
    "count": 22,
    "revenue": 5340.00,
    "daily": {
      "2026-04-14": 1,
      "2026-04-15": 3
    }
  },
  "filters": {
    "days": 30,
    "status": "all"
  }
}
```

---

## Project Structure

```
app/
├── Filament/
│   ├── Actions/
│   │   └── UpdateOrderStatusAction.php   # Custom reusable Filament action
│   ├── Resources/
│   │   ├── Customers/                    # Customer CRUD resource
│   │   ├── Orders/                       # Order CRUD resource
│   │   └── Products/                     # Product CRUD resource
│   └── Widgets/
│       ├── OrderStatsOverview.php        # Stats cards widget
│       ├── OrdersByStatusChart.php       # Doughnut chart widget
│       └── DailyOrdersChart.php          # Line chart widget
├── Models/
│   ├── Customer.php
│   ├── Order.php
│   ├── OrderItem.php
│   └── Product.php
Modules/
└── Reports/                              # nWidart module
    ├── app/
    │   ├── Http/Controllers/
    │   │   ├── ReportsController.php     # Web controller
    │   │   └── Api/
    │   │       └── OrderSummaryController.php  # REST API controller
    │   ├── Livewire/
    │   │   └── OrderReport.php           # Livewire component
    │   ├── Providers/
    │   │   └── ReportsServiceProvider.php
    │   └── Services/
    │       └── OrderReportService.php    # OOP service layer
    ├── resources/views/
    │   ├── layouts/app.blade.php
    │   ├── index.blade.php
    │   └── livewire/order-report.blade.php
    └── routes/
        ├── web.php
        └── api.php
```

---

## Getting Started

### Requirements

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL or SQLite

### Installation

```bash
git clone <repo-url>
cd filamentProject

composer install
npm install

cp .env.example .env
php artisan key:generate

# Configure DB_* in .env, then:
php artisan migrate --seed

npm run build
php artisan serve
```

### Default Admin Credentials

| Field | Value |
|---|---|
| Email | `admin@example.com` |
| Password | `password` |

### Seed Data

The seeder populates:

- **20 customers** with realistic names, emails and addresses
- **15 products** (tech accessories with names and descriptions)
- **50+ orders** across all statuses, each with 1–5 line items

Re-seed at any time:

```bash
php artisan migrate:fresh --seed
```

---

## Key Patterns Demonstrated

| Pattern | Where |
|---|---|
| Modular architecture | `Modules/Reports` via nWidart |
| Service layer (OOP) | `OrderReportService` injected into controller and Livewire component |
| Reactive UI without page reload | `wire:model.live` on period/status filters |
| Client-side state vs server state | Alpine.js tabs (client) + Livewire data (server) |
| Custom Filament action | `UpdateOrderStatusAction` with modal form and notification |
| Computed properties | `#[Computed]` on `OrderReport` Livewire component |
| Soft deletes | All three main models with trash filter and restore |
| Relationship management | Repeater for inline order items in the order form |
| REST API versioning | `/api/v1/...` prefix in module routes |
