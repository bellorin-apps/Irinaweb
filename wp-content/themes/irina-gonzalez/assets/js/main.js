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

  // Credenciales de la sección de la Dra.: un ítem a la vez, rotación automática, puntos y anillo, en todos los anchos (propietario, 2026-10-09/10).
  var creds = document.querySelector('.di-section--doctor-bg .di-creds');
  if (creds && creds.children.length > 1) {
    var items = Array.prototype.slice.call(creds.children);
    var dots = null, timer = null, idx = 0;
    function show(i, dir) {
      idx = (i + items.length) % items.length;
      items.forEach(function (li, k) {
        var on = k === idx;
        li.classList.remove('is-in', 'is-out-left');
        li.classList.toggle('is-active', on);
        if (on) {
          if (dir < 0) { li.classList.add('is-out-left'); }
          // Entrada sutil y rápida (fade + 10 px): se activa en el siguiente frame para que la transición corra.
          requestAnimationFrame(function () { requestAnimationFrame(function () { li.classList.remove('is-out-left'); li.classList.add('is-in'); }); });
        }
      });
      if (dots) { Array.prototype.forEach.call(dots.children, function (b, k) { b.setAttribute('aria-current', k === idx ? 'true' : 'false'); }); }
    }
    // Anillo de progreso (CSS di-ring, 4 s): visible solo mientras corre el autoplay; se reinicia en cada arranque forzando un reflow.
    function ring(on) { if (!dots) { return; } dots.classList.remove('is-playing'); if (on) { void dots.offsetWidth; dots.classList.add('is-playing'); } }
    function start() { if (reduce || timer) { return; } timer = setInterval(function () { show(idx + 1, 1); }, 4000); ring(true); }
    function stop() { clearInterval(timer); timer = null; ring(false); }
    var resumeTimer = null;
    function pauseThenResume() { stop(); clearTimeout(resumeTimer); resumeTimer = setTimeout(start, 8000); }
    // Deslizar con el dedo: umbral 40 px horizontal.
    var sx = 0, sy = 0, swiping = false;
    creds.addEventListener('touchstart', function (e) { sx = e.touches[0].clientX; sy = e.touches[0].clientY; swiping = true; }, { passive: true });
    creds.addEventListener('touchend', function (e) {
      if (!swiping) { return; } swiping = false;
      var dx = e.changedTouches[0].clientX - sx, dy = e.changedTouches[0].clientY - sy;
      if (Math.abs(dx) > 40 && Math.abs(dx) > Math.abs(dy)) { show(idx + (dx < 0 ? 1 : -1), dx < 0 ? 1 : -1); pauseThenResume(); }
    }, { passive: true });
    function setup() {
      if (creds.classList.contains('di-creds--carousel')) { return; }
      // Altura fija = ítem más alto, para que la sección no salte entre ítems.
      var h = 0; items.forEach(function (li) { h = Math.max(h, li.getBoundingClientRect().height); });
      creds.classList.add('di-creds--carousel'); creds.style.minHeight = h ? h + 'px' : '';
      creds.setAttribute('aria-live', 'polite');
      dots = document.createElement('div'); dots.className = 'di-creds__dots';
      items.forEach(function (li, k) {
        var b = document.createElement('button'); b.type = 'button';
        b.setAttribute('aria-label', 'Credencial ' + (k + 1) + ' de ' + items.length);
        b.innerHTML = '<svg viewBox="0 0 18 18" aria-hidden="true" focusable="false"><circle class="di-ring__track" cx="9" cy="9" r="7.5"/><circle class="di-ring__bar" cx="9" cy="9" r="7.5"/></svg>';
        b.addEventListener('click', function () { show(k, k > idx ? 1 : -1); pauseThenResume(); });
        dots.appendChild(b);
      });
      creds.insertAdjacentElement('afterend', dots);
      show(0, 1); start();
    }
    setup();
  }

  // Indicador de scroll del hero: baja hasta el final de la cabecera a sangre.
  var cue = document.querySelector('.di-scroll-cue');
  if (cue) {
    cue.addEventListener('click', function () {
      var hero = cue.closest('.di-bleed') || cue.parentElement;
      window.scrollTo({ top: hero.getBoundingClientRect().bottom + window.scrollY, behavior: reduce ? 'auto' : 'smooth' });
    });
  }

  // Formulario de contacto: envío por fetch a admin-post (acción di_contact); sin JS el servidor redirige igual.
  var form = document.querySelector('.di-form');
  if (form && window.fetch) {
    var status = form.querySelector('.di-form__status');
    var fields = { name: 'di_name', phone: 'di_phone', email: 'di_email', motivo: 'di_motivo', consent: 'di_consent' };
    function showError(msg, field) {
      status.textContent = msg; status.hidden = false; status.classList.remove('is-ok');
      form.querySelectorAll('.is-invalid').forEach(function (el) { el.classList.remove('is-invalid'); });
      var el = field && fields[field] ? form.querySelector('[name="' + fields[field] + '"]') : null;
      if (el) { el.classList.add('is-invalid'); el.focus(); }
    }
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      if (!form.checkValidity()) {
        var bad = form.querySelector(':invalid');
        showError(bad && bad.name === 'di_consent' ? 'Necesitamos tu consentimiento para contactarte.' : 'Revisa los campos marcados.', null);
        if (bad) { bad.classList.add('is-invalid'); bad.focus(); }
        return;
      }
      form.classList.add('is-sending');
      form.querySelector('.di-form__label').hidden = true; form.querySelector('.di-form__sending').hidden = false;
      fetch(form.action, { method: 'POST', body: new FormData(form), headers: { 'X-Requested-With': 'fetch', 'Accept': 'application/json' }, credentials: 'same-origin' })
        .then(function (r) { return r.json().then(function (j) { return { ok: r.ok && j && j.ok, j: j }; }); })
        .then(function (res) {
          if (res.ok) {
            if (window.gtag) { window.gtag('event', 'contact_submit', { page_path: location.pathname }); }
            window.location.assign(form.getAttribute('data-thanks') || '/');
            return;
          }
          showError((res.j && res.j.message) || 'No pudimos enviar tu mensaje. Escríbenos por WhatsApp.', res.j && res.j.error);
        })
        .catch(function () { showError('No pudimos enviar tu mensaje. Escríbenos por WhatsApp.', null); })
        .finally(function () { form.classList.remove('is-sending'); form.querySelector('.di-form__label').hidden = false; form.querySelector('.di-form__sending').hidden = true; });
    });
  }

  // Barra móvil: reserva espacio inferior.
  if (document.querySelector('.di-mobile-bar')) { document.body.classList.add('has-mobile-bar'); }
})();
