const {chromium}=require(process.env.IRINA_PLAYWRIGHT||'playwright');
const fs=require('node:fs'),path=require('node:path');
(async()=>{
 const b=await chromium.launch({executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe'});
 const p=await b.newPage({viewport:{width:390,height:844}});
 await p.route('**/themes/irina-gonzalez/assets/**',r=>{const name=new URL(r.request().url()).pathname.split('/assets/')[1];const file=path.resolve('wp-content/themes/irina-gonzalez/assets',name);return fs.existsSync(file)?r.fulfill({path:file}):r.continue()});
 await p.goto('https://drairinagonzalez.com/',{waitUntil:'networkidle'});
 await p.locator('.di-section--doctor').scrollIntoViewIfNeeded();await p.waitForTimeout(700);
 const state=()=>p.evaluate(()=>({index:Array.from(document.querySelectorAll('.di-creds__dots button')).findIndex(e=>e.getAttribute('aria-current')==='true'),playing:document.querySelector('.di-creds__dots').classList.contains('is-playing'),height:document.querySelector('.di-creds').getBoundingClientRect().height,ringSize:getComputedStyle(document.querySelector('.di-creds__dots svg')).width,ringAnimation:getComputedStyle(document.querySelector('.di-creds__dots button[aria-current="true"] .di-ring__bar')).animationName}));
 const before=await state();await p.waitForTimeout(4100);const auto=await state();
 await p.evaluate(()=>{const e=document.querySelector('.di-creds');const start=new Event('touchstart');start.touches=[{clientX:180,clientY:20}];e.dispatchEvent(start);const end=new Event('touchend');end.changedTouches=[{clientX:90,clientY:20}];e.dispatchEvent(end)});
 await p.waitForTimeout(350);const touch=await state();await p.waitForTimeout(8100);const resume=await state();
 await p.setViewportSize({width:483,height:932});await p.waitForTimeout(350);const resize=await state();
 await p.emulateMedia({reducedMotion:'reduce'});await p.reload({waitUntil:'networkidle'});const reduced=await state();await p.waitForTimeout(4100);const reducedAfter=await state();
 const result={before,auto,touch,resume,resize,reduced,reducedAfter};
 result.pass=auto.index!==before.index&&!touch.playing&&resume.playing&&!reduced.playing&&reduced.index===reducedAfter.index&&resize.height>0;
 fs.writeFileSync('docs/audit/2026-10-10/interaction.json',JSON.stringify(result,null,2)+'\n');
 console.log(result);await b.close();if(!result.pass)process.exit(1);
})().catch(e=>{console.error(e.name);process.exit(1)});
