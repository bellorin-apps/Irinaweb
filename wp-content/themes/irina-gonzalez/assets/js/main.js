/* Irina González theme — JS mínimo, sin dependencias. */
(function () {
  'use strict';
  document.documentElement.classList.add('js');

  // Reveal con IntersectionObserver; respeta prefers-reduced-motion.
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var items = document.querySelectorAll('.di-reveal');
  if (!items.length || reduce || !('IntersectionObserver' in window)) {
    items.forEach(function (el) { el.classList.add('is-visible'); });
  } else {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) { e.target.classList.add('is-visible'); io.unobserve(e.target); }
      });
    }, { rootMargin: '0px 0px -10% 0px', threshold: 0.1 });
    items.forEach(function (el) { io.observe(el); });
  }

  // Barra móvil: reserva espacio inferior cuando existe.
  if (document.querySelector('.di-mobile-bar')) {
    document.body.classList.add('has-mobile-bar');
  }
})();
