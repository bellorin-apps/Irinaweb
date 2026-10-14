// Read-only public audit. Never submit a form, load briefing keys or save full HTML.
const {chromium}=require(process.env.IRINA_PLAYWRIGHT||'playwright');
const fs=require('node:fs');
const origin='https://drairinagonzalez.com',out='docs/audit/2026-10-14';
fs.mkdirSync(out,{recursive:true});
(async()=>{
 const browser=await chromium.launch({executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe'});
 const page=await browser.newPage({viewport:{width:393,height:852}});
 await page.route('**/*',r=> /googletagmanager|google-analytics/.test(new URL(r.request().url()).hostname)||r.request().method()==='POST'?r.abort():r.continue());
 const sleep=await page.request.get(origin+'/sueno/');
 const slugs=[...new Set([...((await sleep.text()).matchAll(/href="https:\/\/drairinagonzalez\.com\/sueno\/([^/#?" ]+)\//g))].map(m=>m[1]))];
 const medical=JSON.parse(fs.readFileSync('tools/content/medical-drafts.json','utf8'));
 const drafts=Object.entries(medical).flatMap(([type,items])=>items.map(i=>({type,slug:i.slug,area:i.area}))).filter(i=>!slugs.includes(i.slug)).slice(0,10);
 const routes=['/','/dra-irina-gonzalez-saez/','/primera-consulta/','/contacto/','/preguntas-frecuentes/','/otorrinolaringologia/','/sueno/',...slugs.map(s=>'/sueno/'+s+'/')];
 const rows=[],errors=[];page.on('pageerror',e=>errors.push(e.name));
 for(const route of routes){
  const response=await page.goto(origin+route,{waitUntil:'networkidle'});
  await page.evaluate(()=>document.fonts.ready);await page.waitForTimeout(2100);
  const row={route,status:response.status(),...await page.evaluate(()=>{
   const schemas=[];document.querySelectorAll('script[type="application/ld+json"]').forEach(s=>{try{const walk=o=>{if(!o||typeof o!=='object')return;if(o['@type'])schemas.push(o);Object.values(o).forEach(v=>Array.isArray(v)?v.forEach(walk):walk(v));};walk(JSON.parse(s.textContent));}catch{}});
   return {h1:document.querySelectorAll('h1').length,main:document.querySelectorAll('main').length,draft:/Borrador/.test(document.querySelector('main')?.innerText||''),canonical:document.querySelector('link[rel="canonical"]')?.href,types:schemas.flatMap(s=>s['@type']),breadcrumbs:schemas.filter(s=>s['@type']==='BreadcrumbList').map(s=>s.itemListElement),visibleCrumbs:document.querySelector('.di-crumbs')?.innerText,generator:document.querySelectorAll('meta[name="generator"]').length,headings:[...document.querySelectorAll('main h2')].map(h=>({text:h.textContent,b:!!h.querySelector('b,strong'),em:!!h.querySelector('em')})),sleepLinks:[...new Set([...document.querySelectorAll('main a[href]')].map(a=>a.getAttribute('href')).filter(h=>h.includes('/sueno/')))]};
  })};
  row.widths=[];
  for(const width of [360,393,768,1440]){await page.setViewportSize({width,height:900});await page.waitForTimeout(150);row.widths.push({width,...await page.evaluate(()=>({overflow:document.documentElement.scrollWidth>innerWidth,hero:[...document.querySelectorAll('.di-bleed h1,.di-bleed .di-lead')].map(e=>({opacity:getComputedStyle(e).opacity,transform:getComputedStyle(e).transform,animation:getComputedStyle(e).animationName}))}))});}
  if(route==='/otorrinolaringologia/'||route==='/sueno/'){
   row.anchors=[];await page.setViewportSize({width:393,height:852});
   for(const id of route==='/sueno/'?['atiendo','ruta']:['oido','nariz','garganta','procedimientos']){
    const a=page.locator('#'+id);if(!await a.count()){row.anchors.push({id,missing:true});continue;}
    await a.evaluate(e=>e.scrollIntoView());await page.waitForTimeout(650);
    row.anchors.push({id,...await a.evaluate(e=>({top:e.getBoundingClientRect().top,headerBottom:document.querySelector('.di-header').getBoundingClientRect().bottom}))});
   }
  }
  if(route==='/contacto/')row.contact=await page.evaluate(()=>({text:document.querySelector('main').innerText,form:!!document.querySelector('#di-form'),captcha:!!document.querySelector('.cf-turnstile'),maps:!!document.querySelector('[data-di-map],#di-map,.di-map'),whatsapp:!!document.querySelector('main a[href*="wa.me"]'),phone:!!document.querySelector('main a[href^="tel:"]')}));
  rows.push(row);
 }
 const redirects=[];
 for(const slug of slugs)for(const type of ['padecimientos','tratamientos']){const r=await page.request.get(origin+'/'+type+'/'+slug+'/',{maxRedirects:0});redirects.push({slug,type,status:r.status(),location:r.headers().location});}
 const hidden=[];for(const d of drafts){const route='/'+(d.type==='condicion'?'padecimientos':d.type==='tratamiento'?'tratamientos':'recursos')+'/'+d.slug+'/';const r=await page.request.get(origin+route,{maxRedirects:0});hidden.push({route,status:r.status()});}
 const security=[];for(const route of ['/wp-json/wp/v2/users','/wp-json/wp/v2/users/1','/?author=1','/readme.html','/license.txt','/wp-config-sample.php','/wp-login.php','/wp-json/','/sitemap_index.xml']){const r=await page.request.get(origin+route,{maxRedirects:0});security.push({route,status:r.status(),redirect:!!r.headers().location,poweredBy:!!r.headers()['x-powered-by']});}
 const sitemap=[];const index=await(await page.request.get(origin+'/sitemap_index.xml')).text();for(const m of index.matchAll(/<loc>([^<]+)<\/loc>/g)){const u=m[1];if(new URL(u).origin!==origin)continue;const r=await page.request.get(u);const xml=await r.text();sitemap.push({path:new URL(u).pathname,status:r.status(),locations:[...xml.matchAll(/<loc>([^<]+)<\/loc>/g)].map(m=>m[1])});}
 const menus=[];for(const motion of ['no-preference','reduce']){
  await page.emulateMedia({reducedMotion:motion});await page.setViewportSize({width:393,height:852});await page.goto(origin+'/sueno/',{waitUntil:'networkidle'});
  await page.locator('.di-burger').click();await page.waitForTimeout(700);
  const state=await page.evaluate(()=>({active:document.activeElement.className,expanded:document.querySelector('.di-burger').getAttribute('aria-expanded'),top:document.querySelector('#di-menu-panel').getBoundingClientRect().top,headerBottom:document.querySelector('.di-header').getBoundingClientRect().bottom,barVisibility:getComputedStyle(document.querySelector('.di-mobile-bar')).visibility,links:[...document.querySelectorAll('#di-menu-panel a')].map(a=>a.textContent.trim())}));
  await page.locator('#di-menu-panel a').last().focus();await page.keyboard.press('Tab');state.tabAfterLast=await page.evaluate(()=>({inside:!!document.activeElement.closest('#di-menu-panel,.di-header'),tag:document.activeElement.tagName}));
  await page.keyboard.press('Escape');state.escape=await page.locator('.di-burger').evaluate(e=>({focused:document.activeElement===e,expanded:e.getAttribute('aria-expanded')}));menus.push({motion,...state});
 }
 await browser.close();fs.writeFileSync(out+'/public.json',JSON.stringify({slugs,rows,redirects,hidden,security,sitemap,menus,errors},null,2)+'\n');console.log(JSON.stringify({slugs,pages:rows.length,hidden,security,menus,errors}));
})().catch(e=>{console.error(e.name,e.message);process.exit(1)});
