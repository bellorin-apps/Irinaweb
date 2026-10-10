// Prototipo de posición sobre producción. No modifica assets del tema ni el servidor.
const {chromium}=require(process.env.IRINA_PLAYWRIGHT||'playwright');
const fs=require('node:fs'),path=require('node:path');
const sharp=require(process.env.IRINA_SHARP||'sharp');
const out=path.resolve('docs/audit/2026-10-10/portrait-position');
const local=process.argv.includes('--local');
const tag=process.argv.find(x=>x.startsWith('--tag='))?.split('=')[1]||(local?'claude':'live');
const offsets=(process.argv.find(x=>x.startsWith('--offsets='))?.split('=')[1]||'0,32,48,64').split(',').map(Number);
if(!/^[a-z0-9-]+$/.test(tag)||offsets.some(x=>!Number.isFinite(x)))throw new Error('Argumentos inválidos');
const assets=Object.fromEntries(['css/components.css','js/main.js'].map(name=>[name,fs.readFileSync(path.resolve('wp-content/themes/irina-gonzalez/assets',name))]));
(async()=>{
 fs.mkdirSync(out,{recursive:true});
 const b=await chromium.launch({executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe'});
 const results=[];
 for(const width of [320,360,390,393,412,430]){
  const p=await b.newPage({viewport:{width,height:1100}});
  if(local)await p.route('**/themes/irina-gonzalez/assets/**',r=>{
   const name=new URL(r.request().url()).pathname.split('/assets/')[1];
   return assets[name]?r.fulfill({body:assets[name],contentType:name.endsWith('.css')?'text/css':'application/javascript'}):r.continue();
  });
  await p.goto('https://drairinagonzalez.com/',{waitUntil:'networkidle'});
  await p.evaluate(()=>document.fonts.ready);
  if(process.argv.includes('--large-text'))await p.addStyleTag({content:'html{font-size:20px!important}'});
  const section=p.locator('.di-section--doctor');await section.scrollIntoViewIfNeeded();await p.waitForTimeout(900);
  await p.addStyleTag({content:'.di-section--doctor .di-reveal{opacity:1;transform:none;transition:none}.di-section--doctor .di-doctor__portrait{overflow:visible}'});
  await p.evaluate(()=>window.dispatchEvent(new Event('resize')));await p.waitForTimeout(300);
  const source=await p.locator('.di-doctor__portrait img').evaluate(i=>i.currentSrc);
  const response=await p.request.get(source);
  const alpha=await sharp(await response.body()).ensureAlpha().raw().toBuffer({resolveWithObject:true});
  if(process.argv.includes('--profile')) {
   const profile=Array.from({length:128},(_,band)=>{
    let left=alpha.info.width;
    for(let y=Math.floor(band*alpha.info.height/128);y<Math.ceil((band+1)*alpha.info.height/128);y++)for(let x=0;x<left;x++)if(alpha.data[(y*alpha.info.width+x)*4+3]>16){left=x;break;}
    return Math.floor(left/alpha.info.width*1000)/1000;
   });
   fs.writeFileSync('tmp/portrait-profile.json',JSON.stringify(profile));
  }
  for(const offset of offsets){
   await p.evaluate(x=>{document.querySelector('.di-doctor__portrait img').style.transform=`translateX(-${x}px)`},offset);
   const row=await section.evaluate((s)=>{
    const r=e=>{const t=e.getBoundingClientRect();return {left:t.left,top:t.top,right:t.right,bottom:t.bottom,width:t.width,height:t.height}};
    const image=s.querySelector('.di-doctor__portrait img'),text=s.querySelector('.di-doctor__text');
    const words=[],walker=document.createTreeWalker(s.querySelector('.di-doctor__lead'),NodeFilter.SHOW_TEXT);
    while(walker.nextNode()){
     const n=walker.currentNode;for(const match of n.textContent.matchAll(/\S+/g)){
      const range=document.createRange();range.setStart(n,match.index);range.setEnd(n,match.index+match[0].length);
      for(const rect of range.getClientRects())words.push({left:rect.left,top:rect.top,right:rect.right,bottom:rect.bottom});
     }
    }
    return {width:innerWidth,section:r(s),image:r(image),text:r(text),words};
   });
   let total=0,overlap=0;
   for(const word of row.words)for(let y=word.top;y<word.bottom;y+=2)for(let x=word.left;x<word.right;x+=2){
    total++;
    const sx=Math.floor((x-row.image.left)/row.image.width*alpha.info.width),sy=Math.floor((y-row.image.top)/row.image.height*alpha.info.height);
    if(sx>=0&&sx<alpha.info.width&&sy>=0&&sy<alpha.info.height&&alpha.data[(sy*alpha.info.width+sx)*4+3]>64)overlap++;
   }
   row.paragraphBoxOverlapPercent=+(100*overlap/total).toFixed(2);delete row.words;
   row.offset=offset;row.localAssets=local;row.faceVisiblePercent=Math.max(0,Math.min(100,(Math.min(width,row.image.left+row.image.width*.65)-Math.max(0,row.image.left+row.image.width*.30))/(row.image.width*.35)*100));
   results.push(row);await section.screenshot({path:path.join(out,`${tag}-shift-${offset}-${width}.png`)});
  }
  await p.close();
 }
 fs.writeFileSync(path.join(out,`${tag}-metrics.json`),JSON.stringify(results,null,2)+'\n');
 console.log(results.map(r=>({width:r.width,offset:r.offset,height:r.section.height,imageHeight:r.image.height,imageLeft:r.image.left,face:r.faceVisiblePercent,paragraphOverlap:r.paragraphBoxOverlapPercent})));
 await b.close();
})().catch(e=>{console.error(e.name);process.exit(1)});
