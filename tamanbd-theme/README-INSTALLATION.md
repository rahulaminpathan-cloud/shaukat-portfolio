# TamanBD WordPress & WooCommerce Theme — Installation Guide

## Server & Hosting Requirements
- WordPress 6.2+
- WooCommerce 7.5+
- PHP 8.0, 8.1, 8.2, or 8.3 (Standard cPanel shared hosting supported)
- Zero Node.js or external database dependencies

## Installation Steps (WordPress Dashboard)
1. Upload the `tamanbd-theme.zip` archive via **WordPress Dashboard → Appearance → Themes → Add New Theme → Upload Theme**.
2. Click **Install Now** and then **Activate**.
3. Go to **Settings → Reading** and confirm your Front Page is set appropriately (or keep `front-page.php` active for the custom WooCommerce storefront).
4. Configure menus under **Appearance → Menus** and assign:
   - `Primary Navigation`
   - `Mobile Drawer Navigation`
   - `Footer Shop Links`
   - `Footer Customer Care Links`
5. Customize announcement text, hero banners, offer strip, and Dhaka store contact info under **Appearance → Customize → TamanBD Storefront Settings**.
6. *(Optional)* Add WooCommerce Filter widgets (Price Filter, Filter by Attribute, Product Categories) under **Appearance → Widgets → Shop Filter Sidebar**.

## Verification Checklist on Your Live WordPress Site
- Test **Shop** (`/shop/`), **Category Archive**, **Product Search** (`?s=...&post_type=product`), **Variable Product** selection, **AJAX Add to Cart**, **Buy Now**, **Cart / Checkout** (COD, bKash, Nagad), and **My Account** login/order history.
- Verify **Rank Math SEO** breadcrumbs and schema output without duplicate Product JSON-LD.
