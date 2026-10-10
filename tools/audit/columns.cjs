// Comprobación de columnas y de las tres tarjetas con assets locales, sin despliegue.
const {chromium}=require(process.env.IRINA_PLAYWRIGHT||'playwright');
const fs=require('node:fs'),path=require('node:path');
const live=process.argv.includes('--live');
const assets=Object.fromEntries(['css/components.css','js/main.js'].map(n=>[n,fs.readFileSync(path.resolve('wp-content/themes/irina-gonzalez/assets',n))]));
(async()=>{
 const b=await chromium.launch({executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe'});const rows=[];
 for(const width of [360,390,430,768,1440]){
  const p=await b.newPage({viewport:{width,height:width<900?844:900},deviceScaleFactor:width<900?2:1,isMobile:width<900,hasTouch:width<900});const errors=[];
  p.on('pageerror',e=>errors.push(e.name));p.on('console',m=>{if(m.type()==='error')errors.push('console-error')});
  if(!live)await p.route('**/themes/irina-gonzalez/assets/**',r=>{const n=new URL(r.request().url()).pathname.split('/assets/')[1];return assets[n]?r.fulfill({body:assets[n],contentType:n.endsWith('.css')?'text/css':'application/javascript'}):r.continue()});
  await p.goto('https://drairinagonzalez.com/',{waitUntil:'networkidle'});await p.evaluate(()=>document.fonts.ready);
  const section=p.locator('.di-section--doctor');await section.scrollIntoViewIfNeeded();await p.waitForTimeout(900);
  const cards=[];
  for(let i=0;i<3;i++){
   await p.locator('.di-creds__dots button').nth(i).click();await p.waitForTimeout(300);
   cards.push(await section.evaluate(s=>{
    const grid=s.querySelector('.di-doctor').getBoundingClientRect(),text=s.querySelector('.di-doctor__text').getBoundingClientRect(),portrait=s.querySelector('.di-doctor__portrait').getBoundingClientRect(),img=s.querySelector('.di-doctor__portrait img').getBoundingClientRect(),card=s.querySelector('.di-creds .is-active').getBoundingClientRect(),section=s.getBoundingClientRect();
    return {sectionHeight:section.height,textPercent:100*text.width/grid.width,photoPercent:100*portrait.width/grid.width,offset:img.left-(grid.left+grid.width*.6),imageHeight:img.height,bottomGap:section.bottom-img.bottom,cardHeight:card.height,oneActive:s.querySelectorAll('.di-creds .is-active').length===1,dots:s.querySelectorAll('.di-creds__dots button').length,overflow:document.documentElement.scrollWidth>innerWidth};
   }));
  }
  rows.push({width,live,deviceScaleFactor:width<900?2:1,cards,errors,sectionHeightStable:Math.max(...cards.map(c=>c.sectionHeight))-Math.min(...cards.map(c=>c.sectionHeight))<1});
  await p.close();
 }
 fs.writeFileSync(`docs/audit/2026-10-10/columns-carousel${live?'-live':''}.json`,JSON.stringify(rows,null,2)+'\n');console.log(rows);
 await b.close();if(rows.some(r=>r.errors.length||!r.sectionHeightStable||r.cards.some(c=>!c.oneActive||c.dots!==3||c.overflow||Math.abs(c.bottomGap)>1)||(r.width<900&&r.cards.some(c=>Math.abs(c.textPercent-60)>.1||Math.abs(c.photoPercent-40)>.1))))process.exit(1);
})().catch(e=>{console.error(e.name);process.exit(1)});
