// Fresh anonymous browser; no auth, no forms, no stored HTML or privileged response bodies.
const {chromium}=require(process.env.IRINA_PLAYWRIGHT||'playwright');
const fs=require('node:fs');
const base='https://drairinagonzalez.com',out='docs/audit/2026-10-14b';fs.mkdirSync(out,{recursive:true});
(async()=>{
 const b=await chromium.launch({executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe'});
 const p=await b.newPage({viewport:{width:1440,height:900}});
 await p.route('**/*',r=>r.request().method()==='POST'||/googletagmanager|google-analytics/.test(new URL(r.request().url()).hostname)?r.abort():r.continue());
 const prev=JSON.parse(fs.readFileSync('docs/audit/2026-10-14/public.json','utf8'));
 const routes=['/','/dra-irina-gonzalez-saez/','/otorrinolaringologia/','/sueno/','/primera-consulta/','/contacto/','/preguntas-frecuentes/','/aviso-de-privacidad/','/aviso-medico/','/terminos-de-uso/',...prev.slugs.map(s=>'/sueno/'+s+'/')];
 const rows=[],errors=[];p.on('pageerror',e=>errors.push(e.name));
 for(const route of [...routes,'/audit-not-a-page/']){
  const r=await p.goto(base+route,{waitUntil:'networkidle'});await p.evaluate(()=>document.fonts.ready);
  const meta=await p.evaluate(()=>{
   const nodes=[];document.querySelectorAll('script[type="application/ld+json"]').forEach(s=>{try{const walk=o=>{if(!o||typeof o!=='object')return;if(o['@type'])nodes.push(o);Object.values(o).forEach(v=>Array.isArray(v)?v.forEach(walk):walk(v));};walk(JSON.parse(s.textContent));}catch{}});
   return {title:document.title,description:document.querySelector('meta[name="description"]')?.content,canonical:document.querySelector('link[rel="canonical"]')?.href,robots:document.querySelector('meta[name="robots"]')?.content,h1:document.querySelectorAll('h1').length,main:document.querySelectorAll('main').length,og:document.querySelector('meta[property="og:image"]')?.content,types:nodes.flatMap(n=>n['@type']),physicians:nodes.filter(n=>[].concat(n['@type']).includes('Physician')).map(n=>({id:n['@id'],types:n['@type'],alumni:!!n.alumniOf})),crumbs:nodes.filter(n=>n['@type']==='BreadcrumbList').map(n=>n.itemListElement),generator:document.querySelectorAll('meta[name="generator"]').length};
  });
  const audits=[];
  for(const width of [1440,390]){
   await p.setViewportSize({width,height:900});await p.evaluate(async()=>{for(let y=0;y<document.documentElement.scrollHeight;y+=700){scrollTo(0,y);await new Promise(r=>setTimeout(r,35));}scrollTo(0,0);});await p.waitForTimeout(500);
   if(!await p.evaluate(()=>!!window.axe))await p.addScriptTag({path:'C:/Users/bellg/AppData/Local/npm-cache/_npx/68d4b0c02cc20704/node_modules/axe-core/axe.min.js'});
   const a=await p.evaluate(async()=>{const r=await axe.run(document,{runOnly:{type:'tag',values:['wcag2a','wcag2aa','wcag21a','wcag21aa','wcag22aa']}});return {version:r.testEngine.version,violations:r.violations.map(v=>({id:v.id,impact:v.impact,nodes:v.nodes.map(n=>({target:n.target,summary:n.failureSummary}))})),incomplete:r.incomplete.map(v=>({id:v.id,count:v.nodes.length})),overflow:document.documentElement.scrollWidth>innerWidth};});audits.push({width,...a});
  }
  rows.push({route,status:r.status(),...meta,titleLength:[...meta.title].length,descriptionLength:[...(meta.description||'')].length,audits});
 }
 const security=[];
 for(const route of ['/wp-json/wp/v2/users/me?context=edit','/wp-json/wp/v2/users','/wp-json/wp/v2/settings','/wp-json/dra-irina/v1/ops/info','/wp-json/dra-irina/v1/ops/option/blogname','/wp-json/dra-irina/v1/practice','/xmlrpc.php','/readme.html','/?author=1']){
  const r=await p.request.get(base+route,{maxRedirects:0});security.push({route,status:r.status(),redirect:!!r.headers().location,headers:Object.fromEntries(Object.entries(r.headers()).filter(([k])=>['strict-transport-security','x-content-type-options','x-frame-options','referrer-policy','permissions-policy','content-security-policy','x-powered-by'].includes(k)))});
 }
 const robots=await(await p.request.get(base+'/robots.txt')).text();const old=await p.request.get('https://otorrino-monterrey.com/',{maxRedirects:0});
 const menu=[];for(const motion of ['no-preference','reduce']){await p.emulateMedia({reducedMotion:motion});await p.setViewportSize({width:390,height:852});await p.goto(base+'/sueno/',{waitUntil:'networkidle'});await p.locator('.di-burger').click();await p.waitForTimeout(500);const first=await p.locator('#di-menu-panel a').first().evaluate(e=>document.activeElement===e);await p.locator('#di-menu-panel a').last().focus();await p.keyboard.press('Tab');const wraps=await p.locator('.di-burger').evaluate(e=>document.activeElement===e);await p.keyboard.press('Escape');menu.push({motion,first,wraps,escape:await p.locator('.di-burger').evaluate(e=>document.activeElement===e&&e.getAttribute('aria-expanded')==='false')});}
 await b.close();fs.writeFileSync(out+'/public.json',JSON.stringify({rows,security,robots,oldDomain:{status:old.status(),location:old.headers().location},menu,errors},null,2)+'\n');console.log(JSON.stringify({pages:rows.map(r=>({route:r.route,status:r.status,title:r.titleLength,desc:r.descriptionLength,violations:r.audits.map(a=>a.violations.map(v=>v.id))})),security:security.map(s=>({route:s.route,status:s.status})),menu,errors}));
})().catch(e=>{console.error(e.name,e.message);process.exit(1)});
