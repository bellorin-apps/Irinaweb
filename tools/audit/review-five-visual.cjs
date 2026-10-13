const {chromium}=require(process.env.IRINA_PLAYWRIGHT||'playwright');
const fs=require('node:fs'),assert=require('node:assert/strict');
(async()=>{
 const b=await chromium.launch({executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe'});const rows=[];
 for(const [name,width,ua] of [['Galaxy',360,'Mozilla/5.0 (Linux; Android 13; SM-S901B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36'],['iPhone',393,'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1'],['Desktop',1440,undefined]]){
  for(const reduced of [false,true]){
   const p=await b.newPage({viewport:{width,height:1100},isMobile:width<900,deviceScaleFactor:width<900?2:1,...(ua?{userAgent:ua}:{}),reducedMotion:reduced?'reduce':'no-preference'});
   await p.route('**/*',r=>/google-analytics\.com|googletagmanager\.com/.test(new URL(r.request().url()).hostname)?r.abort():r.continue());
   await p.goto('https://drairinagonzalez.com/dra-irina-gonzalez-saez/?review=visual20261013',{waitUntil:'networkidle'});await p.evaluate(()=>document.fonts.ready);
   const sec=p.locator('.di-narrative--card');await sec.scrollIntoViewIfNeeded();await p.waitForTimeout(900);
   const row=await sec.evaluate(s=>{
    const card=s.querySelector('.di-narrative__card'),box=card.getBoundingClientRect(),walker=document.createTreeWalker(card,NodeFilter.SHOW_TEXT);let outside=0,covered=0;
    while(walker.nextNode()){const n=walker.currentNode;for(const m of n.textContent.matchAll(/\S+/g)){const range=document.createRange();range.setStart(n,m.index);range.setEnd(n,m.index+m[0].length);for(const r of range.getClientRects()){if(r.left<box.left-1||r.right>box.right+1||r.top<box.top-1||r.bottom>box.bottom+1)outside++;const x=(r.left+r.right)/2,y=(r.top+r.bottom)/2;if(y>120&&y<innerHeight-100&&x>0&&x<innerWidth){const e=document.elementFromPoint(x,y);if(e&&!card.contains(e))covered++;}}}}
    return {overflow:document.documentElement.scrollWidth>innerWidth,outside,covered,endoProgress:getComputedStyle(s).getPropertyValue('--endo-p').trim(),cardZ:getComputedStyle(card).zIndex};
   });assert.ok(!row.overflow&&row.outside===0&&row.covered===0);if(reduced)assert.equal(row.endoProgress,'1');
   if(!reduced&&width!==1440)await sec.screenshot({path:'docs/audit/2026-10-13/doctor-'+name+'.png'});
   rows.push({name,width,reduced,...row});await p.close();
  }
 }
 await b.close();fs.writeFileSync('docs/audit/2026-10-13/five-visual.json',JSON.stringify(rows,null,2)+'\n');console.log(rows);
})().catch(e=>{console.error(e.message);process.exit(1)});
