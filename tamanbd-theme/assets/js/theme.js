/**
 * TamanBD Theme Vanilla JavaScript
 * Lightweight, zero-dependency storefront interactions (PHP/WooCommerce compatible).
 */
(function () {
  'use strict';

  // 1. Dismiss announcement bar
  const dismissBtn = document.getElementById('tmb-dismiss-announcement');
  const announcement = document.getElementById('tmb-announcement');
  if (dismissBtn && announcement) {
    dismissBtn.addEventListener('click', function () {
      announcement.style.display = 'none';
    });
  }

  // 2. Hero Slider
  const slider = document.querySelector('[data-hero-slider]');
  if (slider) {
    const slides = slider.querySelectorAll('[data-hero-slide]');
    let current = 0;

    const showSlide = function (nextIndex) {
      if (!slides.length) return;
      current = (nextIndex + slides.length) % slides.length;
      slides.forEach(function (slide, idx) {
        slide.classList.toggle('is-active', idx === current);
      });
    };

    slider.addEventListener('click', function (e) {
      const target = e.target;
      if (!(target instanceof Element)) return;
      if (target.closest('[data-hero-prev]')) {
        showSlide(current - 1);
      } else if (target.closest('[data-hero-next]')) {
        showSlide(current + 1);
      }
    });
  }

  // 3. Homepage Collection Tabs (Best Selling / Featured / Latest / Discounts)
  const tabButtons = document.querySelectorAll('[data-collection-tab]');
  const tabPanels = document.querySelectorAll('[data-collection-panel]');
  tabButtons.forEach(function (btn) {
    btn.addEventListener('click', function () {
      const key = btn.getAttribute('data-collection-tab');
      tabButtons.forEach(function (b) {
        const active = b.getAttribute('data-collection-tab') === key;
        b.classList.toggle('is-active', active);
        b.setAttribute('aria-selected', active ? 'true' : 'false');
      });
      tabPanels.forEach(function (panel) {
        const active = panel.getAttribute('data-collection-panel') === key;
        panel.classList.toggle('is-active', active);
        if (active) {
          panel.removeAttribute('hidden');
        } else {
          panel.setAttribute('hidden', 'hidden');
        }
      });
    });
  });

  // 4. Mobile Search Toggle
  const mobileSearchBtn = document.getElementById('tmb-mobile-search-toggle');
  const mobileSearchBox = document.getElementById('tmb-mobile-search');
  if (mobileSearchBtn && mobileSearchBox) {
    mobileSearchBtn.addEventListener('click', function () {
      const isHidden = mobileSearchBox.hasAttribute('hidden');
      if (isHidden) {
        mobileSearchBox.removeAttribute('hidden');
      } else {
        mobileSearchBox.setAttribute('hidden', 'hidden');
      }
    });
  }

  // 5. Slide-out Drawers (Mobile Nav & Mini Cart)
  const bindDrawer = function (openBtnId, drawerId) {
    const openBtn = document.getElementById(openBtnId);
    const drawer = document.getElementById(drawerId);
    if (!openBtn || !drawer) return;

    const closeDrawer = function () {
      drawer.setAttribute('hidden', 'hidden');
      openBtn.setAttribute('aria-expanded', 'false');
    };

    openBtn.addEventListener('click', function () {
      drawer.removeAttribute('hidden');
      openBtn.setAttribute('aria-expanded', 'true');
    });

    drawer.querySelectorAll('[data-drawer-close]').forEach(function (el) {
      el.addEventListener('click', closeDrawer);
    });
  };

  bindDrawer('tmb-mobile-menu-open', 'tmb-mobile-drawer');
  bindDrawer('tmb-cart-drawer-open', 'tmb-cart-drawer');

  // Auto-open mini-cart drawer when WooCommerce fires added_to_cart event
  if (window.jQuery) {
    window.jQuery(document.body).on('added_to_cart', function () {
      const cartDrawer = document.getElementById('tmb-cart-drawer');
      if (cartDrawer) {
        cartDrawer.removeAttribute('hidden');
      }
    });
  }

  // 6. Single Product "Buy Now" Direct Checkout Trigger
  const buyNowBtn = document.querySelector('[data-tmb-buy-now]');
  if (buyNowBtn) {
    buyNowBtn.addEventListener('click', function () {
      const form = document.querySelector('form.cart');
      if (!form) return;
      let input = form.querySelector('input[name="tamanbd_buy_now"]');
      if (!input) {
        input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'tamanbd_buy_now';
        form.appendChild(input);
      }
      input.value = '1';
      const submitBtn = form.querySelector('button[type="submit"]');
      if (submitBtn) {
        submitBtn.click();
      } else {
        form.submit();
      }
    });
  }
})();
