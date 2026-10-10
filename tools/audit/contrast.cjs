const {chromium}=require(process.env.IRINA_PLAYWRIGHT||'playwright');
const sharp=require(process.env.IRINA_SHARP||'sharp');
const fs=require('node:fs'),path=require('node:path');
function lum(c){return c.map(v=>{v/=255;return v<=0.04045?v/12.92:((v+0.055)/1.055)**2.4}).reduce((s,v,i)=>s+v*[.2126,.7152,.0722][i],0)}
(async()=>{
 const b=await chromium.launch({executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe'});const p=await b.newPage({viewport:{width:360,height:900}});
 await p.route('**/themes/irina-gonzalez/assets/**',r=>{const file=path.resolve('wp-content/themes/irina-gonzalez/assets',new URL(r.request().url()).pathname.split('/assets/')[1]);return fs.existsSync(file)?r.fulfill({path:file}):r.continue()});
 await p.goto('https://drairinagonzalez.com/',{waitUntil:'networkidle'});await p.locator('.di-section--doctor').scrollIntoViewIfNeeded();await p.waitForTimeout(800);
 const samples=await p.locator('.di-section--doctor').evaluate(s=>{
  const base=s.getBoundingClientRect();return ['.di-eyebrow','.di-quote','.di-quote b','.di-doctor__lead p','.di-creds li.is-active','.di-btn'].map(sel=>{const e=s.querySelector(sel),r=e.getBoundingClientRect(),c=getComputedStyle(e);return {selector:sel,color:c.color,left:r.left-base.left,top:r.top-base.top,width:r.width,height:r.height,size:c.fontSize}});
 });
 await p.addStyleTag({content:'.di-section--doctor .di-reveal{opacity:1;transform:none}.di-section--doctor .di-doctor__portrait{visibility:hidden}.di-section--doctor .di-doctor__text,.di-section--doctor .di-doctor__text *{color:transparent}.di-section--doctor .di-doctor__text svg{visibility:hidden}'});
 const buffer=await p.locator('.di-section--doctor').screenshot();const {data,info}=await sharp(buffer).removeAlpha().raw().toBuffer({resolveWithObject:true});
 for(const s of samples){const rgb=s.color.match(/[\d.]+/g).slice(0,3).map(v=>Number(v)*(s.color.startsWith('color(srgb')?255:1)),f=lum(rgb),button=s.selector==='.di-btn',ix=button?s.width*.20:3,iy=button?s.height*.25:3;let minimum=100;for(let y=Math.max(0,Math.ceil(s.top+iy));y<Math.min(info.height,s.top+s.height-iy);y+=3)for(let x=Math.max(0,Math.ceil(s.left+ix));x<Math.min(info.width,s.left+s.width-ix);x+=3){const i=(y*info.width+x)*info.channels,l=lum([...data.slice(i,i+3)]);minimum=Math.min(minimum,(Math.max(l,f)+.05)/(Math.min(l,f)+.05))}s.minimumContrast=+minimum.toFixed(2);s.method='mínimo conservador de caja, paso 3 px; botón: región central para excluir esquinas fuera de la cápsula'}
 fs.writeFileSync('docs/audit/2026-10-10/contrast.json',JSON.stringify(samples,null,2)+'\n');console.log(samples.map(s=>({selector:s.selector,contrast:s.minimumContrast})));await b.close();
})().catch(e=>{console.error(e.name);process.exit(1)});
