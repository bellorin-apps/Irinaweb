// HTML público con CSS local: no demuestra despliegue ni reproduce el teléfono real.
const {chromium}=require(process.env.IRINA_PLAYWRIGHT||'playwright');
const fs=require('node:fs');
(async()=>{
 const css=fs.readFileSync('wp-content/themes/irina-gonzalez/assets/css/components.css');
 const browser=await chromium.launch({executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe'});
 const rows=[];
 for(const width of [320,360,390,430]){
  const page=await browser.newPage({viewport:{width,height:1000},isMobile:true,deviceScaleFactor:2});
  await page.route('**/assets/css/components.css*',r=>r.fulfill({body:css,contentType:'text/css'}));
  await page.goto('https://drairinagonzalez.com/',{waitUntil:'networkidle'});
  await page.evaluate(()=>document.fonts.ready);
  const section=page.locator('.di-section--doctor');await section.scrollIntoViewIfNeeded();
  await page.addStyleTag({content:'.di-reveal{opacity:1!important;transform:none!important;transition:none!important;animation:none!important}'});
  const states=[];
  for(const enlarged of [false,true]){
   if(enlarged)await page.addStyleTag({content:'html{font-size:20px!important}'});
   await page.waitForTimeout(900);
   states.push(await section.evaluate(s=>{
    const img=s.querySelector('.di-doctor__portrait img').getBoundingClientRect(),box=s.getBoundingClientRect();
    return {imageHeight:img.height,imageLeft:img.left,sectionHeight:box.height,bottomGap:box.bottom-img.bottom,overflow:document.documentElement.scrollWidth>innerWidth};
   }));
   if(!enlarged&&width===360)await section.screenshot({path:'docs/audit/2026-10-10/portrait-cap-360.png'});
  }
  rows.push({width,states});await page.close();
 }
 await browser.close();fs.writeFileSync('docs/audit/2026-10-10/portrait-cap.json',JSON.stringify(rows,null,2)+'\n');
 console.log(JSON.stringify(rows));
 if(rows.some(r=>r.states.some(s=>s.overflow||Math.abs(s.bottomGap)>1||s.imageHeight>Math.max(600,Math.min(640,1.64*r.width))+1)))process.exit(1);
})().catch(e=>{console.error(e.name);process.exit(1)});
