// QA de solo lectura: producción o assets locales sobre el HTML publicado. Nunca envía formularios.
const { chromium } = require(process.env.IRINA_PLAYWRIGHT || 'playwright');
const fs = require('node:fs');
const path = require('node:path');
const out = path.resolve('docs/audit/2026-10-10');
const local = process.argv.includes('--local');
const variant = process.argv.find(x => x.startsWith('--variant='))?.split('=')[1] || 'baseline';
const widths = [360,390,414,430,483,768,1024,1440];
(async () => {
  fs.mkdirSync(out,{recursive:true});
  const browser = await chromium.launch({executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe',headless:true});
  const rows=[];
  for (const width of widths) {
    const page=await browser.newPage({viewport:{width,height:1100},deviceScaleFactor:1});
    const errors=[];
    page.on('pageerror',e=>errors.push(e.name));
    if(local) await page.route('**/themes/irina-gonzalez/assets/**',route=>{
      const name=new URL(route.request().url()).pathname.split('/assets/')[1];
      const file=path.resolve('wp-content/themes/irina-gonzalez/assets',name);
      return fs.existsSync(file)?route.fulfill({path:file}):route.continue();
    });
    await page.goto('https://drairinagonzalez.com/',{waitUntil:'networkidle'});
    await page.evaluate(()=>document.fonts.ready);
    const section=page.locator('.di-section--doctor');
    await section.scrollIntoViewIfNeeded();
    await page.waitForTimeout(700);
    await page.evaluate(()=>document.querySelectorAll('.di-reveal').forEach(e=>e.classList.add('is-visible')));
    if(variant==='B') await page.addStyleTag({content:'@media(max-width:899px){.di-section--doctor-bg .di-doctor__text{width:60%}.di-section--doctor-bg .di-doctor__portrait{left:calc(60% + 16px)}.di-section--doctor-bg .di-quote{font-size:clamp(1.5rem,6.3vw,1.8rem)}.di-section--doctor-bg .di-doctor__portrait img{width:145%}}'});
    const row=await section.evaluate((s)=>{
      const rect=e=>{const r=e.getBoundingClientRect();return {left:r.left,right:r.right,width:r.width,height:r.height,bottom:r.bottom}};
      const text=s.querySelector('.di-doctor__text'),img=s.querySelector('.di-doctor__portrait img'),quote=s.querySelector('.di-quote'),lead=s.querySelector('.di-doctor__lead p'),card=s.querySelector('.di-creds .is-active');
      const lines=e=>Math.round(e.getBoundingClientRect().height/parseFloat(getComputedStyle(e).lineHeight));
      const i=rect(img),t=rect(text);
      return {width:innerWidth,section:rect(s),text:t,image:i,gap:i.left-t.right,visibleImagePercent:100*Math.max(0,Math.min(innerWidth,i.right)-Math.max(0,i.left))/i.width,quoteLines:lines(quote),paragraphLines:lines(lead),card:card?rect(card):null,dots:s.querySelectorAll('.di-creds__dots button').length,active:s.querySelectorAll('.di-creds .is-active').length,ring:s.querySelector('.di-creds__dots')?.classList.contains('is-playing'),button:rect(s.querySelector('.di-btn')),overflow:document.documentElement.scrollWidth>innerWidth};
    });
    // Contorno facial conservador marcado visualmente: x=30�65 % del PNG, sin cabello.
    row.faceVisiblePercent=Math.min(100, Math.max(0, (Math.min(row.width,row.image.left+row.image.width*0.65)-(row.image.left+row.image.width*0.30))/(row.image.width*0.35)*100));
    row.bottomGap=row.section.bottom-row.image.bottom;
    row.errors=errors; rows.push(row);
    await section.screenshot({path:path.join(out,`${variant}-${width}.png`)});
    await page.close();
  }
  fs.writeFileSync(path.join(out,`${variant}-metrics.json`),JSON.stringify(rows,null,2)+'\n');
  console.log(rows.map(r=>({width:r.width,height:r.section.height,gap:r.gap,visible:r.visibleImagePercent,quote:r.quoteLines,paragraph:r.paragraphLines,card:r.card?.height,errors:r.errors})));
  await browser.close();
})().catch(e=>{console.error(e.message.replace(/key=[^&\s]+/g,'key=[redacted]'));process.exit(1)});
