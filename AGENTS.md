## Project Summary
A catering e-commerce platform built with Laravel, featuring four distinct user roles: Customer, Courier, Admin, and Owner. The system manages menus, orders, deliveries, and business reports.

## Tech Stack
- Framework: Laravel 11
- Frontend: Blade, Tailwind CSS, Vite
- Database: MySQL
- Language: PHP 8.3

## Architecture
- Role-based access control (RBAC) using user levels and a separate customer guard.
- Models: Paket, Pemesanan, DetailPemesanan, Pengiriman, Pelanggan, User.
- Controller-based logic (refactoring from web.php).
- Directory Structure:
  - `resources/views/dashboard/`: Role-specific dashboards.
  - `app/Http/Controllers/`: Logic handling.
  - `app/Models/`: Eloquent models.

## User Preferences
- Use functional components when possible.
- No comments unless requested.
- Clean and modern UI with Tailwind.

## Project Guidelines
- Follow standard Laravel conventions.
- Maintain separate auth for customers and staff (admin, owner, kurir).
- Use `use client` for interactive components in a Next.js context (not applicable here, but good to note if we were using it).

## Common Patterns
- Dashboard prefixing for routes.
- Resource-based controllers for CRUD.
