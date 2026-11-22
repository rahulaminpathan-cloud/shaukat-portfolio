(function ($) {
  'use strict';

  function initTmbHeroSliders($scope) {
    $scope.find('[data-hero-slider]').each(function () {
      var slider = this;
      if (slider.dataset.tmbInitialized === '1') return;
      slider.dataset.tmbInitialized = '1';

      var slides = slider.querySelectorAll('[data-hero-slide]');
      if (!slides.length) return;
      var current = 0;

      var show = function (idx) {
        current = (idx + slides.length) % slides.length;
        slides.forEach(function (el, i) {
          el.classList.toggle('is-active', i === current);
        });
      };

      slider.addEventListener('click', function (e) {
        var target = e.target;
        if (!(target instanceof Element)) return;
        if (target.closest('[data-hero-prev]')) show(current - 1);
        if (target.closest('[data-hero-next]')) show(current + 1);
      });
    });
  }

  function initTmbNewsletters($scope) {
    $scope.find('.tmb-ew-newsletter-form').off('submit.tmb').on('submit.tmb', function (e) {
      e.preventDefault();
      var form = $(this);
      var emailInput = form.find('input[name="email"]');
      var msgBox = form.find('.tmb-ew-newsletter-msg');

      if (typeof tmbEwConfig === 'undefined') return;

      $.post(tmbEwConfig.ajaxUrl, {
        action: 'tamanbd_newsletter_subscribe',
        nonce: tmbEwConfig.nonce,
        email: emailInput.val()
      })
        .done(function (res) {
          if (res && res.success) {
            msgBox.text(res.data.message).css('color', '#0F4C3A');
            emailInput.val('');
          } else {
            msgBox.text((res && res.data && res.data.message) || 'Subscription failed.').css('color', '#B91C1C');
          }
        })
        .fail(function () {
          msgBox.text('Network error. Please try again.').css('color', '#B91C1C');
        });
    });
  }

  $(window).on('elementor/frontend/init', function () {
    if (typeof elementorFrontend === 'undefined') return;
    elementorFrontend.hooks.addAction('frontend/element_ready/tmb-hero-slider.default', initTmbHeroSliders);
    elementorFrontend.hooks.addAction('frontend/element_ready/tmb-newsletter.default', initTmbNewsletters);
  });

  $(document).ready(function () {
    initTmbHeroSliders($(document));
    initTmbNewsletters($(document));
  });
})(jQuery);
