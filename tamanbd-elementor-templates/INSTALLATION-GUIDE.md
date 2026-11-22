# TamanBD WordPress + WooCommerce + Elementor — Complete Installation Guide

## Deliverables Package Overview
1. `tamanbd-theme.zip` — Custom Lightweight WordPress Theme (PHP 8.x, WooCommerce + Elementor Free/Pro compatible)
2. `tamanbd-elementor-widgets.zip` — Custom Elementor Widgets Plugin (10 drag-and-drop storefront widgets)
3. `tamanbd-elementor-homepage-template.zip` (contains `tamanbd-elementor-homepage.json`) — Full Elementor Homepage Template
4. `tamanbd-elementor-additional-templates.zip` (contains Header, Footer & Category Section JSON templates)

---

## Step 1: Install the Theme
1. Log in to **WordPress Dashboard**.
2. Go to **Appearance → Themes → Add New Theme → Upload Theme**.
3. Upload `tamanbd-theme.zip`, click **Install Now**, then **Activate**.

## Step 2: Install the Custom Elementor Widgets Plugin
1. Go to **Plugins → Add New Plugin → Upload Plugin**.
2. Upload `tamanbd-elementor-widgets.zip`, click **Install Now**, then **Activate Plugin**.
3. Confirm **Elementor** and **WooCommerce** are both active.

## Step 3: Import the Elementor Homepage Template
1. Unzip `tamanbd-elementor-homepage-template.zip` locally to extract `tamanbd-elementor-homepage.json`.
2. Go to **Templates → Saved Templates → Import Templates** and upload `tamanbd-elementor-homepage.json`.
3. Create (or edit) your **Home** page in **Pages → Add New**, set Page Attributes Template to **Elementor Full Width**, click **Edit with Elementor**, click the **Folder icon (Add Template) → My Templates**, and insert **TamanBD Premium Homepage**.
4. Go to **Settings → Reading → Your homepage displays → A static page** and select your **Home** page.

## Step 4: Edit Header & Footer Visually in Elementor Free (No Elementor Pro Needed)
1. Unzip `tamanbd-elementor-additional-templates.zip` and import `tamanbd-elementor-header.json` and `tamanbd-elementor-footer.json` into **Templates → Saved Templates**.
2. Go to **TamanBD Layouts → Add New** in the WordPress sidebar:
   - Create **Site Header**, click **Edit with Elementor**, insert the imported Header template, customize logo/menu/colors, and click **Publish**.
   - Create **Site Footer**, click **Edit with Elementor**, insert the imported Footer template, customize links/address, and click **Publish**.
3. Go to **Appearance → Customize → TamanBD Storefront Settings → Elementor Header & Footer (Free)** and assign your published **Site Header** and **Site Footer**.

## Step 5: Configure WooCommerce Pages & Menus
1. Verify **WooCommerce → Settings → Advanced** has **Cart**, **Checkout**, and **My Account** pages assigned.
2. Go to **Appearance → Menus** and assign your menus (`Primary Navigation`, `Footer Shop Links`, `Footer Customer Care Links`).
