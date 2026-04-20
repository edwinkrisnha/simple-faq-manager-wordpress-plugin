# Simple FAQ Manager

A WordPress plugin for managing FAQs with categories, drag-and-drop widget ordering, live search, and an Elementor widget — no external dependencies beyond WordPress core.

## Features

- **Custom Post Type** — FAQs with question (title) and answer (editor)
- **Taxonomy** — Hierarchical FAQ Categories
- **Widget FAQs admin page** — Toggle widget visibility and drag-and-drop reorder via AJAX
- **Settings page** — Control display mode, search, filters, sort order, widget behaviour, and SEO schema
- **`[faq_list]` shortcode** — All FAQs grouped by category with real-time search, category filters, configurable display mode (expanded / accordion), and optional Expand All / Collapse All button
- **`[faq_widget]` shortcode** — Widget-enabled FAQs as a CSS accordion with configurable open-first and exclusive-accordion behaviours
- **Elementor Widget** — Drag-and-drop into any Elementor page from the "FAQ" panel category
- **FAQ JSON-LD schema** — Outputs `FAQPage` structured data for Google rich results (togglable)

## Requirements

| Requirement | Version |
|---|---|
| WordPress | 6.0+ |
| PHP | 7.4+ |
| Elementor *(optional)* | 3.x |

## Installation

1. Copy the `simple-faq-manager` folder into `wp-content/plugins/`.
2. Activate via **Plugins > Installed Plugins**.
3. Create FAQs under **FAQs > Add New**.
4. Visit **FAQs > Widget FAQs** to configure the homepage widget.

## Shortcodes

### `[faq_list]`

Displays all published FAQs grouped by category. Includes:

- Real-time keyword search across questions and answers (JavaScript, no page reload)
- Category filter buttons
- All answers expanded by default

### `[faq_widget]`

Displays FAQs marked **Show on Widget**, ordered by the drag-and-drop admin order, as a smooth CSS accordion.

## File Structure

```
simple-faq-manager/
├── simple-faq-manager.php       # Main plugin file: CPT, taxonomy, meta, hooks
├── includes/
│   ├── admin-widget-faqs.php    # Admin page + AJAX handlers (order & toggle)
│   ├── shortcodes.php           # [faq_list], [faq_widget], sfm_render_accordion()
│   └── elementor-widget.php     # Elementor 3.x widget class
├── assets/
│   ├── css/
│   │   ├── admin.css            # Admin toggle switch, drag placeholder, notices
│   │   └── frontend.css        # FAQ list, accordion, category filters, responsive
│   └── js/
│       ├── admin-sortable.js    # jQuery UI Sortable + AJAX toggle
│       └── frontend-search.js  # Accordion + debounced search + category filter
├── includes/
│   └── settings.php             # Settings page, sfm_get_settings(), sanitizer
├── data/
│   └── seed-faqs.php            # One-time data seeder (Kyrim FAQ content) — delete after use
├── readme.txt                   # WordPress plugin directory readme
├── README.md                    # This file
└── CHANGELOG.md
```

## Data Seeder

`data/seed-faqs.php` imports the Kyrim FAQ dataset (9 categories, ~130 FAQs in Indonesian) into the database. Run it once, then delete the file.

**Via WP-CLI:**
```bash
wp eval-file wp-content/plugins/simple-faq-manager/data/seed-faqs.php
```

**Via browser** (while logged in as admin):
```
https://your-site.com/?sfm_run_seed=1&sfm_seed_key=sfm_seed_2024
```

> Change `sfm_seed_2024` in the script to your own secret before running in the browser. The script is idempotent — safe to re-run, existing FAQs are skipped.

## Usage

### Settings (FAQs > Settings)

| Setting | Options | Default |
|---|---|---|
| **Display Mode** | All Expanded, Accordion | All Expanded |
| **Search Bar** | Show / Hide | Show |
| **Category Filter** | Show / Hide | Show |
| **Expand / Collapse All** | Show / Hide *(accordion only)* | Show |
| **Sort Order** | Title A→Z, Z→A, Newest, Oldest, Custom | Title A→Z |
| **Open First Item** | On / Off | Off |
| **Exclusive Accordion** | On / Off | On |
| **FAQ Schema Markup** | On / Off | On |

### Admin: Widget FAQs

Go to **FAQs > Widget FAQs** to:

- **Toggle** the switch on any row to include/exclude it from the `[faq_widget]` and Elementor widget.
- **Drag** rows to set the display order. Changes save automatically via AJAX.

### Elementor

The **FAQ Widget** appears in the **FAQ** category in Elementor's panel. It supports:

- Custom title and HTML tag (H2–H4, p)
- Title color and typography controls

### Per-FAQ options

Each FAQ edit screen has a **FAQ Widget Options** meta box (sidebar) where you can manually set **Show on Widget** and **Widget Order**.

## Security

- All AJAX actions verified with WordPress nonces (`check_ajax_referer`)
- Capability check (`manage_options`) on every admin AJAX handler
- All output escaped with `esc_html`, `esc_attr`, `wp_kses_post`
- Meta box uses `wp_nonce_field` / `wp_verify_nonce`

## Customisation

All frontend classes are prefixed `sfm-`. Override them in your theme:

```css
/* Example: change the accordion header accent colour */
.sfm-accordion-header[aria-expanded="true"] {
    background: #f5f0ff;
    color: #6d28d9;
}

/* Example: change the FAQ list left border */
.sfm-faq-item {
    border-left-color: #6d28d9;
}
```

## License

GPL-2.0+. See [LICENSE](LICENSE).
