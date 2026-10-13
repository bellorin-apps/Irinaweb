// Lectura pública sin POST, credenciales, formularios ni rutas privadas.
const {chromium}=require(process.env.IRINA_PLAYWRIGHT||'playwright');
const fs=require('node:fs');
const out='docs/audit/2026-10-12';fs.mkdirSync(out,{recursive:true});
(async()=>{
 const browser=await chromium.launch({executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe'});
 const rows=[];
 const routes=['/','/dra-irina-gonzalez-saez/','/primera-consulta/','/contacto/','/preguntas-frecuentes/','/links/','/gracias/','/aviso-de-privacidad/','/aviso-medico/','/terminos-de-uso/','/recursos/','/otorrinolaringologia/','/sueno/','/padecimientos/otitis/','/review-no-existe/'];
 for(const route of routes){
  const page=await browser.newPage();const errors=[];page.on('pageerror',e=>errors.push(e.name));
  const response=await page.goto('https://drairinagonzalez.com'+route+'?review=20261012',{waitUntil:'networkidle'});
  const html=await response.text();
  const row=await page.evaluate(()=>{
   const graphs=Array.from(document.querySelectorAll('script[type="application/ld+json"]')).flatMap(s=>{try{const j=JSON.parse(s.textContent);return j['@graph']||[j]}catch{return [{'@type':'INVALID'}]}});
   return {lang:document.documentElement.lang,title:document.title,description:document.querySelector('meta[name=description]')?.content,canonical:document.querySelector('link[rel=canonical]')?.href,robots:document.querySelector('meta[name=robots]')?.content,h1:document.querySelectorAll('h1').length,main:document.querySelectorAll('main').length,ogImage:document.querySelector('meta[property="og:image"]')?.content,schemas:graphs.map(g=>g['@type']),faqCount:graphs.filter(g=>g['@type']==='FAQPage').map(g=>g.mainEntity?.length),nav:Array.from(document.querySelectorAll('.di-nav a')).map(a=>({text:a.textContent.trim(),path:new URL(a.href).pathname})),motivos:Array.from(document.querySelectorAll('.di-symptom b')).map(e=>e.textContent),crumb:document.querySelector('.di-crumbs-bar')?.innerText,pluginAssets:Array.from(document.querySelectorAll('link[href],script[src]')).map(e=>(e.href||e.src).match(/\/plugins\/([^/]+)\//)?.[1]).filter(Boolean)};
  });
  const widths=[];for(const width of [360,390,430,768,1440]){await page.setViewportSize({width,height:900});await page.evaluate(()=>document.fonts.ready);widths.push({width,overflow:await page.evaluate(()=>document.documentElement.scrollWidth>innerWidth)});}
  rows.push({route,status:response.status(),...row,duplicateImageAttributes:(html.match(/<img\b[^>]*>/gi)||[]).some(t=>['loading','fetchpriority','decoding'].some(a=>(t.match(new RegExp('\\b'+a+'=','g'))||[]).length>1)),widths,errors});await page.close();
 }
 const page=await browser.newPage();const rest=await page.request.get('https://drairinagonzalez.com/wp-json/');const j=await rest.json();
 const root={status:rest.status(),timezone:j.timezone_string,gmtOffset:j.gmt_offset};
 const practice=await page.request.get('https://drairinagonzalez.com/wp-json/dra-irina/v1/practice');const p=await practice.json();root.practicePrivateFieldsPresent=['maps_api_key','maps_map_id','contacto_copia','horario'].filter(k=>Object.hasOwn(p,k));
 const sitemap=await page.request.get('https://drairinagonzalez.com/page-sitemap.xml');root.sitemapStatus=sitemap.status();root.sitemapPaths=Array.from((await sitemap.text()).matchAll(/<loc>(.*?)<\/loc>/g)).map(m=>new URL(m[1]).pathname);
 const devices=[];
 for(const [name,width,ua] of [['Galaxy',360,'Mozilla/5.0 (Linux; Android 13; SM-S901B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36'],['iPhone',393,'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1']]){
  const phone=await browser.newPage({viewport:{width,height:844},deviceScaleFactor:2,isMobile:true,hasTouch:true,userAgent:ua});await phone.goto('https://drairinagonzalez.com/primera-consulta/',{waitUntil:'networkidle'});await phone.evaluate(()=>document.fonts.ready);
  devices.push({name,width,...await phone.locator('.di-bleed__bg img').evaluate(i=>({position:getComputedStyle(i).objectPosition,naturalWidth:i.naturalWidth,naturalHeight:i.naturalHeight,mobileVersion:new URL(i.currentSrc).searchParams.has('m'),loading:i.loading,fetchpriority:i.getAttribute('fetchpriority')}))});await phone.screenshot({path:out+'/primera-'+name+'.png'});await phone.close();
 }
 await browser.close();fs.writeFileSync(out+'/public-review.json',JSON.stringify({rows,root,devices},null,2)+'\n');console.log(JSON.stringify({routes:rows.map(r=>({route:r.route,status:r.status,schemas:r.schemas,errors:r.errors,overflow:r.widths.some(w=>w.overflow)})),root,devices}));
})().catch(e=>{console.error(e.name);process.exit(1)});
