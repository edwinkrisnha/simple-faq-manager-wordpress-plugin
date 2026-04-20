# Changelog

All notable changes to Simple FAQ Manager will be documented here.

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).
This project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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
