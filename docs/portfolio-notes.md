# DLBCSPJWD01 Portfolio — Working Notes

## Assignment summary
Course: **Project Java and Web Development (DLBCSPJWD01)** — IU International University
Task selected: **Develop a web application**

Portfolio runs in three sequential phases, submitted via PebblePad → Atlas. Order is strict — submitting out of sequence is an automatic fail.

| Phase | Deliverable | Key content |
|---|---|---|
| 1. Conception | PPTX, max 5 slides (no coding yet) | Purpose, target users, benefit, tech choices |
| 2. Development/Reflection | +10 slides, screencast embedded in PPTX | Public GitHub link, final stack, annotated screenshots, changes vs. Phase 1, test cases |
| 3. Finalization | +10 slides, zipped GitHub export | "Making of" abstract, lessons learned, full code + README |

**Deadlines found in the docs:** tutor feedback ≤ 7 days per phase; final grade ≤ 6 weeks after Phase 3 submission. No calendar dates are specified in these files — actual due dates live on myCampus.

**Hard constraints:**
- Front end: HTML/CSS/JS or a framework (Vue, React, JSF, etc.)
- Back end: any language/tech
- ≥ 2 dynamic JS-driven interactions with the backend
- Fully responsive (desktop + mobile/tablet)
- External APIs, if used, must be called only from the backend — optional, not required
- Public GitHub repo, documented code, README with install/run instructions
- One file per PebblePad upload (Phase 2 screencast must be embedded in the PPTX)
- Strict file naming: `Surname-First_MatrNo_DLBCSPJWD01_PhaseX_S`

**Grading weights:** Quality of implementation 40% · Methodology/ideas 20% · Creativity/correctness 20% · Problem definition 10% · Formal requirements 10%

**PebblePad text field:** the built-in text box inside each phase's template (e.g. the "Concept" box) — used for short notes (Phase 1, ≤ ½ A4 page) and for pasting the GitHub repo link (Phases 2 & 3).

## Decisions made so far
- **Current phase:** Phase 1 (concept) — not yet submitted. Per the guidelines, no coding should start until Phase 1 feedback is received.
- **App concept:** Expense Tracker — users log expenses with category/amount; backend aggregates totals into a live chart. Self-contained, no external API needed.
- **Two dynamic interactions:** (1) add/delete an expense via AJAX without page reload, (2) backend-aggregated category totals rendered live on a chart.
- **Stack:** HTML/CSS + Vue.js (front end), Laravel/PHP (back end), MySQL (database). Laravel vs. vanilla PHP was raised with the tutor (see email draft) and settled on Laravel; see `docs/project-scope.md` for the full set of build decisions.

## Full artifact
Published briefing with phase timeline, deadlines, constraints, and grading table: see conversation for link (Portfolio Briefing artifact).
