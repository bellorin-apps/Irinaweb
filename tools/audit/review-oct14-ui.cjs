// Public baseline plus explicitly local main.js overlay. All form POSTs are intercepted; never reach WordPress.
const {chromium}=require(process.env.IRINA_PLAYWRIGHT||'playwright');
const fs=require('node:fs');
const base='https://drairinagonzalez.com',out='docs/audit/2026-10-14';
(async()=>{
 const b=await chromium.launch({executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe'});
 const result={hero:[],menu:[],form:[],images:[],limits:'Chromium emulation, local JS overlay for menu/form, no real POST or mail.'};
 for(const motion of ['no-preference','reduce']){
  const p=await b.newPage({viewport:{width:393,height:852},reducedMotion:motion});
  await p.route('**/*',r=>/google-analytics|googletagmanager/.test(new URL(r.request().url()).hostname)?r.abort():r.continue());
  for(const route of ['/','/sueno/','/otorrinolaringologia/']){
   await p.goto(base+route,{waitUntil:'networkidle'});await p.evaluate(()=>document.fonts.ready);await p.waitForTimeout(2000);
   result.hero.push({motion,route,...await p.locator('.di-bleed').evaluate(e=>({parts:[...e.querySelectorAll('.di-container > h1,.di-container > .di-lead,.di-bleed__bg img')].map(x=>({tag:x.tagName,opacity:getComputedStyle(x).opacity,transform:getComputedStyle(x).transform,animation:getComputedStyle(x).animationName})),running:e.getAnimations({subtree:true}).filter(a=>a.animationName?.startsWith('di-hero')).length}))});
  }await p.close();
 }
 const p=await b.newPage({viewport:{width:393,height:852}});
 let postCount=0;let networkError=false;
 await p.route('**/*',async r=>{
  const u=new URL(r.request().url());
  if(r.request().method()==='POST'){
   if(u.origin!==base||!u.pathname.endsWith('/admin-post.php'))return r.abort();
   postCount++;return networkError?r.abort():r.fulfill({status:422,contentType:'application/json',body:JSON.stringify({ok:false,error:'captcha',message:'Synthetic fixture rejection'})});
  }
  if(/googletagmanager|google-analytics|challenges.cloudflare/.test(u.hostname))return r.abort();
  if(u.pathname.endsWith('/assets/js/main.js'))return r.fulfill({path:'wp-content/themes/irina-gonzalez/assets/js/main.js',contentType:'application/javascript'});
  return r.continue();
 });
 for(const motion of ['no-preference','reduce']){
  await p.emulateMedia({reducedMotion:motion});await p.goto(base+'/sueno/',{waitUntil:'networkidle'});await p.locator('.di-burger').click();await p.waitForTimeout(600);
  const row={motion,firstFocused:await p.locator('#di-menu-panel a').first().evaluate(e=>e===document.activeElement)};
  await p.locator('#di-menu-panel a').last().focus();await p.keyboard.press('Tab');row.tabWrap=await p.locator('.di-burger').evaluate(e=>e===document.activeElement);
  await p.keyboard.press('Shift+Tab');row.shiftWrap=await p.locator('#di-menu-panel a').last().evaluate(e=>e===document.activeElement);
  await p.keyboard.press('Escape');row.escape=await p.locator('.di-burger').evaluate(e=>e===document.activeElement&&e.getAttribute('aria-expanded')==='false');
  await p.locator('.di-burger').click();await p.setViewportSize({width:1440,height:900});await p.waitForTimeout(150);row.desktopCloses=await p.evaluate(()=>!document.body.classList.contains('menu-open'));result.menu.push(row);await p.setViewportSize({width:393,height:852});
 }
 await p.goto(base+'/contacto/',{waitUntil:'networkidle'});
 await p.evaluate(()=>{window.resetCount=0;window.turnstile={reset:()=>window.resetCount++};const f=document.querySelector('form.di-form');f.querySelector('[name="di_name"]').value='Synthetic patient';f.querySelector('[name="di_phone"]').value='8112345678';f.querySelector('[name="di_motivo"]').value='sueno';f.querySelector('[name="di_consent"]').checked=true;});
 for(const error of [false,true]){networkError=error;await p.locator('form.di-form').evaluate(f=>f.requestSubmit());await p.waitForTimeout(450);result.form.push({networkError:error,...await p.evaluate(()=>({resets:window.resetCount,sending:document.querySelector('form.di-form').classList.contains('is-sending')}))});}
 result.interceptedPosts=postCount;
 for(const width of [360,393,1440]){
  await p.setViewportSize({width,height:900});await p.goto(base+'/',{waitUntil:'networkidle'});
  await p.evaluate(async()=>{for(let y=0;y<document.documentElement.scrollHeight;y+=650){scrollTo(0,y);await new Promise(r=>setTimeout(r,65));}});await p.waitForTimeout(400);
  result.images.push({width,...await p.evaluate(()=>({broken:[...document.querySelectorAll('main img')].filter(i=>i.offsetWidth&&(!i.complete||!i.naturalWidth)).length,images:[...document.querySelectorAll('.di-bleed__bg img,.di-doctor__bg img')].map(i=>({path:new URL(i.currentSrc).pathname,naturalWidth:i.naturalWidth,transfer:performance.getEntriesByName(i.currentSrc)[0]?.transferSize}))}))});
 }
 await p.goto(base+'/sueno/',{waitUntil:'networkidle'});await p.setViewportSize({width:393,height:852});await p.locator('.di-burger').click();await p.waitForTimeout(700);await p.screenshot({path:out+'/menu-local.png'});
 await b.close();fs.writeFileSync(out+'/ui.json',JSON.stringify(result,null,2)+'\n');
 const pass=result.menu.every(r=>r.firstFocused&&r.tabWrap&&r.shiftWrap&&r.escape&&r.desktopCloses)&&result.form.every((r,i)=>r.resets===i+1&&!r.sending)&&postCount===2&&result.images.every(r=>r.broken===0);
 console.log(JSON.stringify({pass,menu:result.menu,form:result.form,images:result.images}));if(!pass)process.exit(1);
})().catch(e=>{console.error(e.name,e.message);process.exit(1)});
