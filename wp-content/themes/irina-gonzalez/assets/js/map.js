/* Mapa del consultorio con estilo propio (D-048): Maps JavaScript API + JSON de estilo (Snazzy Maps o paleta). */
(function () {
  'use strict';
  window.diInitMap = function () {
    var el = document.getElementById('di-map');
    if (!el || !window.google || !google.maps || el.querySelector('.gm-style')) { return; }
    var pos = { lat: parseFloat(el.dataset.lat), lng: parseFloat(el.dataset.lng) };
    var mapId = typeof window.diMapId === 'string' ? window.diMapId : '';
    var opts = {
      center: pos,
      zoom: parseInt(el.dataset.zoom || '16', 10),
      disableDefaultUI: true,
      zoomControl: true,
      gestureHandling: 'cooperative',
      clickableIcons: false,
      backgroundColor: '#fbf7f2'
    };
    // Con Map ID (D-059) el estilo lo gestiona Google Cloud y `styles` se ignora; sin él, el JSON de la paleta o de Snazzy Maps.
    if (mapId) { opts.mapId = mapId; } else { opts.styles = Array.isArray(window.diMapStyle) ? window.diMapStyle : []; }
    var map = new google.maps.Map(el, opts);
    var open = function () { if (el.dataset.url) { window.open(el.dataset.url, '_blank', 'noopener'); } };
    if (mapId && google.maps.marker && google.maps.marker.AdvancedMarkerElement) {
      // Marcador avanzado con el pin de la marca como contenido (sin aviso de obsolescencia).
      var pin = document.createElement('img');
      pin.src = window.diMapMarker || ''; pin.width = 48; pin.height = 60; pin.alt = ''; pin.style.display = 'block'; pin.style.transform = 'translateY(2px)';
      var adv = new google.maps.marker.AdvancedMarkerElement({ map: map, position: pos, title: el.dataset.title || '', content: window.diMapMarker ? pin : undefined });
      adv.addEventListener('gmp-click', open);
    } else {
      var marker = new google.maps.Marker({
        position: pos,
        map: map,
        title: el.dataset.title || '',
        icon: window.diMapMarker ? { url: window.diMapMarker, scaledSize: new google.maps.Size(48, 60), anchor: new google.maps.Point(24, 58) } : undefined
      });
      marker.addListener('click', open);
    }
  };
  // Guarda: si la API ya cargó antes que este script (orden de carga), inicializa ahora.
  if (window.google && window.google.maps && document.getElementById('di-map') && !document.querySelector('#di-map .gm-style')) {
    window.diInitMap();
  }
})();
