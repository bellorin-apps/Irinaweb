// Native-size representative screenshots, with no form submission, analytics or external profile navigation.
const {chromium}=require(process.env.IRINA_PLAYWRIGHT||'playwright');
(async()=>{
 const b=await chromium.launch({executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe'});
 const p=await b.newPage();await p.route('**/*',r=>r.request().method()!=='GET'||/googletagmanager|google-analytics|doctoralia/.test(new URL(r.request().url()).hostname)?r.abort():r.continue());
 for(const [name,route,width] of [['home-mobile','/',390],['home-tablet','/',768],['home-desktop','/',1440],['contact-mobile','/contacto/',390]]){
  await p.setViewportSize({width,height:900});await p.goto('https://drairinagonzalez.com'+route,{waitUntil:'networkidle'});await p.evaluate(()=>document.fonts.ready);await p.waitForTimeout(2000);
  await p.screenshot({path:'docs/audit/2026-10-16/'+name+'.png'});
 }
 await b.close();console.log('4 native screenshots saved (public baseline).');
})().catch(e=>{console.error(e.name,e.message);process.exit(1)});
