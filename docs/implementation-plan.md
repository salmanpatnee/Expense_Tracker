# Implementation Plan — Expense Tracker

Last updated: 2026-08-28

Breaks `docs/project-scope.md` into an ordered, phased build plan. Each module (Categories, Expenses) has its backend and frontend work split into separate phases/tasks, so each phase is a small, reviewable, commit-sized chunk of work.

Order: **Foundation → Categories backend → Categories frontend → Expenses backend → Expenses frontend → Remaining work.** Categories comes before Expenses because an expense needs a category to belong to.

Read `docs/project-scope.md` for the "why" behind each decision, and `docs/design-tokens.md` for exact colors/fonts/spacing before touching any UI. Per `AGENT.md`, none of this starts until Phase 1 feedback comes back from the tutor.

## Phase 0 — Foundation & housekeeping

Gets the project able to run Vue inside Laravel, with nothing feature-specific yet.

- [x] Install frontend dependencies: `vue`, `@vitejs/plugin-vue`, `axios`, `chart.js`, `vue-chartjs`
- [x] Configure `vite.config.js` to build Vue single-file components
- [x] Set up MySQL: create the local database, update `.env` connection settings, confirm `php artisan migrate` runs cleanly against it
- [x] Create the base Blade view (`resources/views/app.blade.php`) with a Vue mount point (`<div id="app">`), loading the Google Fonts link and base styles from `docs/design-tokens.md` — built as self-hosted `bunny()` fonts via `vite.config.js` instead of a Google Fonts CDN link, and as a Tailwind v4 `@theme` block in `resources/css/app.css` instead of a Google Fonts `<link>` tag (see notes below)
- [x] Add the Tailwind token mapping from `docs/design-tokens.md` into `tailwind.config.js` — this project uses Tailwind v4 (CSS-first config, no `tailwind.config.js`), so tokens went into a `@theme` block in `resources/css/app.css` instead
- [x] Scaffold the root Vue app: `App.vue` with the header bar and a tab toggle (Expenses / Categories), both panels empty for now
- [x] Add a small `resources/js/api.js` module with a configured axios instance (base URL, JSON headers)
- [x] Verify: loading the app in the browser via Herd shows the header, tabs switch, no console errors — confirmed via browser check, DOM renders correctly, tab toggle is reactive, no console errors

**Notes from actually building this phase (fresh Laravel 13 skeleton differs from what the docs assumed):**
- Tailwind v4 is CSS-first — no `tailwind.config.js`. Tokens live in `resources/css/app.css`'s `@theme` block instead.
- Fonts are self-hosted via `laravel-vite-plugin`'s `bunny()` helper (already used for the default "Instrument Sans"), not a Google Fonts `<link>` tag — swapped it to Libre Franklin / Source Sans 3 / IBM Plex Mono.
- `routes/api.php` didn't exist yet in this Laravel 13 skeleton — ran `php artisan install:api` (also installs Laravel Sanctum and a `personal_access_tokens` migration we don't use, but it's harmless) to scaffold it before Phase 1 needs it.
- `app.blade.php` replaced `welcome.blade.php` as the `/` route; `welcome.blade.php` was deleted.

## Phase 1 — Categories: backend

- [x] `categories` migration: `id`, `name`, timestamps
- [x] `Category` Eloquent model
- [x] Seeder with default categories (Food, Transport, Rent, Utilities, Entertainment, Other)
- [x] `StoreCategoryRequest` / `UpdateCategoryRequest` form requests (name required, unique)
- [x] `CategoryController` as an API resource controller: index, store, update, destroy
- [x] Register routes in `routes/api.php`
- [x] Verify: exercise each endpoint (Tinker, Postman, or `php artisan route:list` plus manual curl/browser requests) and confirm validation errors return sensibly — verified via curl: index returns seeded categories in order, store rejects missing/duplicate name (422), update rejects duplicate but allows unchanged self-name, delete returns 204 and removes the row

**Notes from building this phase:**
- Plain Eloquent JSON responses (no API Resource classes) — six-field model doesn't need the extra layer.
- No `color`/`sort_order` column — the seeder inserts categories in the exact order the chart palette in `docs/design-tokens.md` expects, and `index` returns default `id` order, so the frontend zips list position with the fixed palette array.
- Routes registered with `Route::apiResource(...)->only([...])` — no `show` route, since nothing needs to fetch a single category.

## Phase 2 — Categories: frontend

- [x] `CategoriesPanel.vue`: list of categories, add-category form, inline rename, delete button
- [x] Wire panel into `App.vue`'s Categories tab
- [x] Style using the tokens from `docs/design-tokens.md` (card layout, chip/swatch style, spacing, radius) — match the look in the preview artifact
- [x] Verify: add, rename, and delete a category from the browser; confirm the list updates without a page reload

Note: the "can't delete a category that has expenses" guard is deferred to Phase 5, once expense data actually exists to test it against.

**Notes from building this phase:**
- No inline row editing — one shared add/edit form instead. Clicking "Edit" loads the category's name into the form and swaps the submit button to "Update" (with a "Cancel" button); this was a deliberate change from the plan's original "inline rename" wording, made before building.
- No `color` column on categories (per the Phase 1 decision) — the swatch dot color is computed client-side as `PALETTE[index % 6]`, a hardcoded array matching the design tokens' chart palette table.
- Delete uses a styled inline confirmation popover (`--color-danger`/`--color-danger-bg`), not a native `confirm()` dialog, per the design tokens' spec. Only one popover open at a time (`confirmingDeleteId` ref).
- List re-fetches from the API after every add/update/delete rather than updating the local array optimistically — simple and fine at this list size.
- No preview artifact existed to match against; built directly from `docs/design-tokens.md`.
- Verified end-to-end in the browser via Herd: add, rename, delete, and duplicate-name validation (422 "The name has already been taken.") all work and the list updates without a page reload.

## Phase 3 — Expenses: backend

- [x] `expenses` migration: `id`, `category_id` (foreign key to `categories`), `amount`, `description`, `date`, timestamps
- [x] `Expense` Eloquent model with `belongsTo(Category::class)`
- [x] `StoreExpenseRequest` form request (amount numeric and positive, category must exist, description required)
- [x] `ExpenseController` as an API resource controller: index, store, destroy
- [x] Totals endpoint (`GET /api/expenses/totals`): group expenses by category, sum amounts
- [x] Register routes in `routes/api.php`
- [x] Verify: exercise each endpoint manually, confirm totals endpoint returns correct sums for seeded/test data — verified via curl: store rejects negative/zero amount, nonexistent category, and missing description (all 422); index returns expenses ordered by date desc; totals returns all 6 categories with correct sums, including 0.00 for categories with no expenses; destroy returns 204; deleting a category with expenses attached is blocked by the DB foreign key (500 QueryException, to be caught cleanly in Phase 5)

**Notes from building this phase:**
- `amount` stored as `decimal(10,2)` — exact currency precision, no float rounding issues.
- `category_id` foreign key uses `restrictOnDelete()` — deleting a category that still has expenses fails at the database level right now (raw `QueryException`, not yet a friendly error). Phase 5's "can't delete a category with expenses" guard just needs to catch this and return a clean message, rather than building the check from scratch.
- Totals endpoint left-joins all categories against expenses and sums with `COALESCE(..., 0)`, so every category appears (even with zero expenses) — needed for the chart to show the full category list.
- `index` orders by `date` descending (most recent first) — not explicitly specified in the plan, chosen as the natural default for an expense list.
- Plain Eloquent JSON responses (no API Resource classes), consistent with Phase 1's `CategoryController`.

## Phase 4 — Expenses: frontend

- [x] `ExpensesPanel.vue`: add-expense form (date, category dropdown sourced from the categories API, description, amount), expense table, delete button per row
- [x] Chart: `vue-chartjs` doughnut component fed by `/api/expenses/totals`, refetched after every add/delete
- [x] Wire panel into `App.vue`'s Expenses tab (this is the default active tab)
- [x] Style using the tokens from `docs/design-tokens.md` — no preview artifact exists to match against (same as Phase 2), built directly from the tokens doc
- [x] Verify: add and delete an expense in the browser, confirm the table and chart update immediately without a page reload — verified via Herd browser check: add updates both table and chart, delete updates both back down, and a missing-description submit correctly shows the 422 validation message inline

**Notes from building this phase:**
- Backend tweak: `ExpenseController::index()` now eager-loads the category relation (`Expense::with('category')->orderBy(...)`) so each row carries `category.name` directly — avoids the frontend cross-referencing two separate lists.
- `PALETTE` (the 6-color category swatch/chart array) was extracted from `CategoriesPanel.vue` into a shared `resources/js/palette.js`, imported by both `CategoriesPanel.vue` and the new `ExpensesChart.vue` — keeps swatch colors and chart colors from drifting apart.
- Layout is two columns on desktop: form + table on the left, the doughnut chart in its own card on the right (stacking for mobile is deferred to the Phase 5 responsiveness pass).
- Delete reuses the exact same inline confirmation popover pattern as `CategoriesPanel.vue` (`confirmingDeleteId` ref, Cancel/Confirm buttons).
- Found and fixed a bug during verification: the table wrapper originally had `overflow-hidden` (for rounded corners), which silently clipped the absolutely-positioned delete-confirmation popover off-screen — the popover existed in the DOM but was invisible. Fixed by dropping `overflow-hidden` from the wrapper, matching the `CategoriesPanel.vue` list container which never had it.
- Amounts formatted as `$XX.XX` (e.g. `$42.75`), `font-mono text-figure` per the design tokens.
- Date field defaults to today (`new Date().toISOString().slice(0, 10)`).
- List/chart re-fetch from the API after every add/delete rather than updating local state optimistically, consistent with the Phase 2 approach.

## Phase 5 — Remaining work

Everything that only makes sense once both modules exist.

- [x] Add the "can't delete a category with expenses logged against it" guard: backend returns a clear error if expenses reference the category, frontend explains why via an inline message (see notes — changed from disabling the button to a reactive error)
- [x] Full responsiveness pass: resize the browser through mobile/tablet/desktop widths, fix any layout breakage per the course's responsiveness requirement
- [x] Visual consistency pass: compare both panels against `docs/design-tokens.md`, fix any drift (missing `--text-*` tokens)
- [x] Edge cases: empty states (no expenses yet, no categories yet, all-zero chart totals), invalid form input, decimal amount handling
- [x] Code documentation pass: comments where intent isn't obvious, per the course's "appropriately documented" requirement
- [x] End-to-end manual verification of the full app before moving on to Phase 2 (Development/Reflection) portfolio work

**Notes from building this phase:**
- **Category delete guard**: `CategoryController::destroy()` catches the `QueryException` thrown by `restrictOnDelete()` and returns a `422` with a clean message. The frontend doesn't disable the delete button proactively (no expense-count check on the categories index) — it attempts the delete and, on failure, swaps the confirmation popover's content for the error message plus a "Dismiss" button, reusing the same `bg-danger-bg` styling. This was a deliberate change from the original plan wording ("disables the delete button"), made before building, per the user's direction.
- **`--text-*` tokens**: added the six missing type-scale tokens (`--text-display`, `--text-h2`, `--text-h3`, `--text-body`, `--text-small`, `--text-figure`) to `app.css`'s `@theme` block — these were referenced by class name throughout both panels since Phase 2/4 but never defined, so text was silently falling back to Tailwind defaults.
- **Empty chart state**: `ExpensesChart.vue` now treats "all category totals are 0" the same as "no data" (shows "No data yet." instead of a six-slice doughnut of empty wedges) — the totals endpoint always returns all 6 categories via `COALESCE(..., 0)`, so a fresh install would otherwise render a full-looking chart with nothing in it.
- **Decimal amounts**: the amount is rounded to 2 decimals client-side (`Math.round(amount * 100) / 100`) before `ExpensesPanel.vue` posts it, so what's shown matches what's saved. In practice, the native `<input type="number" step="0.01">` already blocks the browser from submitting a value with more than 2 decimals (e.g. `12.999` fails HTML5 step validation silently, no request is even sent) — so this rounding is defensive/normalizing rather than something a user can trigger through the UI as built.
- **Responsive layout**: `ExpensesPanel.vue`'s two-column grid was restructured so the form, chart, and table are three separate grid items (previously the table was nested inside the same div as the form). Desktop keeps the original look via explicit `lg:order`/`lg:col-span`/`lg:row-span` placement (form + table in the left 2/3, chart spanning the right 1/3 top-to-bottom); on mobile (single column) the DOM/visual order is form → chart → table.
- **Popover-below-fold fix**: both `CategoriesPanel.vue` and `ExpensesPanel.vue` now measure the delete button's position on click (`getBoundingClientRect()`) and flip the confirmation popover to open upward (`bottom-full` instead of `top-full`) when it would overflow past the bottom of the viewport. Verified visually: last-row popovers now open upward and stay fully on-screen.
- Verified end-to-end via Herd browser check: category delete-blocked message displays and dismisses correctly; decimal-amount rounding and native step-validation behavior confirmed; empty chart state (0 expenses) shows "No data yet."; popover flip-up confirmed on the last category row.
- Could not visually verify the mobile/tablet breakpoint in the browser this session — the sandboxed Chrome window's viewport was stuck at 1366×551 regardless of `resize_window` calls (window resize appears unsupported in this environment). The `lg:` Tailwind breakpoint (1024px) and grid restructuring were verified by code inspection only; worth a manual resize check in a normal browser before final submission.
