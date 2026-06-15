# LifeDrop - Blood Donation Management System

A full-stack blood donation management platform built with **Laravel 13** (backend API) and **Vue 3** (frontend SPA) using **Tailwind CSS v4** and **SQLite**.

Users can register as blood donors, book appointments, track donations, and request blood. Admins manage inventory, donation centers, and blood requests.

## Prerequisites

- **PHP** ^8.3
- **Composer**
- **Node.js** ^20.19 or ^22.12
- **npm**

## Getting Started

### 1. Clone the repository

```bash
git clone https://github.com/your-username/lifedrop.git
cd lifedrop
```

### 2. Backend Setup

```bash
cd backend

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Create SQLite database and run migrations
php artisan migrate

# (Optional) Seed sample data
php artisan db:seed
```

### 3. Frontend Setup

```bash
cd frontend

# Install npm dependencies
npm install

# Start the development server
npm run dev
```

### 4. Start the Backend

Open a second terminal:

```bash
cd backend
php artisan serve
```

### 5. Open the App

Visit **http://localhost:5173** in your browser. The Vite dev server proxies API requests (`/api/*`) to Laravel on port 8000.

## Project Structure

```
lifedrop/
├── backend/                  # Laravel 13 API
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/Api/   # API controllers
│   │   │   ├── Middleware/         # CheckRole middleware
│   │   │   └── Resources/         # API resource transformers
│   │   └── Models/                # Eloquent models
│   ├── database/
│   │   ├── migrations/            # Database schema
│   │   └── seeders/               # Sample data seeders
│   ├── routes/
│   │   └── api.php                # API routes
│   └── .env                       # Environment config
│
├── frontend/                 # Vue 3 SPA
│   └── src/
│       ├── api/                   # Axios API service functions
│       ├── assets/                # CSS and static assets
│       ├── components/
│       │   ├── home/              # Homepage sections
│       │   └── shared/            # Reusable UI components
│       ├── layouts/               # Admin, Donor, Guest layouts
│       ├── router/                # Vue Router config
│       ├── stores/                # Pinia state stores
│       └── views/                 # Page components (admin, auth, donor)
│
└── README.md
```

## Available Scripts

### Backend (`backend/`)

| Command | Description |
|---------|-------------|
| `php artisan serve` | Start Laravel dev server on port 8000 |
| `php artisan migrate` | Run database migrations |
| `php artisan db:seed` | Seed sample data |
| `php artisan test` | Run tests |
| `composer run dev` | Run full dev environment (server, queue, logs, Vite) |

### Frontend (`frontend/`)

| Command | Description |
|---------|-------------|
| `npm run dev` | Start Vite dev server on port 5173 |
| `npm run build` | Type-check and build for production |
| `npm run type-check` | Run Vue/TypeScript type checking |
| `npm run build-only` | Build for production (skip type-check) |
| `npm run preview` | Preview production build |

## API Endpoints

### Public (no auth required)
- `POST /api/register` — Create an account
- `POST /api/login` — Log in
- `GET /api/centers` — List donation centers
- `GET /api/blood-requests` — List blood requests
- `GET /api/inventory` — List blood inventory
- `GET /api/inventory/summary` — Inventory summary by blood type

### Donor (auth required)
- `GET /api/me` — Get profile
- `PUT /api/profile` — Update profile
- `POST /api/appointments` — Book appointment
- `GET /api/my-appointments` — My appointments
- `GET /api/my-donations` — Donation history
- `POST /api/blood-requests` — Submit a blood request

### Admin (auth + admin role required)
- `GET /api/admin/dashboard` — Dashboard stats
- CRUD for centers, appointments, donations, inventory, blood requests
- `GET /api/admin/donors` — Manage donors
- `PUT /api/admin/donors/{id}/toggle-eligibility`

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 13, PHP 8.3 |
| Frontend | Vue 3 (Composition API + `<script setup>`) |
| Styling | Tailwind CSS v4 |
| State | Pinia |
| Routing | Vue Router 5 |
| Auth | Laravel Sanctum (API tokens) |
| Database | SQLite |
| Build | Vite 8, TypeScript 6 |
