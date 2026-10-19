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

  // Franja de confianza del hero: en móvil se desplaza en horizontal; solo entonces recibe foco para poder recorrerla con el teclado
  // (WCAG 2.1.1, axe scrollable-region-focusable). En escritorio no se desplaza y no añade una parada de tabulación.
  var trust = document.querySelector('.di-hero-trust');
  function trustFocus() {
    if (!trust) { return; }
    if (trust.scrollWidth > trust.clientWidth + 1) { trust.setAttribute('tabindex', '0'); } else { trust.removeAttribute('tabindex'); }
  }
  trustFocus(); window.addEventListener('resize', trustFocus, { passive: true });

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
    function menuFocusables() {
      return [burger].concat(Array.prototype.slice.call(panel.querySelectorAll('a[href],button:not([disabled]),[tabindex="0"]')).filter(function (el) { return el.getClientRects().length > 0; }));
    }
    burger.addEventListener('click', function () {
      var open = !panel.classList.contains('is-open');
      // El panel arranca justo bajo la cabecera, mida lo que mida (móvil 92 px, tableta 112 px, barra de admin).
      if (open) { panel.style.top = Math.round(header.getBoundingClientRect().bottom) + 'px'; }
      panel.classList.toggle('is-open', open);
      header.classList.toggle('is-open', open);
      document.body.classList.toggle('menu-open', open);
      burger.setAttribute('aria-expanded', open ? 'true' : 'false');
      if (open) { var first = menuFocusables()[1]; if (first) { first.focus(); } }
      else { burger.focus(); }
    });
    document.addEventListener('keydown', function (e) {
      if (!panel.classList.contains('is-open')) { return; }
      if (e.key === 'Escape') { e.preventDefault(); burger.click(); }
      if (e.key === 'Tab') {
        var focusable = menuFocusables(), first = focusable[0], last = focusable[focusable.length - 1];
        if (e.shiftKey && (document.activeElement === first || !focusable.includes(document.activeElement))) { e.preventDefault(); last.focus(); }
        else if (!e.shiftKey && (document.activeElement === last || !focusable.includes(document.activeElement))) { e.preventDefault(); first.focus(); }
      }
    });
    window.addEventListener('resize', function () {
      if (!panel.classList.contains('is-open')) { return; }
      if (window.innerWidth >= 1024) { burger.click(); }
      else { panel.style.top = Math.round(header.getBoundingClientRect().bottom) + 'px'; }
    });
    // La cabecera cambia de alto con una transición (móvil 92 px → tableta 112 px) después del evento resize: se sigue su alto real.
    if (window.ResizeObserver) {
      new ResizeObserver(function () {
        if (panel.classList.contains('is-open')) { panel.style.top = Math.round(header.getBoundingClientRect().bottom) + 'px'; }
      }).observe(header);
    }
  }

  // Credenciales de la sección de la Dra.: un ítem a la vez, rotación automática, puntos y anillo, en todos los anchos (propietario, 2026-10-09/10).
  var creds = document.querySelector('.di-section--doctor-bg .di-creds');
  if (creds && creds.children.length > 1) {
    var credItems = Array.prototype.slice.call(creds.children);
    var dots = null, timer = null, idx = 0;
    function show(i, dir) {
      idx = (i + credItems.length) % credItems.length;
      credItems.forEach(function (li, k) {
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
      var h = 0; credItems.forEach(function (li) { h = Math.max(h, li.getBoundingClientRect().height); });
      creds.classList.add('di-creds--carousel'); creds.style.minHeight = h ? h + 'px' : '';
      // No anunciar cambios automáticos cada cuatro segundos al lector de pantalla.
      creds.setAttribute('aria-live', 'off');
      dots = document.createElement('div'); dots.className = 'di-creds__dots';
      credItems.forEach(function (li, k) {
        var b = document.createElement('button'); b.type = 'button';
        b.setAttribute('aria-label', (creds.getAttribute('data-label') || 'Credencial %1$d de %2$d').replace('%1$d', String(k + 1)).replace('%2$d', String(credItems.length)));
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
      credItems.forEach(function (li) {
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
    // Q-047 (OK del propietario, 2026-10-12): el cálculo recorre cada palabra del texto; se hace solo cuando la sección está a
    // menos de 800 px de la pantalla, no durante la carga de la página (bloqueaba ~0,3 s en móvil).
    var doctorNear = !('IntersectionObserver' in window);
    function queueDoctorFit() { if (doctorNear && !doctorFrame) { doctorFrame = window.requestAnimationFrame(fitDoctor); } }
    if (!doctorNear) {
      var doctorSeen = new IntersectionObserver(function (entries) {
        if (entries.some(function (en) { return en.isIntersecting; })) { doctorNear = true; doctorSeen.disconnect(); queueDoctorFit(); }
      }, { rootMargin: '800px 0px' });
      doctorSeen.observe(doctorImage.closest('.di-doctor'));
    }
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
        showError(bad && bad.name === 'di_consent' ? (form.getAttribute('data-error-consent') || 'Necesitamos tu consentimiento para contactarte.') : (form.getAttribute('data-error-fields') || 'Revisa los campos marcados.'), null);
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
          showError((res.j && res.j.message) || form.getAttribute('data-error-send') || 'No pudimos enviar tu mensaje. Escríbenos por WhatsApp.', res.j && res.j.error);
          // Captcha Turnstile: cada token sirve una vez; tras un error se pide uno nuevo.
          if (window.turnstile && form.querySelector('.cf-turnstile')) { try { window.turnstile.reset(form.querySelector('.cf-turnstile')); } catch { /* sin captcha */ } }
        })
        .catch(function () {
          showError(form.getAttribute('data-error-send') || 'No pudimos enviar tu mensaje. Escríbenos por WhatsApp.', null);
          if (window.turnstile && form.querySelector('.cf-turnstile')) { try { window.turnstile.reset(form.querySelector('.cf-turnstile')); } catch { /* sin captcha */ } }
        })
        .finally(function () { form.classList.remove('is-sending'); form.querySelector('.di-form__label').hidden = false; form.querySelector('.di-form__sending').hidden = true; });
    });
  }

  // «Escucharte» con endoscopio (propietario, 2026-10-10): la foto sale de debajo de la tarjeta según el scroll. --endo-p va de 0
  // (sección asomando por abajo) a 1 (sección a media pantalla); el CSS la traduce a desplazamiento. Sin JS o con reduced-motion: 1.
  var endo = document.querySelectorAll('.di-narrative--card');
  if (endo.length && !reduce) {
    var endoTick = false;
    function endoUpdate() {
      endoTick = false;
      var vh = window.innerHeight || 1;
      endo.forEach(function (sec) {
        var r = sec.getBoundingClientRect();
        var p = (vh - r.top) / (vh * 0.55 + r.height * 0.5);
        sec.style.setProperty('--endo-p', Math.max(0, Math.min(1, p)).toFixed(3));
      });
    }
    window.addEventListener('scroll', function () { if (!endoTick) { endoTick = true; requestAnimationFrame(endoUpdate); } }, { passive: true });
    window.addEventListener('resize', endoUpdate);
    endoUpdate();
  }

  // Foto de fondo repartida entre dos secciones seguidas (Primera consulta: «Qué llevar» + «Preguntas frecuentes», 2026-10-13).
  // Sin JS cada sección enseña su tramo (arriba/abajo) con la costura desvanecida. Con JS las dos <img> toman el alto combinado de
  // ambas secciones y la segunda se desplaza hacia arriba lo que mide el tramo de la primera: se ve UNA foto continua, sin repetir.
  var spanTops = document.querySelectorAll('.di-sec-photo--top');
  if (spanTops.length) {
    var pairs = [];
    Array.prototype.forEach.call(spanTops, function (top) {
      var secA = top.closest('.di-section--photo');
      var all = Array.prototype.slice.call(document.querySelectorAll('.di-sec-photo--bottom'));
      var bottom = all.filter(function (b) { return secA && (secA.compareDocumentPosition(b) & Node.DOCUMENT_POSITION_FOLLOWING); })[0];
      if (!secA || !bottom) { return; }
      var secB = bottom.closest('.di-section--photo'), imgA = top.querySelector('img'), imgB = bottom.querySelector('img');
      if (secB && imgA && imgB) { pairs.push([secA, secB, top, bottom, imgA, imgB]); }
    });
    var spanFit = function () {
      pairs.forEach(function (q) {
        var a = q[0].getBoundingClientRect(), b = q[1].getBoundingClientRect();
        var span = Math.round(b.bottom - a.top), off = Math.round(b.top - a.top);
        if (span <= 0 || off <= 0) { return; }
        q[4].style.height = span + 'px'; q[5].style.height = span + 'px'; q[5].style.marginTop = -off + 'px';
        q[2].classList.add('is-spanned'); q[3].classList.add('is-spanned');
      });
    };
    if (pairs.length) {
      spanFit();
      window.addEventListener('resize', spanFit);
      window.addEventListener('load', spanFit);
      if (document.fonts && document.fonts.ready) { document.fonts.ready.then(spanFit); }
      if ('ResizeObserver' in window) { var spanRo = new ResizeObserver(spanFit); pairs.forEach(function (q) { spanRo.observe(q[0]); spanRo.observe(q[1]); }); }
    }
  }

  // Logos: el carril se mueve solo y además se puede arrastrar con ratón, dedo o lápiz (propietario, 2026-10-10).
  // Al primer arrastre la animación CSS se congela en su posición y el movimiento pasa a JS: sigue el puntero, suelta con
  // inercia breve y, tras 3 s sin tocarlo, reanuda el autoplay desde donde quedó a la misma velocidad que la animación.
  // Bucle infinito: el desplazamiento se mantiene en [-ciclo, 0), donde ciclo = distancia entre la primera copia y la segunda.
  // Con prefers-reduced-motion la lista es estática y envuelta (CSS), así que no hay arrastre.
  if (!reduce) {
    Array.prototype.forEach.call(document.querySelectorAll('.di-logos__rail'), function (rail) {
      var track = rail.querySelector('.di-logos__track');
      var items = track ? track.querySelectorAll('.di-logos__item') : [];
      if (!track || items.length < 2 || items.length % 2) { return; }
      var n = items.length / 2, x = 0, cycle = 0, speed = 0, manual = false, dragging = false, hovering = false, focused = false;
      var startX = 0, lastX = 0, lastT = 0, vel = 0, moved = false, raf = 0, prevT = 0, idle = 0, pid = null;
      Array.prototype.forEach.call(track.querySelectorAll('img'), function (img) { img.draggable = false; });
      function measure() {
        cycle = items[n].offsetLeft - items[0].offsetLeft;
        var dur = parseFloat(getComputedStyle(track).animationDuration) || 70; // s; la misma velocidad que la animación CSS.
        speed = cycle / dur;
      }
      function wrap(v) { return cycle ? ((v % cycle) + cycle) % cycle - cycle : v; }
      var ramp = 1;
      function apply() { track.style.transform = 'translate3d(' + x + 'px,0,0)'; }
      function freeze() {
        if (manual) { return; }
        measure();
        var m = getComputedStyle(track).transform, tx = 0, p = m && m !== 'none' ? m.match(/matrix\(([^)]+)\)/) : null;
        if (p) { tx = parseFloat(p[1].split(',')[4]) || 0; }
        x = wrap(tx); track.classList.add('is-manual'); apply(); manual = true;
        prevT = 0; raf = requestAnimationFrame(tick);
      }
      function tick(t) {
        var dt = prevT ? Math.min((t - prevT) / 1000, 0.05) : 0; prevT = t;
        if (!dragging && !focused) {
          // Inercia «suelta» (propietario, 2026-10-12): fricción baja, queda el 40 % de la velocidad tras 1 s (un punto más fluido, 2026-10-12). Si la inercia va en el
          // sentido del autoplay le pasa el relevo a velocidad plena; si va al revés, el autoplay recoge la velocidad que trae (sin salto a
          // cero) y la invierte en continuo: rápido hasta el cero (0,25 s) y suave hasta la velocidad plena (0,8 s).
          if (Math.abs(vel) > speed) { x += vel * dt; vel *= Math.pow(0.4, dt); }
          else { if (vel < 0) { ramp = 1; } else if (vel > 0) { ramp = -vel / speed; } vel = 0; if (idle <= 0 && !hovering) { ramp = Math.min(1, ramp + dt / (ramp < 0 ? 0.25 : 0.8)); x -= speed * ramp * dt; } else { idle -= dt; } }
          x = wrap(x); apply();
        }
        raf = requestAnimationFrame(tick);
      }
      var startY = 0, samples = [];
      rail.addEventListener('pointerdown', function (e) {
        if (dragging || (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches)) { return; }
        if (e.button && e.button !== 0) { return; }
        // Sin congelar todavía: si el dedo va en vertical (scroll de la página) el carril sigue moviéndose solo.
        dragging = true; moved = false; pid = e.pointerId; startX = lastX = e.clientX; startY = e.clientY; lastT = e.timeStamp; vel = 0;
        samples = [{ t: e.timeStamp, x: e.clientX }];
      });
      rail.addEventListener('pointermove', function (e) {
        if (!dragging || e.pointerId !== pid) { return; }
        var dx = e.clientX - lastX;
        if (!moved) {
          var ax = Math.abs(e.clientX - startX), ay = Math.abs(e.clientY - startY);
          if (ax < 6 || ax < ay) { return; } // umbral: un toque, un clic o un gesto vertical no arrastran.
          moved = true; freeze(); rail.classList.add('is-dragging'); dx = e.clientX - lastX;
          try { rail.setPointerCapture(pid); } catch { /* sin captura */ }
        }
        samples.push({ t: e.timeStamp, x: e.clientX });
        while (samples.length > 2 && e.timeStamp - samples[0].t > 70) { samples.shift(); } // ventana de los últimos 70 ms
        lastX = e.clientX; lastT = e.timeStamp; x = wrap(x + dx); apply();
      });
      function release(e) {
        if (!dragging || (e && e.pointerId !== pid)) { return; }
        var wasDrag = moved; dragging = false; pid = null; rail.classList.remove('is-dragging');
        if (!wasDrag) { return; } // toque o scroll vertical: el autoplay no se interrumpe.
        // Velocidad de salida = recorrido de los últimos 70 ms del gesto. Si el dedo se quedó quieto antes de soltar
        // (más de 150 ms sin moverse), no hay inercia.
        var now = e ? e.timeStamp : lastT, first = samples[0], span = (lastT - first.t) / 1000;
        vel = (!e || e.type === 'pointercancel' || now - lastT > 150 || span <= 0) ? 0 : (lastX - first.x) / span;
        if (Math.abs(vel) > 5000) { vel = vel > 0 ? 5000 : -5000; }
        idle = 0; ramp = 0; // sin pausa: tras la inercia, el autoplay arranca desde cero y acelera suave.
      }
      rail.addEventListener('pointerup', release); rail.addEventListener('pointercancel', release);
      rail.addEventListener('lostpointercapture', function () { if (dragging) { release(null); } });
      // Un arrastre no debe contar como clic sobre un logo enlazado.
      rail.addEventListener('click', function (e) { if (moved) { e.preventDefault(); e.stopPropagation(); moved = false; } }, true);
      // En escritorio, al pasar el ratón el carril se detiene (como la animación CSS); con el dedo no aplica.
      rail.addEventListener('pointerenter', function (e) { if (e.pointerType === 'mouse') { hovering = true; } });
      rail.addEventListener('pointerleave', function (e) { if (e.pointerType === 'mouse') { hovering = false; } });
      rail.addEventListener('focusin', function (e) {
        var item = e.target.closest('.di-logos__item');
        if (!item) { return; }
        focused = true; vel = 0; x = 0; apply(); rail.scrollLeft = 0;
        item.scrollIntoView({ block: 'nearest', inline: 'nearest', behavior: 'auto' });
      });
      rail.addEventListener('focusout', function (e) {
        if (rail.contains(e.relatedTarget)) { return; }
        focused = false; rail.scrollLeft = 0;
        if (!manual) { track.style.transform = ''; }
      });
      window.addEventListener('resize', function () { if (manual) { measure(); x = wrap(x); apply(); } });
      var logoMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
      function stopLogoMotion(e) {
        if (!e.matches) { return; }
        cancelAnimationFrame(raf); raf = 0; manual = false; dragging = false; pid = null; vel = 0;
        track.style.transform = ''; track.classList.remove('is-manual'); rail.classList.remove('is-dragging');
      }
      if (logoMotion.addEventListener) { logoMotion.addEventListener('change', stopLogoMotion); }
      else if (logoMotion.addListener) { logoMotion.addListener(stopLogoMotion); }
    });
  }

  // Barra móvil: reserva espacio inferior.
  if (document.querySelector('.di-mobile-bar')) { document.body.classList.add('has-mobile-bar'); }

  // Opiniones (D-012, D-091): sin conexión con Doctoralia hasta una acción explícita.
  var doctoralia = document.querySelector('[data-di-doctoralia]');
  if (doctoralia && doctoralia.querySelector('[data-di-doctoralia-load]')) {
    var doctoraliaButton = doctoralia.querySelector('[data-di-doctoralia-load]');
    var doctoraliaStatus = doctoralia.querySelector('[data-di-doctoralia-status]');
    doctoraliaButton.hidden = false;
    doctoraliaButton.addEventListener('click', function () {
      if (document.getElementById('zl-widget-s') || doctoraliaButton.getAttribute('aria-disabled') === 'true') { return; }
      doctoraliaButton.setAttribute('aria-disabled', 'true');
      doctoraliaStatus.textContent = doctoralia.getAttribute('data-di-status-loading') || 'Cargando opiniones de Doctoralia…';
      var s = document.createElement('script');
      s.id = 'zl-widget-s'; s.async = true; s.src = 'https://platform.docplanner.com/js/widget.js';
      s.addEventListener('load', function () {
        doctoraliaStatus.textContent = doctoralia.getAttribute('data-di-status-loaded') || 'Si las opiniones no aparecen, puedes abrir el enlace de Doctoralia.';
      });
      s.addEventListener('error', function () {
        s.remove();
        doctoraliaButton.removeAttribute('aria-disabled');
        doctoraliaStatus.textContent = doctoralia.getAttribute('data-di-status-error') || 'No se han podido cargar las opiniones. Puedes reintentar o abrir el enlace de Doctoralia.';
      });
      document.body.appendChild(s);
    });
  }
})();
