/* Irina González theme — JS mínimo, sin dependencias (sistema v2). */
(function () {
  'use strict';
  var root = document.documentElement;
  root.classList.add('js');
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Reveal al hacer scroll.
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

  // Header: sólido al desplazarse; claro sobre cabecera a sangre. Parallax suave del fondo.
  var header = document.querySelector('.di-header');
  var bleed = document.querySelector('.di-bleed');
  var bgs = document.querySelectorAll('.di-bleed__bg');
  if (bleed) { document.body.classList.add('has-bleed'); }
  function onScroll() {
    var y = window.scrollY || window.pageYOffset;
    if (header) {
      header.classList.toggle('is-scrolled', y > 40);
      if (bleed) { header.classList.toggle('di-on-dark', y < bleed.offsetHeight - 84); }
    }
    if (!reduce) { bgs.forEach(function (b) { b.style.transform = 'translateY(' + (y * 0.25) + 'px)'; }); }
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  // Menú móvil.
  var burger = document.querySelector('.di-burger');
  var panel = document.getElementById('di-menu-panel');
  if (burger && panel) {
    burger.addEventListener('click', function () {
      var open = !panel.classList.contains('is-open');
      panel.classList.toggle('is-open', open);
      header.classList.toggle('is-open', open);
      document.body.classList.toggle('menu-open', open);
      burger.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && panel.classList.contains('is-open')) { burger.click(); burger.focus(); }
    });
  }

  // Barra móvil: reserva espacio inferior.
  if (document.querySelector('.di-mobile-bar')) { document.body.classList.add('has-mobile-bar'); }
})();
