/* Mapa del consultorio con estilo propio (D-048): Maps JavaScript API + JSON de estilo (Snazzy Maps o paleta). */
(function () {
  'use strict';
  window.diInitMap = function () {
    var el = document.getElementById('di-map');
    if (!el || !window.google || !google.maps) { return; }
    var pos = { lat: parseFloat(el.dataset.lat), lng: parseFloat(el.dataset.lng) };
    var map = new google.maps.Map(el, {
      center: pos,
      zoom: parseInt(el.dataset.zoom || '16', 10),
      styles: Array.isArray(window.diMapStyle) ? window.diMapStyle : [],
      disableDefaultUI: true,
      zoomControl: true,
      gestureHandling: 'cooperative',
      clickableIcons: false,
      backgroundColor: '#fbf7f2'
    });
    var marker = new google.maps.Marker({
      position: pos,
      map: map,
      title: el.dataset.title || '',
      icon: window.diMapMarker ? { url: window.diMapMarker, scaledSize: new google.maps.Size(48, 60), anchor: new google.maps.Point(24, 58) } : undefined
    });
    if (el.dataset.url) {
      marker.addListener('click', function () { window.open(el.dataset.url, '_blank', 'noopener'); });
    }
  };
})();
