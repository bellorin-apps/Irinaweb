/* Preview v2: reveal al hacer scroll, header sólido al desplazarse, parallax suave del fondo. Todo opcional: sin JS la página se lee igual. */
(function(){
  var reduce=matchMedia('(prefers-reduced-motion: reduce)').matches;
  var header=document.querySelector('.header');
  var hero=document.querySelector('.bleed');
  var bgs=document.querySelectorAll('.bleed .bg');
  function onScroll(){
    var y=scrollY;
    if(header){
      header.classList.toggle('scrolled',y>40);
      if(hero) header.classList.toggle('on-dark',y<hero.offsetHeight-84);
    }
    if(!reduce) bgs.forEach(function(b){b.style.transform='translateY('+(y*0.25)+'px)';});
  }
  addEventListener('scroll',onScroll,{passive:true}); onScroll();
  var els=document.querySelectorAll('.r');
  if(reduce||!('IntersectionObserver' in window)){els.forEach(function(e){e.classList.add('in')});return;}
  var io=new IntersectionObserver(function(entries){entries.forEach(function(en){if(en.isIntersecting){en.target.classList.add('in');io.unobserve(en.target);}})},{rootMargin:'0px 0px -10% 0px',threshold:.1});
  els.forEach(function(e){io.observe(e)});
})();
