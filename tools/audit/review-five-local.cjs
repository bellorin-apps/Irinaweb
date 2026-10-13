const {chromium}=require(process.env.IRINA_PLAYWRIGHT||'playwright');
const fs=require('node:fs'),vm=require('node:vm'),assert=require('node:assert/strict');
(async()=>{
 const ga=JSON.parse(fs.readFileSync('tmp/five-ga4.json'));
 const names=['appointment_click','whatsapp_click','phone_click','directions_click','contact_submit','doctoralia_click'];
 for(const dnt of [false,true]){
  const handlers={},idle=[],provider=[];const ctx={navigator:{doNotTrack:dnt?'1':'0'},location:{pathname:'/contacto/'},Date,document:{readyState:'loading',addEventListener:(n,f)=>handlers[n]=f,createElement:()=>({}),head:{appendChild:s=>provider.push(s)}},requestIdleCallback:f=>idle.push(f)};
  ctx.window=ctx;ctx.addEventListener=(n,f)=>handlers[n]=f;vm.createContext(ctx);vm.runInContext(ga.public['di-ga4'],ctx);vm.runInContext(ga.public['di-events'],ctx);
  if(dnt){let calls=0;ctx.gtag=()=>calls++;for(const name of names)handlers.click({target:{closest:()=>({getAttribute:()=>name})}});assert.equal(calls,0);assert.equal(provider.length,0);}
  else{assert.equal(ctx.dataLayer[1][1],'G-5961XX8QZ7');for(const name of names)handlers.click({target:{closest:()=>({getAttribute:()=>name})}});assert.equal(ctx.dataLayer.length,8);assert.equal(provider.length,0);handlers.load();idle[0]();assert.equal(provider.length,1);}
 }
 assert.deepEqual(ga.editor,[]);assert.deepEqual(ga.invalid,[]);assert.ok(ga.thanks['di-ga4'].includes("gtag('event','contact_submit'"));
 const manifest=JSON.parse(fs.readFileSync('wp-content/themes/irina-gonzalez/assets/brand/site.webmanifest'));
 const sharp=require(process.env.IRINA_SHARP||'sharp');for(const icon of manifest.icons){const meta=await sharp('wp-content/themes/irina-gonzalez/assets/brand/'+icon.src).metadata();assert.equal(icon.sizes,meta.width+'x'+meta.height);}
 const b=await chromium.launch({executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe'});const p=await b.newPage({viewport:{width:360,height:900}});
 await p.route('**/*',r=>{const pathname=new URL(r.request().url()).pathname;const src='wp-content/themes/irina-gonzalez/'+pathname.replace(/^\/theme\//,'');return pathname.startsWith('/theme/assets/')&&fs.existsSync(src)?r.fulfill({body:fs.readFileSync(src),contentType:src.endsWith('.svg')?'image/svg+xml':'image/png'}):r.abort()});
 await p.setContent(fs.readFileSync('tmp/five-logos.html','utf8'));for(const name of ['tokens','base','components'])await p.addStyleTag({path:'wp-content/themes/irina-gonzalez/assets/css/'+name+'.css'});await p.addScriptTag({path:'wp-content/themes/irina-gonzalez/assets/js/main.js'});await p.waitForTimeout(300);
 const hidden=await p.locator('.di-logos__item[aria-hidden]').evaluateAll(es=>es.every(e=>e.tabIndex===-1));assert.ok(hidden);
 const originals=p.locator('.di-logos__item:not([aria-hidden])');const count=await originals.count();assert.equal(count,9);
 for(let i=0;i<count;i++){const item=originals.nth(i);await item.focus();await p.waitForTimeout(350);const r=await item.evaluate(e=>({focus:document.activeElement===e,visible:e.getBoundingClientRect().right>0&&e.getBoundingClientRect().left<innerWidth,tip:getComputedStyle(e,'::after').opacity}));assert.ok(r.focus&&r.visible&&r.tip==='1');}
 const track=p.locator('.di-logos__track');const initial=await track.evaluate(e=>getComputedStyle(e).transform);await p.waitForTimeout(250);assert.equal(await track.evaluate(e=>getComputedStyle(e).transform),initial);
 await p.emulateMedia({reducedMotion:'reduce'});await p.waitForTimeout(100);assert.equal(await track.evaluate(e=>getComputedStyle(e).animationName),'none');
 await b.close();const row={sixEventsQueued:true,providerAfterLoad:true,dntBlocksExternalGtag:true,editorsAndInvalidExcluded:true,manifestIconDimensions:true,originals:count,hiddenNotFocusable:true,keyboardTooltipVisible:true,keyboardPausesTrack:true,reducedMotionStatic:true};fs.writeFileSync('docs/audit/2026-10-13/five-local.json',JSON.stringify(row,null,2)+'\n');console.log(row);
})().catch(e=>{console.error(e.message);process.exit(1)});
