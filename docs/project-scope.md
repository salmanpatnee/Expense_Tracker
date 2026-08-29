# Project Scope — Expense Tracker (DLBCSPJWD01)

Last updated: 2026-08-28

## What we are building

A single-user Laravel web app for tracking expenses. Laravel serves one Blade page that mounts a Vue 3 app (bundled via Vite). Vue drives two dynamic sections, Expenses and Categories, inside one page with a tab toggle, no page reloads. Laravel exposes JSON API endpoints that Vue calls via axios for all create/edit/delete actions and for the category-totals data that feeds a Chart.js chart. MySQL stores the data. No login/accounts, anyone with access to the app sees the same data.

## Terms

- **Expense**: a record with amount, category, date, and a short description.
- **Category**: a named grouping (e.g. Food, Transport) that each expense belongs to; fully user-manageable (create/rename/delete).
- **Dynamic interaction**: JS on the frontend calls a Laravel API endpoint and updates the page without a full reload.
- **User**: single user, no login/accounts.

## Decisions made

- **No authentication.** Not required by the course docs (confirmed by reading both assignment PDFs); single-user app.
- **Database: MySQL.** Chosen over SQLite for this project.
- **Categories get full CRUD, and it's dynamic like expenses.** Kept consistent across the app rather than mixing plain Blade forms with a Vue-driven expenses page.
- **No automated tests.** Pest is installed in the project but won't be used here. If Phase 2 needs test cases documented, they'll be described in the slides instead, which the course guidelines explicitly allow ("test cases if not coded in software").
- **Vue integration: Vite + Vue SFCs mounted on one Blade page.** Not Inertia.js, not CDN-only Vue. This is Laravel's default frontend tooling and keeps the setup simple to explain.
- **Chart library: Chart.js**, via the `vue-chartjs` wrapper for clean Vue component integration.
- **Single Blade page, one root Vue app, tab toggle between Expenses and Categories.** No `vue-router`, since there are only two views and no need for deep linking.

## Assumptions

- Styling: Tailwind CSS (Laravel's default Vite scaffold).
- HTTP client: axios (bundled by Laravel's default frontend scaffold).
- Data model:
  - `categories`: id, name, timestamps
  - `expenses`: id, category_id (foreign key), amount, description, date, timestamps
- API endpoints:
  - `GET/POST/DELETE /api/expenses`
  - `GET/POST/PATCH/DELETE /api/categories`
  - `GET /api/expenses/totals` (expenses aggregated by category, for the chart)
- Dev environment: Laravel Herd (already in use) + a local MySQL instance.

## Dependencies

**Backend (Composer, already in place or to add):**
- `laravel/framework` (already installed)
- No extra backend packages needed beyond what Laravel ships with — no auth package, no testing additions required.

**Frontend (npm, to add):**
- `vue` — frontend framework
- `@vitejs/plugin-vue` — lets Vite build `.vue` single-file components
- `axios` — HTTP client for calling the Laravel API from Vue
- `chart.js` — charting library for the live category-totals chart
- `vue-chartjs` — thin Vue wrapper around Chart.js

**Already available via Laravel's default scaffold:**
- Vite (build tool)
- Tailwind CSS (styling)

## How to build it

See `docs/implementation-plan.md` for the ordered, phased build plan (Foundation → Categories backend → Categories frontend → Expenses backend → Expenses frontend → remaining/polish work). That document is the single source of truth for build order; this file covers the "what" and "why," not the "in what order."

## Important reminder

Per the course rules, coding should only start once Phase 1 feedback comes back from the tutor. This document is the plan to execute at that point, not necessarily a signal to start building right now.
