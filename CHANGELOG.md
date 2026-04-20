# Changelog

All notable changes to Simple FAQ Manager will be documented here.

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).
This project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [1.0.6] — 2026-04-20

### Added

- **FAQs > Settings › Colors** section — four color pickers to match the FAQ to your brand:
  - *Accent Color* — borders, active buttons, focus rings, hover states (dark shade and light tint computed automatically)
  - *Question Text Color* — FAQ question headings and category titles
  - *Answer Text Color* — FAQ answer body text
  - *Item Background* — FAQ card / accordion toggle button background (answer background computed as a lighter tint)
- `sfm_hex_tint()` / `sfm_hex_shade()` PHP helpers to derive the accent tint and shade from the saved hex value
- Inline CSS via `wp_add_inline_style` overwrites the `:root` CSS custom properties defined in `frontend.css`
- `frontend.css` refactored to use CSS custom properties (`--sfm-accent`, `--sfm-accent-dark`, `--sfm-accent-bg`, `--sfm-question-text`, `--sfm-answer-text`, `--sfm-item-bg`, `--sfm-answer-bg`) — the file ships sensible defaults; the inline style overrides them with saved values

---

## [1.0.5] — 2026-04-20

### Changed

- **Widget FAQs admin page** — redesigned for at-a-glance status:
  - Summary count ("X of Y FAQs shown on widget") above the table, updates live after each toggle
  - Off-widget rows visually dimmed (reduced opacity), toggle and edit controls remain fully visible
  - Filter tabs — **All / On Widget / Off Widget** — instantly hide/show rows without a page reload; the active tab is highlighted

---

## [1.0.4] — 2026-04-20

### Fixed

- **FAQ List › Category filter not applied on page load when "All" button is hidden** — `activeCategory` was hardcoded to `'all'` at init; it now reads the initially active `.sfm-cat-btn` from the DOM, and `applyFilters()` runs immediately on load when the active category is not `'all'`.

---

## [1.0.3] — 2026-04-20

### Added

- **FAQ List › Exclusive Accordion** setting — when enabled (accordion mode only), opening one FAQ closes all others in the same group
- **FAQ List › "All" Button** setting — show or hide the "All" reset button inside the category filter row; when hidden, the first category button receives the `active` class by default

### Changed

- `sfmSettings` JS object now includes `listExclusive` flag consumed by the list toggle handler

---

## [1.0.2] — 2026-04-20

### Added

- **FAQs > Settings** page with three sections:
  - *FAQ List* — display mode (All Expanded / Accordion), show/hide search bar, show/hide category filter buttons, show/hide "Expand All / Collapse All" button (accordion mode only), sort order (Title A→Z, Title Z→A, Newest, Oldest, Custom)
  - *FAQ Widget* — open first item on load, exclusive accordion (close others when one opens)
  - *SEO* — output JSON-LD `FAQPage` schema on `[faq_list]` pages for Google rich results
- `includes/settings.php` — Settings API registration, field callbacks, sanitizer, `sfm_get_settings()` / `sfm_default_settings()` helpers
- `sfm_render_list_item()` — outputs either expanded or accordion HTML for each FAQ item in `[faq_list]`
- `sfm_sort_args()` — translates sort setting to `get_posts` orderby/order args
- `sfmSettings` JS object localized via `wp_localize_script` — provides display mode, widget behaviour flags, and i18n strings to the frontend script
- Accordion mode for `[faq_list]`: togglable answers with accessible `aria-expanded`, grouped inside a bordered card per category
- "Expand All / Collapse All" button for `[faq_list]` accordion mode
- Widget exclusive accordion and open-first-on-load behaviours in JS

### Changed

- `[faq_list]` now reads sort order, search bar, category filter, and display mode from settings instead of hard-coded defaults
- `sfm_render_accordion()` accordion toggle now closes sibling items when `widgetExclusive` is enabled

---

## [1.0.1] — 2026-04-20

### Fixed

- `[faq_list]` and `[faq_widget]` shortcodes now render FAQ answers with `wpautop()` + `wptexturize()` instead of `apply_filters('the_content', ...)`. Calling the full `the_content` filter chain from inside a shortcode callback caused two bugs: duplicate shortcode output on the page, and nested shortcode execution (e.g. `[faq_widget]` rendering inside an FAQ answer instead of the actual answer text).

### Added

- `sfm_render_faq_content()` helper in `shortcodes.php` — safe content renderer used by both shortcodes and the Elementor widget.
- `data/seed-faqs.php` — one-time data seeder that imports the Kyrim FAQ dataset (9 categories, ~130 FAQs in Indonesian) via WP-CLI or a guarded browser request; idempotent (skips existing titles).

---

## [1.0.0] — 2026-04-20

### Added

- `faq` custom post type with title (question) and editor (answer) support
- `faq_category` hierarchical taxonomy for grouping FAQs
- `sfm_show_on_widget` and `sfm_widget_order` post meta fields registered via `register_post_meta`
- FAQ Widget Options meta box on the FAQ edit screen for per-item widget control
- **FAQs > Widget FAQs** admin page with:
  - Toggle switch to enable/disable each FAQ in the widget (AJAX, no reload)
  - Drag-and-drop row reordering via jQuery UI Sortable (AJAX, no reload)
  - Success/error notice bar with auto-dismiss
- `[faq_list]` shortcode — all published FAQs grouped by category, all answers expanded, with:
  - Real-time keyword search (debounced, covers question and answer text)
  - Category filter buttons
- `[faq_widget]` shortcode — widget-enabled FAQs as a CSS `max-height` accordion respecting admin order
- Elementor 3.x widget (**FAQ Widget**) in a custom **FAQ** panel category, with:
  - Configurable title and HTML tag (H2, H3, H4, p)
  - Title colour and typography style controls
  - `get_style_depends` / `get_script_depends` for automatic asset loading
- `sfm_render_accordion()` shared helper used by both shortcode and Elementor widget
- Conditional asset enqueueing — CSS/JS only loaded on pages where shortcodes or the Elementor widget are present
- Activation hook: registers CPT and flushes rewrite rules
- Deactivation hook: flushes rewrite rules
- `readme.txt` (WordPress plugin directory format)
- `README.md` and `CHANGELOG.md`
