# AGENT.md — Expense Tracker (DLBCSPJWD01 Coursework Project)

This is a Laravel application being built as coursework for **DLBCSPJWD01 — Project Java and Web Development** at IU International University. Read this before making changes so decisions stay consistent with what's already been agreed with the tutor.

## What this project is

An **Expense Tracker** web app: users log expenses with a category and amount; the backend aggregates totals and renders them on a live chart.

- Front end: HTML/CSS + Vue.js
- Back end: **Laravel** (PHP) — confirmed choice, not vanilla PHP
- Database: MySQL
- Required dynamic (AJAX-style) interactions with the backend, at minimum:
  1. Add/delete an expense without a page reload
  2. Backend-aggregated category totals rendered live on a chart

No external API is needed — the app is self-contained.

## Course structure (why this matters)

The portfolio is graded in **three sequential phases**, submitted via PebblePad → Atlas. Submitting out of order is an automatic fail, and per the guidelines **no coding should start until Phase 1 feedback comes back from the tutor.**

| Phase | Deliverable | Key content |
|---|---|---|
| 1. Conception | PPTX, max 5 slides, no code yet | Purpose, target users, benefits, tech choices, procedure |
| 2. Development/Reflection | +10 slides, screencast embedded in the PPTX | Public GitHub link, final stack, annotated screenshots, changes vs. Phase 1, test cases |
| 3. Finalization | +10 slides, zipped GitHub export | "Making of" abstract, lessons learned, full code + README |

**Current status: Phase 1 (Conception) drafted, not yet submitted.** Treat any request to start implementing app features as premature unless the user says Phase 1 feedback has come back.

Hard constraints that apply once coding starts:
- Fully responsive (desktop + mobile/tablet)
- Public GitHub repo, documented code, README with install/run instructions
- Strict file naming for every submission: `Surname-First_MatrNo_DLBCSPJWD01_PhaseX_S`
- One file per PebblePad upload (Phase 2's screencast must be embedded inside the PPTX itself)

Grading weights: Quality of implementation 40% · Methodology/ideas 20% · Creativity/correctness 20% · Problem definition 10% · Formal requirements 10%.

## Where things live

- **`docs/project-scope.md` — the project scope document. Read this before writing any code.** Covers the agreed architecture, decisions, dependencies, and build steps.
- **`docs/implementation-plan.md` — the phased build plan. Follow this order once coding starts.** Foundation & housekeeping → Categories backend → Categories frontend → Expenses backend → Expenses frontend → remaining/polish work. Each phase is a separate, commit-sized chunk with its own checklist.
- **`docs/design-tokens.md` — the design tokens. Read this before writing any UI/CSS/Vue markup.** Covers colors, typography, spacing, radii, shadows, and the Tailwind config mapping — use these instead of ad hoc values so every page/component looks consistent.
- `docs/portfolio-notes.md` — working notes on the assignment (phases, constraints, grading, decisions)
- `docs/tutor-email-draft.md` — draft email to the tutor about the Phase 1 submission (still has placeholders: tutor's name, student's name, matriculation number)
- `docs/Assignments Portfolio-DLBCSPJWD01-1.pdf`, `docs/Guidelines Portfolio-2.pdf` — official assignment source docs
- `docs/User Manual PebblePad  Atlas 1-3.pdf` — how to actually submit through PebblePad/Atlas
- `memory.md` — session-to-session handoff notes (see `/remember` skill); more detailed and time-stamped than this file
- Interactive UI preview (Artifact, not a repo file): https://claude.ai/code/artifact/dc1f008c-56fd-455d-b84d-a02eec02545e — a working mockup of the Expenses and Categories screens built from `docs/design-tokens.md`. Check it before building UI to see the tokens applied in context.

## Known open items

- Student's name and matriculation number are still placeholders everywhere (PPTX, tutor email) — needed before final submission.
- Actual calendar deadlines aren't in the assignment PDFs; they live on myCampus.
- **Portfolio PPTX lives at `docs/portfolio/Expense-Tracker-Portfolio.pptx`** (gitignored, local-only, not committed). Contains the title slide, Phase 1's 5 slides, and Phase 2's 10 slides (screenshots, final stack, changes vs. Phase 1, test cases, screencast placeholder). The embedded screencast still needs to be recorded and inserted by hand before Phase 2 submission.

## Working conventions for this repo

- Avoid em dashes in any text-heavy deliverables for this user (slides, emails, docs) — reads as AI-generated to them.
- **Portfolio slide deliverables (PPTX) follow `docs/design-tokens.md`**, the same palette and type system as the app itself (navy/gold, Libre Franklin/Source Sans 3/IBM Plex Mono), for visual continuity between the product and the slides describing it. Since PowerPoint export only supports web-safe fonts, use stand-ins: Arial Bold for `--font-display`, Arial for `--font-body`, Courier New for `--font-mono`. Tutor emails can stay plainer/more formal prose; this only applies to the slide visuals.
- This is a student project graded by a human tutor — keep implementation choices simple and explainable, not over-engineered, since methodology and problem definition are graded alongside code quality.

## Commit messages

- One line only, plain simple language, no jargon.
- **MOST IMPORTANT RULE: never mention Claude, AI, or that the commit was AI-assisted, anywhere in the commit message.** No "Co-Authored-By: Claude", no mention of Claude Code, nothing. Write it as if the student wrote it themselves.
