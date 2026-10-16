// Diagnose pending lazy assets without treating unloaded/offscreen logos as broken images.
const {chromium}=require(process.env.IRINA_PLAYWRIGHT||'playwright');const fs=require('node:fs');
(async()=>{
 const b=await chromium.launch({executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe'});const p=await b.newPage({viewport:{width:390,height:900}});
 await p.route('**/*',r=> /googletagmanager|google-analytics|doctoralia/.test(new URL(r.request().url()).hostname)||(r.request().method()!=='GET'&&!new URL(r.request().url()).hostname.endsWith('challenges.cloudflare.com'))?r.abort():r.continue());
 await p.goto('https://drairinagonzalez.com/dra-irina-gonzalez-saez/',{waitUntil:'networkidle'});
 const sample=()=>p.locator('.di-logos img').evaluateAll(es=>es.map(i=>({path:new URL(i.currentSrc||i.src).pathname,loading:i.loading,complete:i.complete,width:i.naturalWidth,visible:!!i.getClientRects().length})));
 const before=await sample();await p.locator('.di-logos img').evaluateAll(es=>es.forEach(i=>{i.loading='eager';}));await p.waitForTimeout(1400);const after=await sample();
 const errors=[];p.on('pageerror',e=>errors.push({name:e.name,message:e.message.replace(/https?:\/\/\S+/g,'[URL omitted]').slice(0,150)}));await p.goto('https://drairinagonzalez.com/contacto/',{waitUntil:'networkidle'});await p.waitForTimeout(2500);
 const contact=await p.evaluate(()=>({widget:!!document.querySelector('.cf-turnstile'),forms:document.querySelectorAll('form.di-form').length,maps:document.querySelectorAll('[role="region"][aria-label*="Mapa"],.di-map').length,faqCopy:document.querySelector('main')?.innerText.includes('previa cita')}));
 await b.close();fs.writeFileSync('docs/audit/2026-10-16/followup.json',JSON.stringify({before,after,errors,contact,limits:'Browser DOM loading=eager diagnostic only; source and server unchanged. Cloudflare provider network allowed, own POST blocked. No form submitted.'},null,2)+'\n');console.log(JSON.stringify({pending:before.filter(i=>!i.complete).length,broken:after.filter(i=>!i.complete||!i.width).length,errors,contact}));
})().catch(e=>{console.error(e.name,e.message);process.exit(1)});
