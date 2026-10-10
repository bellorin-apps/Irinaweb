// Recorrido público sin sesión y sin POST. No persiste HTML ni URLs de scripts con claves.
const {chromium}=require(process.env.IRINA_PLAYWRIGHT||'playwright');
const fs=require('node:fs');
const root='docs/audit/2026-10-10';
const routes=['/','/dra-irina-gonzalez-saez/','/primera-consulta/','/preguntas-frecuentes/','/contacto/','/gracias/','/aviso-de-privacidad/','/aviso-medico/','/terminos-de-uso/','/links/','/auditoria-404-no-existe/'];
(async()=>{
 const browser=await chromium.launch({executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe',headless:true});
 const rows=[];
 for(const route of routes){
  const page=await browser.newPage();const errors=[];page.on('pageerror',e=>errors.push(e.name));
  const response=await page.goto('https://drairinagonzalez.com'+route,{waitUntil:'networkidle'});
  const html=await response.text();
  const duplicateHero=(html.match(/<img\b[^>]*fetchpriority[^>]*>/gi)||[]).some(tag=>(tag.match(/\bfetchpriority=/g)||[]).length>1||(tag.match(/\bloading=/g)||[]).length>1);
  const summary=await page.evaluate(()=>({title:document.title,h1:document.querySelectorAll('h1').length,main:document.querySelectorAll('main').length,canonical:document.querySelector('link[rel=canonical]')?.href,robots:document.querySelector('meta[name=robots]')?.content,unlabelledImages:document.querySelectorAll('img:not([alt])').length,markers:document.body.innerText.includes('[PENDIENTE'),draftLinks:Array.from(document.querySelectorAll('a')).filter(a=>a.href.includes('?page_id=')).length,schema:Array.from(document.querySelectorAll('script[type="application/ld+json"]')).flatMap(e=>{try{const j=JSON.parse(e.textContent);return(j['@graph']||[j]).map(n=>n['@type'])}catch{return ['INVALID']}})}));
  const sizes=[];
  await page.addStyleTag({content:'html{scroll-behavior:auto}.js .di-reveal{transition:none;transition-delay:0s}'});
  for(const width of [360,390,430,768,1024,1440]){
   await page.setViewportSize({width,height:width===390?844:width===430?932:width===768?1024:width===1024?768:900});
   await page.evaluate(()=>document.fonts.ready);await page.waitForTimeout(160);
   sizes.push(await page.evaluate(()=>({width:innerWidth,overflow:document.documentElement.scrollWidth>innerWidth,overflowElements:Array.from(document.querySelectorAll('main *')).filter(e=>{const r=e.getBoundingClientRect(),s=getComputedStyle(e);return r.width&&r.right>innerWidth+1&&s.position!=='absolute'&&!e.closest('.di-ticker,.di-doctor__portrait')}).slice(0,8).map(e=>e.className)})));
   {
    await page.evaluate(async()=>{document.querySelectorAll('.di-reveal').forEach(e=>e.classList.add('is-visible'));for(let y=0;y<document.documentElement.scrollHeight;y+=innerHeight){window.scrollTo(0,y);await new Promise(r=>setTimeout(r,60))}window.scrollTo(0,0);await Promise.race([Promise.all(Array.from(document.images).map(i=>i.decode().catch(()=>{}))),new Promise(r=>setTimeout(r,3000))])});
    await page.screenshot({path:`${root}/page-${route==='/'?'home':route.split('/')[1]}-${width}.png`,fullPage:true});
   }
  }
  const headers=response.headers();rows.push({route,status:response.status(),...summary,duplicateHero,headers:Object.fromEntries(['strict-transport-security','x-content-type-options','x-frame-options','referrer-policy','permissions-policy'].map(k=>[k,headers[k]||null])),sizes,errors});
  await page.close();console.log(route,response.status(),summary.h1,summary.main);
 }
 const p=await browser.newPage();
 const rest=await p.request.get('https://drairinagonzalez.com/wp-json/dra-irina/v1/practice');const data=await rest.json();
 const endpoints={practiceStatus:rest.status(),mapsKeyExposed:Object.hasOwn(data,'maps_api_key'),hiddenHoursExposed:!!data.horario_oculto&&!!data.horario};
 for(const route of ['/robots.txt','/sitemap_index.xml','/page-sitemap.xml','/wp-json/dra-irina/v1/ops/info','/briefing/config.php','/briefing/revision/revisar.php']){const r=await p.request.get('https://drairinagonzalez.com'+route);endpoints[route]=r.status();if(route.includes('sitemap')){const t=await r.text();endpoints[route+'-excluded']=!t.includes('/gracias/')&&!t.includes('/links/')}}
 fs.writeFileSync(`${root}/public.json`,JSON.stringify({rows,endpoints},null,2)+'\n');
 await browser.close();
})().catch(e=>{console.error(e.message.replace(/key=[^&\s]+/g,'key=[redacted]'));process.exit(1)});
