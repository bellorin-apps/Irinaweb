// Revisión pública: no POST ni formulario; GA bloqueado para evitar enviar medición de prueba.
const {chromium}=require(process.env.IRINA_PLAYWRIGHT||'playwright');
const sharp=require(process.env.IRINA_SHARP||'sharp');
const fs=require('node:fs');
const out='docs/audit/2026-10-13';
(async()=>{
 const browser=await chromium.launch({executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe'});
 const rows=[];const routes=['/','/dra-irina-gonzalez-saez/','/primera-consulta/','/contacto/','/preguntas-frecuentes/','/links/','/gracias/','/aviso-de-privacidad/','/aviso-medico/','/terminos-de-uso/','/recursos/'];
 const uaAndroid='Mozilla/5.0 (Linux; Android 13; SM-S901B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36';
 for(const route of routes){
  const p=await browser.newPage({viewport:{width:1440,height:900}});const errors=[];p.on('pageerror',e=>errors.push(e.name));
  await p.addInitScript(()=>{window.gaAppendStates=[];const append=Element.prototype.appendChild;Element.prototype.appendChild=function(child){if(child.tagName==='SCRIPT'&&child.src.includes('googletagmanager.com/gtag/js'))window.gaAppendStates.push(document.readyState);return append.call(this,child)};});
  await p.route('**/*',r=>/google-analytics\.com|googletagmanager\.com/.test(new URL(r.request().url()).hostname)?r.abort():r.continue());
  const response=await p.goto('https://drairinagonzalez.com'+route+'?review=five20261013',{waitUntil:'networkidle'});await p.evaluate(()=>document.fonts.ready);
  const html=await response.text();
  const meta=await p.evaluate(()=>({title:document.title,h1:document.querySelectorAll('h1').length,main:document.querySelectorAll('main').length,draft:/Borrador|PENDIENTE DE CONFIRMACIÓN/.test(document.querySelector('main')?.innerText||''),crumb:document.querySelector('.di-crumbs-bar')?.innerText,headings:Array.from(document.querySelectorAll('main h1, main h2')).map(h=>({text:h.innerText,bold:h.querySelectorAll('b').length,italic:h.querySelectorAll('em').length})),schemas:Array.from(document.querySelectorAll('script[type="application/ld+json"]')).flatMap(s=>{try{const j=JSON.parse(s.textContent);return(j['@graph']||[j]).map(n=>n['@type'])}catch{return ['INVALID']}}),og:document.querySelector('meta[property="og:image"]')?.content,twitter:document.querySelector('meta[name="twitter:image"]')?.content,icons:Array.from(document.querySelectorAll('link[rel="icon"],link[rel="apple-touch-icon"],link[rel="manifest"]')).map(l=>({rel:l.rel,href:l.href,sizes:l.sizes.value})),gaScriptCount:document.querySelectorAll('#di-ga4').length,gaProviderCount:Array.from(document.scripts).filter(s=>s.src.includes('googletagmanager.com/gtag/js')).length,gaIds:typeof dataLayer!=='undefined'?dataLayer.filter(a=>a[0]==='config').map(a=>a[1]):[]}));
  const social=[];for(const src of new Set([meta.og,meta.twitter].filter(Boolean))){const r=await p.request.get(src);const data=await r.body();const m=await sharp(data).metadata();social.push({url:new URL(src).pathname,status:r.status(),format:m.format,width:m.width,height:m.height,bytes:data.length});}
  const icons=[];for(const icon of meta.icons){const r=await p.request.get(icon.href);icons.push({rel:icon.rel,path:new URL(icon.href).pathname,status:r.status(),type:r.headers()['content-type']});}
  const widths=[];for(const width of [360,393,430,768,1440]){await p.setViewportSize({width,height:900});await p.evaluate(()=>document.fonts.ready);widths.push({width,overflow:await p.evaluate(()=>document.documentElement.scrollWidth>innerWidth)});}
  let logos;if(route==='/dra-irina-gonzalez-saez/'){
   logos=await p.locator('.di-logos__item').evaluateAll(es=>es.map(e=>({alt:e.querySelector('img')?.alt,tip:e.getAttribute('data-tip'),hidden:e.getAttribute('aria-hidden'),tabIndex:e.tabIndex,tag:e.tagName})));
   const first=p.locator('.di-logos__item:not([aria-hidden])').first();await first.focus();await p.waitForTimeout(300);
   logos.keyboard=await first.evaluate(e=>({focused:document.activeElement===e,tipOpacity:getComputedStyle(e,'::after').opacity}));
  }
  let span;if(route==='/primera-consulta/'){
   const measure=()=>p.locator('.di-sec-photo').evaluateAll(es=>es.map(e=>({top:e.querySelector('img').getBoundingClientRect().top,height:e.querySelector('img').getBoundingClientRect().height,spanned:e.classList.contains('is-spanned'),opacity:getComputedStyle(e).opacity,blend:getComputedStyle(e).mixBlendMode})));
   span={before:await measure(),steps:await p.locator('.di-step img').count()};await p.locator('details').first().evaluate(e=>{e.open=true});await p.waitForTimeout(400);span.after=await measure();
  }
  const duplicates=(html.match(/<img\b[^>]*>/gi)||[]).filter(t=>['fetchpriority','loading'].some(a=>(t.match(new RegExp('\\b'+a+'=','g'))||[]).length>1)).length;
  rows.push({route,status:response.status(),...meta,social,iconResponses:icons,widths,logos,logoKeyboard:logos?.keyboard,span,duplicates,errors,gaAppendStates:await p.evaluate(()=>window.gaAppendStates)});await p.close();
 }
 const images=[],layouts=[];
 for(const [name,width,ua,mobile] of [['Galaxy',360,uaAndroid,true],['iPhone',393,'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1',true],['Desktop',1440,undefined,false]]){
  const p=await browser.newPage({viewport:{width,height:900},isMobile:mobile,hasTouch:mobile,deviceScaleFactor:mobile?2:1,...(ua?{userAgent:ua}:{})});
  await p.route('**/*',r=>/google-analytics\.com|googletagmanager\.com/.test(new URL(r.request().url()).hostname)?r.abort():r.continue());
  for(const route of ['/','/dra-irina-gonzalez-saez/','/primera-consulta/','/contacto/']){
   await p.goto('https://drairinagonzalez.com'+route+'?review=images20261013',{waitUntil:'networkidle'});await p.evaluate(()=>document.fonts.ready);
   await p.evaluate(async()=>{for(let y=0;y<document.documentElement.scrollHeight;y+=600){window.scrollTo(0,y);await new Promise(r=>setTimeout(r,60));}});await p.waitForTimeout(500);
   layouts.push({name,width,route,...await p.evaluate(()=>({overflow:document.documentElement.scrollWidth>innerWidth,bodyImages:Array.from(document.querySelectorAll('.di-narrative__aside img,.di-step img')).filter(i=>!i.complete||i.naturalWidth===0).length,steps:document.querySelectorAll('.di-step img').length}))});
   const list=await p.locator('.di-bleed__bg img,.di-doctor__bg img,.di-area--photo img,.di-doctor__portrait img,.di-narrative__aside img,.di-sec-photo img').evaluateAll(es=>es.map(i=>{const timing=performance.getEntriesByName(i.currentSrc).find(e=>e.entryType==='resource');return {src:i.currentSrc,selector:i.parentElement.className,cssWidth:i.getBoundingClientRect().width,cssHeight:i.getBoundingClientRect().height,naturalWidth:i.naturalWidth,naturalHeight:i.naturalHeight,loading:i.loading,priority:i.getAttribute('fetchpriority'),transferSize:timing?.transferSize,encodedBodySize:timing?.encodedBodySize}}));
   for(const img of list){const r=await p.request.get(img.src);const buffer=await r.body();const m=await sharp(buffer).metadata();images.push({name,width,route,...img,src:new URL(img.src).pathname,versionM:new URL(img.src).searchParams.has('m'),bytes:buffer.length,format:m.format,pixelWidth:m.width,pixelHeight:m.height,alpha:m.hasAlpha,losslessWebp:buffer.includes(Buffer.from('VP8L'))});}
  }await p.close();
 }
 await browser.close();fs.writeFileSync(out+'/five-public.json',JSON.stringify({rows,images,layouts},null,2)+'\n');console.log(JSON.stringify({pages:rows.map(r=>({route:r.route,status:r.status,draft:r.draft,schemas:r.schemas,manifest:r.icons.some(i=>i.rel==='manifest'),duplicates:r.duplicates,errors:r.errors})),images:images.map(i=>({name:i.name,route:i.route,path:i.src,width:i.pixelWidth,height:i.pixelHeight,kb:Math.round(i.bytes/1024),m:i.versionM,alpha:i.alpha,lossless:i.losslessWebp}))}));
})().catch(e=>{console.error(e.name,e.message);process.exit(1)});
