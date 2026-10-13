// Fixtures DOM con código real; no prueba Safari nativo ni clics en producción.
const {chromium}=require(process.env.IRINA_PLAYWRIGHT||'playwright');
const fs=require('node:fs'),assert=require('node:assert/strict');
(async()=>{
 const b=await chromium.launch({executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe'});
 const p=await b.newPage();await p.setContent('<style>.di-logos__rail{touch-action:pan-y}.di-logos__track{display:flex;gap:20px;width:max-content;animation-duration:70s}.di-logos__item{width:120px;height:60px}</style><div class="di-logos__rail"><div class="di-logos__track">'+Array(4).fill('<span class="di-logos__item"></span>').join('')+'</div></div>');
 await p.evaluate(()=>{const request=window.requestAnimationFrame,cancel=window.cancelAnimationFrame;window.pending=new Set();window.peak=0;window.requestAnimationFrame=f=>{const id=request(t=>{pending.delete(id);f(t)});pending.add(id);peak=Math.max(peak,pending.size);return id};window.cancelAnimationFrame=id=>{pending.delete(id);cancel(id)}});
 await p.addScriptTag({path:'wp-content/themes/irina-gonzalez/assets/js/main.js'});
 const pointer=async(type,x,id=1)=>p.locator('.di-logos__rail').dispatchEvent(type,{pointerId:id,pointerType:'touch',clientX:x,clientY:10,button:0});
 const x=()=>p.locator('.di-logos__track').evaluate(e=>new DOMMatrix(getComputedStyle(e).transform).m41);
 await pointer('pointerdown',200);await p.waitForTimeout(20);await pointer('pointermove',160);const before=await x();
 await pointer('pointerdown',100,2);await pointer('pointermove',20,2);assert.equal(await x(),before);
 await pointer('pointercancel',160);const cancelled=await x();await p.waitForTimeout(200);assert.ok(Math.abs((await x())-cancelled)<3);
 for(let i=0;i<3;i++){await pointer('pointerdown',200);await p.waitForTimeout(20);await pointer('pointermove',170);await pointer('pointerup',170);}
 assert.ok(await p.evaluate(()=>peak)<=1);
 await pointer('pointerdown',200);await pointer('pointermove',180);await p.locator('.di-logos__rail').dispatchEvent('lostpointercapture',{pointerId:1});await p.waitForTimeout(100);
 await p.emulateMedia({reducedMotion:'reduce'});await p.waitForTimeout(100);assert.equal(await p.evaluate(()=>pending.size),0);
 assert.equal(await p.locator('.di-logos__track').evaluate(e=>e.classList.contains('is-manual')),false);
 await pointer('pointerdown',200);await pointer('pointermove',100);assert.equal(await p.evaluate(()=>pending.size),0);
 const row={secondPointerIgnored:true,cancelNoFling:true,lostCaptureReleased:true,oneAnimationLoop:true,reducedMotionStopsLoop:true,touchAction:await p.locator('.di-logos__rail').evaluate(e=>getComputedStyle(e).touchAction)};
 assert.equal(row.touchAction,'pan-y');await b.close();fs.writeFileSync('docs/audit/2026-10-12/logos-fixtures.json',JSON.stringify(row,null,2)+'\n');console.log(row);
})().catch(e=>{console.error(e.message);process.exit(1)});
