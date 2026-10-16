// Public GET/browser audit only. No form submissions, Google Business or Doctoralia navigation, secrets or full HTML stored.
const {chromium}=require(process.env.IRINA_PLAYWRIGHT||'playwright');
const sharp=require(process.env.IRINA_SHARP||'sharp');
const fs=require('node:fs');
const base='https://drairinagonzalez.com',out='docs/audit/2026-10-16';fs.mkdirSync(out,{recursive:true});
const routes=['/','/dra-irina-gonzalez-saez/','/otorrinolaringologia/','/sueno/','/primera-consulta/','/contacto/','/preguntas-frecuentes/','/aviso-de-privacidad/','/aviso-medico/','/terminos-de-uso/','/sueno/ronquido/','/sueno/apnea-obstructiva-del-sueno/','/sueno/estudio-del-sueno/','/sueno/cpap/','/sueno/cirugia-de-ronquido-y-apnea/','/no-existe-audit16/'];
(async()=>{
 const b=await chromium.launch({executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe'});
 const p=await b.newPage({viewport:{width:1440,height:900}});const errors=[],resources=[],rows=[],tiles={390:[],1440:[]};
 await p.route('**/*',r=>r.request().method()!=='GET'||/googletagmanager|google-analytics|doctoralia/.test(new URL(r.request().url()).hostname)?r.abort():r.continue());
 p.on('pageerror',e=>errors.push({route:new URL(p.url()).pathname,type:e.name}));
 p.on('response',r=>{const u=new URL(r.url());if(u.origin===base&&r.status()>=400&&!r.request().isNavigationRequest())resources.push({route:new URL(p.url()).pathname,path:u.pathname,status:r.status()});});
 for(const [i,route] of routes.entries()){
  const r=await p.goto(base+route,{waitUntil:'networkidle'});await p.evaluate(()=>document.fonts.ready);
  const row={route,status:r.status(),headers:Object.fromEntries(Object.entries(r.headers()).filter(([k])=>['strict-transport-security','x-content-type-options','x-frame-options','referrer-policy','permissions-policy','x-powered-by','x-litespeed-cache'].includes(k))),...await p.evaluate(()=>{
   const nodes=[];document.querySelectorAll('script[type="application/ld+json"]').forEach(s=>{try{const walk=o=>{if(!o||typeof o!=='object')return;if(o['@type'])nodes.push(o);Object.values(o).forEach(v=>Array.isArray(v)?v.forEach(walk):walk(v));};walk(JSON.parse(s.textContent));}catch{}});
   const links=[...document.querySelectorAll('main a[href],footer a[href],.di-mobile-bar a[href]')].map(a=>a.href);
   const professional=nodes.filter(n=>[].concat(n['@type']).includes('Physician'));
   return {h1:document.querySelectorAll('h1').length,main:document.querySelectorAll('main').length,title:document.title,canonical:document.querySelector('link[rel="canonical"]')?.href,robots:document.querySelector('meta[name="robots"]')?.content,internal:[...new Set(links.filter(h=>new URL(h).origin===location.origin).map(h=>new URL(h).pathname))],whatsapp:[...new Set(links.filter(h=>h.includes('wa.me/')).map(h=>new URL(h).pathname))],phone:[...new Set(links.filter(h=>h.startsWith('tel:')))],maps:[...new Set(links.filter(h=>h.includes('maps.app.goo.gl')))],professional:professional.map(n=>({name:n.name,address:n.address,telephone:n.telephone,url:n.url,types:n['@type'],openingHours:!!(n.openingHours||n.openingHoursSpecification)})),faqOldCopy:/cada p[aá]gina de padecimiento/i.test(document.querySelector('main')?.innerText||''),draft:/Borrador|PENDIENTE DE CONFIRMACIÓN/.test(document.querySelector('main')?.innerText||''),form:!!document.querySelector('form.di-form'),appointmentOnly:/previa cita/i.test(document.body.innerText),addressVisible:/Paseo de los Leones 2341/.test(document.body.innerText),hourPattern:/mar(?:tes)?\s*(?:a|–|-)\s*vie|10:00.*12:30/i.test(document.querySelector('main')?.innerText||''),h2:[...document.querySelectorAll('main h2')].map(h=>({text:h.textContent.trim(),bold:!!h.querySelector('b,strong'),em:!!h.querySelector('em')}))};
  })};
  row.views=[];
  for(const width of [360,390,768,1024,1440]){
   await p.setViewportSize({width,height:900});await p.evaluate(async()=>{for(let y=0;y<document.documentElement.scrollHeight;y+=700){scrollTo(0,y);await new Promise(r=>setTimeout(r,45));}});await p.waitForTimeout(350);
   row.views.push({width,...await p.evaluate(()=>({overflow:Math.max(0,document.documentElement.scrollWidth-innerWidth),imageCandidates:[...document.querySelectorAll('main img,footer img')].filter(i=>i.offsetWidth&&(!i.complete||!i.naturalWidth)).map(i=>new URL(i.currentSrc||i.src).pathname)}))});
   if(width===390||width===1440){
    const pieces=[];for(const y of [0,await p.evaluate(()=>Math.max(0,(document.documentElement.scrollHeight-innerHeight)/2)),await p.evaluate(()=>document.documentElement.scrollHeight-innerHeight)]){await p.evaluate(y=>scrollTo(0,y),y);await p.waitForTimeout(150);pieces.push(await sharp(await p.screenshot()).resize(240,240,{fit:'fill'}).png().toBuffer());}
    const label=Buffer.from(`<svg width="240" height="32"><rect width="240" height="32" fill="white"/><text x="5" y="14" font-size="10">${i+1}. ${route}</text><text x="5" y="27" font-size="10">${width}px: top / middle / bottom</text></svg>`);
    tiles[width].push(await sharp({create:{width:240,height:752,channels:3,background:'white'}}).composite([{input:label,left:0,top:0},...pieces.map((input,k)=>({input,left:0,top:32+k*240}))]).png().toBuffer());
   }
  }
  rows.push(row);
 }
 const destinations=[...new Set(rows.flatMap(r=>r.internal))].filter(r=>r!== '/no-existe-audit16/');const links=[];
 for(const route of destinations){if(/briefing|doctoralia|\/op\//.test(route))continue;const r=await p.request.get(base+route,{maxRedirects:0});links.push({route,status:r.status(),location:r.headers().location});}
 const exclusions=[];for(const route of ['/links/','/gracias/','/preview/','/briefing/','/briefing/guia/','/briefing/revision/']){
  const r=await p.request.get(base+route);const text=await r.text();exclusions.push({route,status:r.status(),headerRobots:r.headers()['x-robots-tag'],metaRobots:(text.match(/<meta[^>]+name=["']robots["'][^>]+content=["']([^"']+)/i)||[])[1]});
 }
 const motion=[];for(const pref of ['no-preference','reduce']){await p.emulateMedia({reducedMotion:pref});for(const route of ['/','/sueno/']){await p.goto(base+route,{waitUntil:'networkidle'});await p.waitForTimeout(2200);motion.push({pref,route,...await p.evaluate(()=>({hero:[...document.querySelectorAll('.di-bleed h1,.di-bleed .di-lead,.di-bleed__bg img')].map(e=>({opacity:getComputedStyle(e).opacity,transform:getComputedStyle(e).transform,name:getComputedStyle(e).animationName})),transition:getComputedStyle(document.documentElement,'::view-transition-new(root)').animationName,headerName:getComputedStyle(document.querySelector('.di-header')).viewTransitionName}))});}}
 const robots=await(await p.request.get(base+'/robots.txt')).text();const index=await(await p.request.get(base+'/sitemap_index.xml')).text();const sitemaps=[];
 for(const m of index.matchAll(/<loc>([^<]+)<\/loc>/g)){if(new URL(m[1]).origin!==base)continue;const r=await p.request.get(m[1]);const text=await r.text();sitemaps.push({path:new URL(m[1]).pathname,status:r.status(),locations:[...text.matchAll(/<loc>([^<]+)<\/loc>/g)].map(m=>m[1])});}
 const utm=await p.request.get(base+'/?utm_source=google&utm_medium=organic&utm_campaign=gbp');const utmHtml=await utm.text();
 for(const width of [390,1440])await sharp({create:{width:960,height:3008,channels:3,background:'white'}}).composite(tiles[width].map((input,k)=>({input,left:(k%4)*240,top:Math.floor(k/4)*752}))).png().toFile(out+'/contact-'+width+'.png');
 await b.close();fs.writeFileSync(out+'/public.json',JSON.stringify({rows,links,exclusions,motion,robots,sitemaps,utm:{status:utm.status(),canonical:(utmHtml.match(/<link[^>]+rel="canonical"[^>]+href="([^"]+)/)||[])[1]},errors,resources,limits:'Chrome Windows emulation; GA/Doctoralia and all non-GET requests blocked. No live form/Google Business/Doctoralia navigation. Contact sheets use top/middle/bottom resized samples, not legibility certification.'},null,2)+'\n');console.log(JSON.stringify({views:rows.flatMap(r=>r.views).length,pages:rows.length,overflows:rows.flatMap(r=>r.views).filter(v=>v.overflow),imageCandidates:rows.flatMap(r=>r.views).filter(v=>v.imageCandidates.length),links:links.filter(l=>l.status!==200),exclusions,errors,resources}));
})().catch(e=>{console.error(e.name,e.message);process.exit(1)});
