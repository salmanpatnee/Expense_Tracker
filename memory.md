# Memory — DLBCSPJWD01 Portfolio (Web App Project)

Last updated: 2026-08-28 (session 10)

## What was built

Phase 5 (Remaining work) of `docs/implementation-plan.md` is done, verified, and committed (`aa6adc4 — Add Phase 5: category delete guard, responsive layout, and edge cases`).

- `app/Http/Controllers/CategoryController.php`: `destroy()` now wraps `$category->delete()` in try/catch for `QueryException` (thrown by `restrictOnDelete()` when expenses still reference the category) and returns a `422` with `{"message": "This category has expenses logged against it and can't be deleted."}`.
- `resources/js/components/CategoriesPanel.vue`: added a `deleteError` ref. `confirmDelete()` now catches the 422 and stores the message; the confirmation popover swaps its content to show the error + a "Dismiss" button (reusing `bg-danger-bg` styling) instead of closing silently. Also added a `popoverAbove` ref — `askDelete(event, id)` now takes the click event, measures the button's `getBoundingClientRect()`, and flips the popover to open upward (`bottom-full mb-2` vs `top-full mt-2`) when it would overflow past the viewport bottom.
- `resources/js/components/ExpensesPanel.vue`: same `popoverAbove` flip-up fix applied to the expense-row delete popover. `submitForm()` now rounds the amount to 2 decimals client-side (`Math.round(amount * 100) / 100`) before posting. The template was restructured — form, chart card, and table are now three separate grid items (previously table was nested inside the same div as the form) with explicit `lg:order`/`lg:col-span`/`lg:row-span` so desktop keeps its original two-column look (form+table left, chart right, chart spanning both rows) while mobile (single column) stacks in DOM order: form → chart → table.
- `resources/js/components/ExpensesChart.vue`: added a `hasData` computed (`totals.some(row => Number(row.total) > 0)`) — the doughnut now only renders when at least one category has a nonzero total; otherwise shows "No data yet." (previously it rendered a full six-slice doughnut of empty wedges whenever categories existed but had zero expenses, since the totals endpoint always returns all categories via `COALESCE(..., 0)`).
- `resources/css/app.css`: added the six missing `--text-*` tokens to the `@theme` block (`--text-display: 2rem`, `--text-h2: 1.4rem`, `--text-h3: 1.05rem`, `--text-body: 1rem`, `--text-small: 0.875rem`, `--text-figure: 1rem`) — these were referenced by class name (`text-h3`, `text-figure`, etc.) throughout both panels since Phase 2/4 but were never actually defined, so text was silently falling back to Tailwind/browser defaults. This was a known gap carried over from Phase 4.
- Verified end-to-end via Herd browser check: category delete-blocked message displays correctly and dismisses; decimal amount rounding works (though native `<input type="number" step="0.01">` already blocks the browser from submitting non-2-decimal values like `12.999` before it ever reaches the JS — confirmed via network tab, no request fires); empty-chart state confirmed showing "No data yet." at 0 expenses; popover flip-up confirmed on the last row of both the category list and (by the same code path) the expense table.
- Updated `docs/implementation-plan.md`: checked off every Phase 5 item, added a "Notes from building this phase" block (local-only, gitignored, not in the commit — same as every prior phase).
- Cleaned up all test data added during verification (a "Lunch" $1200 expense and a "Decimal test" $12.99 expense, both under Food) — DB is back to 6 seeded categories, 0 expenses.

## Decisions made

- **Category delete guard is reactive, not proactive** — no expense-count check added to `GET /categories`. The frontend just attempts the delete and shows an inline error on failure, in the same popover slot the confirm buttons occupied. This was an explicit correction from the original plan wording ("frontend disables the delete button") — the user said "do not disable instead show the notification that this category has expense entry" when this was discussed in architect mode.
- **422** (not 409) for the category-delete-blocked response, to stay consistent with how validation-style errors are already handled elsewhere in the app (`formError` pattern).
- **Mobile stacking order for `ExpensesPanel`**: form → chart → table (chart placed right after the form since it's a quick glance-value, not buried below a potentially long table).
- **Decimal rounding happens client-side in JS before submit**, even though in practice the native `step="0.01"` input already prevents non-2-decimal values from being submitted at all — the rounding is defensive/normalizing, not something a user can currently trigger through the UI as built.
- **Popover flip-up uses a JS position check on click** (`getBoundingClientRect()` + a fixed height estimate), not a pure-CSS solution — chosen because reliably detecting viewport overflow for an absolutely-positioned element needs a real measurement.
- Reconfirmed all prior-phase decisions unchanged.

## Problems solved

- Nothing new and unexpected this session beyond what's captured above as "what was built" — the delete-guard, popover flip, and empty-chart-state were all planned fixes for previously-known gaps (see Phase 4 memory), not surprises discovered mid-session.
- Minor false alarm during verification: typing `12.999` into the amount field and clicking "Add expense" appeared to silently do nothing (no network request, no console error). This was not a bug — it's the browser's native HTML5 `step="0.01"` constraint validation blocking submission of a value that isn't a multiple of the step, before any JS runs. Confirmed by retesting with a valid value (`12.99`), which submitted successfully (201) and rounded/displayed correctly.

## Current state

- **Phases 0–5 are all done, committed, and verified except one item.** The full app is functionally complete per `docs/implementation-plan.md`: categories and expenses are both manageable from the UI with live-updating chart, category deletion is guarded against orphaning expenses, decimal handling is safe, empty states are handled, and the design-token gap is closed.
- The DB currently has 6 seeded categories and 0 expenses (clean, matching the state at the end of every prior session).
- **One unverified item carried forward**: the mobile/tablet responsive breakpoint (`lg:` at 1024px) was implemented and verified correct by code inspection only — this session's browser sandbox could not actually resize below its fixed 1366×551 viewport (`resize_window` calls reported success but had no visible effect, and the Chrome tab became briefly unresponsive after repeated resize attempts — had to close and reopen the tab). Needs a real manual resize check (or a different browser environment) before final submission to be fully confident the Phase 5 responsiveness pass is complete.

## Next session starts with

Two things, in order:
1. **Manually verify the responsive breakpoint** that couldn't be checked this session — resize an actual browser window (not this sandboxed one) through mobile/tablet/desktop widths and confirm `ExpensesPanel`'s three-item grid (form → chart → table) stacks correctly on mobile, and that both delete-popover flip-fixes still behave correctly at narrow widths.
2. **Portfolio submission prep resumes** — Phase 2 (Development/Reflection) work per `AGENT.md`/`docs/project-scope.md`, now that all coding phases (0–5) are functionally complete. This was blocked on Phase 5 finishing; it's the first genuinely new phase of work, not carried-over cleanup.

## Open questions

- Whether Phase 1 (Conception) tutor feedback has actually come back yet, or whether the user is deliberately building ahead of it — still unclarified across sessions (per `AGENT.md`'s "coding should only start once Phase 1 feedback comes back" reminder in `docs/project-scope.md`). Worth confirming now that all coding phases are done and portfolio-writing work is about to start.
- Student's name and matriculation number are still placeholders in the PPTX and tutor email (unaddressed, carried over from earlier sessions).
- Whether an Artifact preview link should be shared with the tutor — likely moot, no such artifact was ever saved (reconfirmed again this session).
