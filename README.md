# Vila Baleira — Custom WordPress Theme

A custom, high-performance WordPress theme created for **Vila Baleira Hotel Holding** (Porto Santo & Madeira). Built with responsive design principles, custom dynamic page templates, inline Advanced Custom Fields (ACF) schemas, and Tailwind CSS + Vanilla JS interactivity.

---

## 📋 Table of Contents

- [Project Status](#-project-status)
- [Key Features](#-key-features)
- [Technology Stack](#-technology-stack)
- [Directory & File Structure](#-directory--file-structure)
- [Custom Post Types & ACF Integration](#-custom-post-types--acf-integration)
- [Page Templates](#-page-templates)
- [Installation & Setup](#-installation--setup)
- [Development Guidelines](#-development-guidelines)
- [Credits](#-credits)

---

## 📊 Project Status

| Area | Status | Notes |
| :--- | :---: | :--- |
| **Theme Core** | ✅ Ready | Complete `functions.php`, `header.php`, `footer.php`, `style.css` |
| **Templates** | ✅ Ready | Homepage, O Grupo, Hotéis, Experiências, Gift Card, Contactos, Notícias, Single Notícia, 404 |
| **ACF Fields** | ✅ Complete | Programmatically registered in `functions.php` with fallback to WP Customizer |
| **Custom Post Types** | ✅ Active | `vbl_noticia` (Notícias) CPT fully configured with REST API support |
| **Typography & Styling** | ✅ Ready | Tailwind CSS configuration + local `OldStandardTT` and `Commissioner` fonts |
| **Interactivity (JS)** | ✅ Active | Slide-over drawer menu, desktop cross-fade hotel slider, mobile touch slider, AJAX newsletter |
| **AJAX Integration** | ✅ Ready | Nonce-secured Newsletter handler saving subscribers & firing email alerts |

---

## ✨ Key Features

1. **Tailwind CSS & Design Tokens**:
   - Custom color scheme defined: `--verde` (`#0d5257`), `--dourado` (`#bc945b`), `--bege` (`#eee8e5`), `--branco` (`#ffffff`), `--preto` (`#000000`).
   - Integrated font system using `OldStandardTT` for display headers and `Commissioner` for body text.
2. **Programmatic ACF Fields (Zero DB dependency for schemas)**:
   - All field groups for Homepage, Global Settings, O Grupo, Experiências, Hotéis, Gift Card, Contactos, and Notícias are defined via `acf_add_local_field_group()`.
   - Includes custom fallback helper functions `vbl_field()` and `vbl_option()` checking ACF first, then Customizer, then defaults.
3. **Dedicated Admin Options Page**:
   - Registered under **Vila Baleira** in the WP Admin menu with icon `dashicons-palmtree` for global contact info and social media links.
4. **Custom Post Type — Notícias (`vbl_noticia`)**:
   - Built-in post type for news articles with custom single template `single-vbl_noticia.php` and page model `page-modelo-noticia.php`.
5. **Interactive UI Components**:
   - Fullscreen / Slide-over Mobile Navigation drawer with responsive accordions.
   - Desktop & Mobile Hotel Carousels with cross-fade image switching and real-time content updates.
   - Gift Card Modal request form for digital and physical vouchers.
   - Newsletter subscription form with AJAX submission, input validation, and WP Mail marketing notifications.

---

## 🛠 Technology Stack

- **Core**: WordPress PHP Engine
- **Styling**: Tailwind CSS (loaded via CDN with inline runtime configuration) + Custom CSS (`style.css`)
- **Scripting**: Vanilla JavaScript (`assets/js/main.js`)
- **Custom Fields**: Advanced Custom Fields (ACF PRO / Free compatible via local JSON/PHP arrays)
- **Fonts**: Local Font Files (`assets/fonts/`) — Old Standard TT & Commissioner

---

## 📂 Directory & File Structure

```
vila-baleira-theme/
├── 404.php                    # Custom 404 error page template
├── footer.php                 # Global theme footer & widget area
├── front-page.php             # Homepage template
├── functions.php              # Theme setup, assets enqueue, CPT, ACF registration, AJAX handlers
├── header.php                 # Global theme header & slide-over mobile drawer
├── index.php                  # Fallback WordPress template
├── page.php                   # Generic page template
├── screenshot.jpg             # Theme screenshot preview
├── single.php                 # Default single post template
├── single-vbl_noticia.php     # Custom post type single template for Notícias
├── style.css                  # Theme declaration, design tokens, font definitions
├── assets/
│   ├── fonts/                 # Local font assets (OldStandardTT, Commissioner)
│   ├── images/                # Theme image assets
│   └── js/
│       └── main.js            # Main theme scripts (Menu, Carousels, AJAX)
├── page-templates/            # Custom Page Templates
│   ├── page-contactos.php     # Contactos page template
│   ├── page-experiencias.php  # Experiências page template
│   ├── page-giftcard.php      # Gift Card page template
│   ├── page-hoteis.php        # Hotéis overview template
│   ├── page-modelo-noticia.php# Detailed article template
│   ├── page-noticias.php      # News archive template
│   └── page-ogrupo.php        # O Grupo template
└── template-parts/            # Reusable PHP sub-components
    ├── section-newsletter.php # Global newsletter section
    ├── section-newsletter-home.php # Homepage-specific newsletter section
    └── section-noticias.php   # Reusable news grid section
```

---

## ⚙️ Custom Post Types & ACF Integration

### Custom Post Type: `vbl_noticia`
- **Slug**: `/noticia/`
- **Supports**: `title`, `editor`, `thumbnail`, `excerpt`
- **REST API**: Enabled (`show_in_rest => true`)

### ACF Options Page
- **Slug**: `vbl-global-settings`
- **Fields**: General Address, Phone, Email, Facebook, Instagram, YouTube.
- **Helper Usage**:
  ```php
  $phone = vbl_option('vbl_phone', '+351 291 980 800');
  $email = vbl_option('vbl_email', 'sales@vilabaleira.com');
  ```

### ACF Field Helper
```php
// Gets field value from ACF, falls back to WP Customizer theme mod, then default value
$hero_title = vbl_field('vbl_banner_line1', false, 'Default Title');
```

---

## 💻 Installation & Setup

1. **Theme Deployment**:
   Upload the `vila-baleira-theme` folder into your WordPress installation under `wp-content/themes/`.

2. **Activate Theme**:
   Go to **WP Admin -> Appearance -> Themes** and activate **Vila Baleira**.

3. **Install Required Plugins**:
   - **Advanced Custom Fields (ACF)** or **ACF Pro** (Recommended for editing options in WP Admin UI).

4. **Assign Page Templates**:
   Create pages in WP Admin and assign their respective template under **Page Attributes -> Template**:
   - Homepage -> Set as Front Page under **Settings -> Reading**.
   - O Grupo -> Template: `O Grupo`
   - Hotéis -> Template: `Hotéis`
   - Experiências -> Template: `Experiências`
   - Gift Card -> Template: `Gift Card`
   - Contactos -> Template: `Contactos`
   - Notícias -> Template: `Notícias`

5. **Navigation Menus**:
   Configure menus under **Appearance -> Menus** and set location assignments for:
   - Menu Principal
   - Menu Mobile
   - Menu Footer

---

## 📝 Development Guidelines

- **Inline ACF Modification**: To update or add new fields, edit `functions.php` inside `vbl_register_acf_fields()` or the respective page field group functions.
- **JavaScript Interactivity**: Add global component scripts in `assets/js/main.js`. Ensure event listeners check for DOM element presence before binding.
- **Color & Style Updates**: Update CSS variables in `style.css` and mirror changes in the inline Tailwind script configuration in `functions.php` (`vbl_enqueue_assets()`).

---

## 🤝 Credits

- **Client**: Vila Baleira Hotel Holding
- **Agency / Author**: Sanzza ([https://sanzza.com](https://sanzza.com))
- **Version**: 1.5.1
