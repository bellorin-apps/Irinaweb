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
      // Sobre una cabecera clara (variante light) la cabecera nunca va en blanco.
      if (bleed) { header.classList.toggle('di-on-dark', !bleed.classList.contains('di-bleed--light') && y < bleed.offsetHeight - 84); }
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
    creds.addEventListener('touchstart', function (e) { pauseThenResume(); sx = e.touches[0].clientX; sy = e.touches[0].clientY; swiping = true; }, { passive: true });
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
      // No anunciar cambios automáticos cada cuatro segundos al lector de pantalla.
      creds.setAttribute('aria-live', 'off');
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
    function measureCards() {
      var h = 0;
      items.forEach(function (li) {
        var old = li.getAttribute('style');
        li.style.display = 'flex'; li.style.position = 'absolute'; li.style.visibility = 'hidden';
        h = Math.max(h, li.getBoundingClientRect().height);
        if (old === null) { li.removeAttribute('style'); } else { li.setAttribute('style', old); }
      });
      creds.style.minHeight = h ? h + 'px' : '';
    }
    window.addEventListener('resize', measureCards);
    if (document.fonts) { document.fonts.ready.then(measureCards); }
    creds.addEventListener('focusin', stop);
    if (dots) { dots.addEventListener('focusin', stop); }
  }

  // Encaje móvil del PNG actual: borde alfa por bandas, no borde transparente del archivo.
  // Perfil conservador (alfa >16, mínimo de cada banda) del retrato 1024×2170.
  // Otra proporción conserva el posicionamiento CSS y exige revisar el perfil.
  var doctorImage = document.querySelector('.di-section--doctor-bg .di-doctor__portrait img');
  if (doctorImage) {
    var doctorEdge = [0.405,0.37,0.347,0.333,0.32,0.306,0.296,0.287,0.283,0.277,0.269,0.262,0.252,0.244,0.238,0.233,0.229,0.225,0.221,0.219,0.215,0.215,0.209,0.204,0.198,0.192,0.182,0.169,0.159,0.151,0.149,0.146,0.144,0.151,0.151,0.12,0.105,0.093,0.084,0.076,0.07,0.064,0.053,0.037,0.022,0.004,0,0,0,0.004,0.01,0.016,0.024,0.031,0.037,0.035,0.033,0.033,0.031,0.031,0.033,0.031,0.031,0.033,0.033,0.033,0.035,0.035,0.037,0.039,0.041,0.045,0.049,0.06,0.078,0.097,0.093,0.093,0.091,0.089,0.086,0.084,0.08,0.08,0.078,0.076,0.078,0.086,0.093,0.165,0.165,0.159,0.136,0.13,0.128,0.109,0.095,0.084,0.078,0.074,0.074,0.076,0.08,0.084,0.091,0.091,0.086,0.082,0.076,0.072,0.066,0.062,0.055,0.049,0.045,0.043,0.041,0.033,0.024,0.02,0.018,0.016,0.014,0.004,0.002,0.008,0.002,0];
    var doctorFrame = 0;
    function fitDoctor() {
      doctorFrame = 0;
      if (window.innerWidth > 430 || !doctorImage.naturalWidth || Math.abs(doctorImage.naturalWidth / doctorImage.naturalHeight - 1024 / 2170) > 0.01) { doctorImage.style.left = ''; return; }
      var imageBox = doctorImage.getBoundingClientRect();
      if (!imageBox.height) { return; }
      var baseLeft = imageBox.left - (parseFloat(doctorImage.style.left) || 0);
      var requiredLeft = -Infinity;
      var text = doctorImage.closest('.di-doctor').querySelector('.di-doctor__text');
      // Tarjetas y botón tienen fondo propio y quedan por encima del retrato.
      text.querySelectorAll('.di-eyebrow, .di-quote, .di-doctor__lead').forEach(function (block) {
        var walker = document.createTreeWalker(block, NodeFilter.SHOW_TEXT);
        while (walker.nextNode()) {
          var node = walker.currentNode;
          var matches = node.textContent.matchAll(/\S+/g);
          for (var match of matches) {
            var range = document.createRange(); range.setStart(node, match.index); range.setEnd(node, match.index + match[0].length);
            Array.from(range.getClientRects()).forEach(function (rect) {
              var first = Math.max(0, Math.floor((rect.top - imageBox.top) / imageBox.height * 128));
              var last = Math.min(127, Math.floor((rect.bottom - imageBox.top) / imageBox.height * 128));
              for (var band = first; band <= last; band++) {
                requiredLeft = Math.max(requiredLeft, rect.right + 4 - doctorEdge[band] * imageBox.width);
              }
            });
          }
        }
      });
      if (Number.isFinite(requiredLeft)) { doctorImage.style.left = Math.ceil(requiredLeft - baseLeft) + 'px'; }
    }
    function queueDoctorFit() { if (!doctorFrame) { doctorFrame = window.requestAnimationFrame(fitDoctor); } }
    doctorImage.addEventListener('load', queueDoctorFit);
    doctorImage.closest('.di-doctor').addEventListener('transitionend', queueDoctorFit);
    window.addEventListener('resize', queueDoctorFit);
    if (document.fonts) { document.fonts.ready.then(queueDoctorFit); }
    if (window.ResizeObserver) {
      var doctorResize = new ResizeObserver(queueDoctorFit);
      doctorResize.observe(doctorImage.closest('.di-doctor'));
      doctorResize.observe(doctorImage);
    }
    queueDoctorFit();
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
      form.querySelectorAll('.is-invalid').forEach(function (el) { el.classList.remove('is-invalid'); el.removeAttribute('aria-invalid'); });
      var el = field && fields[field] ? form.querySelector('[name="' + fields[field] + '"]') : null;
      if (el) { el.classList.add('is-invalid'); el.setAttribute('aria-invalid', 'true'); el.focus(); }
    }
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      if (form.classList.contains('is-sending')) { return; }
      if (!form.checkValidity()) {
        var bad = form.querySelector(':invalid');
        showError(bad && bad.name === 'di_consent' ? 'Necesitamos tu consentimiento para contactarte.' : 'Revisa los campos marcados.', null);
        if (bad) { bad.classList.add('is-invalid'); bad.setAttribute('aria-invalid', 'true'); bad.focus(); }
        return;
      }
      form.classList.add('is-sending');
      form.querySelector('.di-form__label').hidden = true; form.querySelector('.di-form__sending').hidden = false;
      // getAttribute: el campo oculto name="action" (exigido por admin-post) sombrea la propiedad form.action.
      fetch(form.getAttribute('action'), { method: 'POST', body: new FormData(form), headers: { 'X-Requested-With': 'fetch', 'Accept': 'application/json' }, credentials: 'same-origin' })
        .then(function (r) { return r.json().then(function (j) { return { ok: r.ok && j && j.ok, j: j }; }); })
        .then(function (res) {
          if (res.ok) {
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
