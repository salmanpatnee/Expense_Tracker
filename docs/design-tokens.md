# Design Tokens — Expense Tracker

Last updated: 2026-08-28

Professional finance-software feel, built on the same navy/gold palette as the Phase 1 slide deck for continuity, but with its own typography rather than the deck's academic serif style (see `AGENT.md`). Not editorial, not playful, not a generic AI-purple-gradient look. Built to sit on top of Tailwind CSS.

## Typography

| Token | Value | Use |
|---|---|---|
| `--font-display` | `"Libre Franklin", "Segoe UI", sans-serif` | Page titles, section headings, the app name in the header |
| `--font-body` | `"Source Sans 3", "Segoe UI", sans-serif` | Body text, labels, table content, buttons |
| `--font-mono` | `"IBM Plex Mono", monospace` | Money amounts and any tabular numbers (gives figures a ledger-like, precise feel and keeps digits aligned in tables) |

A grotesk-serif-hybrid display face paired with a clean, neutral body face reads as professional finance software, not an academic slide deck or a literary editorial page.

Load via Google Fonts:
```html
<link href="https://fonts.googleapis.com/css2?family=Libre+Franklin:wght@600;700&family=Source+Sans+3:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
```

Type scale (rem, 16px base):

| Token | Size | Weight | Use |
|---|---|---|---|
| `--text-display` | 2rem / 32px | 700 (Libre Franklin) | App title / page heading |
| `--text-h2` | 1.4rem / 22px | 600 (Libre Franklin) | Section headings ("Expenses", "Categories") |
| `--text-h3` | 1.05rem / 17px | 600 (Source Sans 3) | Card/table headers |
| `--text-body` | 1rem / 16px | 400 (Source Sans 3) | Default body text |
| `--text-small` | 0.875rem / 14px | 400 (Source Sans 3) | Helper text, table meta, timestamps |
| `--text-figure` | 1rem / 16px | 500 (Plex Mono) | Expense amounts, totals |

## Color palette

Base is a warm, muted "paper and ink" academic palette — not a stark white/purple SaaS look.

| Token | Hex | Use |
|---|---|---|
| `--color-ink` | `#14213D` | Primary brand navy — header bar, primary buttons, active nav/tab, chart primary series |
| `--color-gold` | `#C9A227` | Accent — focus rings, active tab underline, highlighted totals, chart accent series |
| `--color-bg` | `#F7F6F3` | Page background (soft paper, not pure white) |
| `--color-surface` | `#FFFFFF` | Cards, table rows, modals |
| `--color-border` | `#E3E0D6` | Card/table/input borders |
| `--color-text` | `#1C1F26` | Primary text |
| `--color-text-muted` | `#5B6270` | Secondary text, labels, placeholders |
| `--color-success` | `#2F6F4E` | Confirmation states, "saved" feedback |
| `--color-danger` | `#9B2C2C` | Delete actions, validation errors |
| `--color-danger-bg` | `#F7E9E9` | Background for delete confirmation / error banners |

### Chart category palette (for Chart.js category-totals chart)

A muted, report-style qualitative palette — six colors, one per default category, extendable if the user adds more categories.

| Order | Hex | Suggested category |
|---|---|---|
| 1 | `#14213D` | Food |
| 2 | `#C9A227` | Transport |
| 3 | `#6B4226` | Rent |
| 4 | `#3E6259` | Utilities |
| 5 | `#7A5980` | Entertainment |
| 6 | `#8A8D91` | Other |

If a user adds more categories than the palette covers, cycle the palette rather than introducing new hues, to keep the chart visually consistent.

## Spacing scale

4px base unit, matches Tailwind's default scale so no config override is needed — just use these consistently instead of arbitrary values.

| Token | Value | Use |
|---|---|---|
| `--space-1` | 4px | Icon-to-label gaps |
| `--space-2` | 8px | Tight internal padding (badges, chips) |
| `--space-3` | 12px | Input padding, small gaps |
| `--space-4` | 16px | Default padding inside cards, form field spacing |
| `--space-6` | 24px | Spacing between form sections |
| `--space-8` | 32px | Spacing between major page sections |
| `--space-12` | 48px | Page top/bottom margins |

## Border radius

Restrained, not sharp/brutalist and not fully pill-shaped/playful.

| Token | Value | Use |
|---|---|---|
| `--radius-sm` | 4px | Inputs, badges, small buttons |
| `--radius-md` | 8px | Buttons, table cells with backgrounds |
| `--radius-lg` | 12px | Cards, modals, the chart container |

## Shadows

Subtle, used to lift cards and modals off the page background, never decorative glow.

| Token | Value | Use |
|---|---|---|
| `--shadow-sm` | `0 1px 2px rgba(20, 33, 61, 0.06)` | Cards, table container |
| `--shadow-md` | `0 4px 12px rgba(20, 33, 61, 0.12)` | Dropdowns, modals, the delete-confirmation popover |

## Tailwind mapping

Add to `tailwind.config.js` under `theme.extend` so these tokens are usable as utility classes (`bg-ink`, `text-gold`, `font-display`, etc.) instead of hardcoded hex values scattered through components:

```js
theme: {
  extend: {
    colors: {
      ink: '#14213D',
      gold: '#C9A227',
      bg: '#F7F6F3',
      surface: '#FFFFFF',
      border: '#E3E0D6',
      muted: '#5B6270',
      success: '#2F6F4E',
      danger: '#9B2C2C',
      'danger-bg': '#F7E9E9',
    },
    fontFamily: {
      display: ['"Libre Franklin"', '"Segoe UI"', 'sans-serif'],
      body: ['"Source Sans 3"', '"Segoe UI"', 'sans-serif'],
      mono: ['"IBM Plex Mono"', 'monospace'],
    },
    borderRadius: {
      sm: '4px',
      md: '8px',
      lg: '12px',
    },
    boxShadow: {
      sm: '0 1px 2px rgba(20, 33, 61, 0.06)',
      md: '0 4px 12px rgba(20, 33, 61, 0.12)',
    },
  },
}
```

## Applying these tokens

- Header bar and primary buttons: `bg-ink text-white`.
- Active tab (Expenses / Categories toggle) and focus rings: `border-gold` / `ring-gold`.
- Cards (expense list, category list, chart container): `bg-surface border border-border rounded-lg shadow-sm`.
- Delete buttons/confirmations: `text-danger`, `bg-danger-bg` for the confirm banner.
- Expense amounts and chart totals: `font-mono text-figure`.
- Page background: `bg-bg` on the `<body>` or root layout element.
