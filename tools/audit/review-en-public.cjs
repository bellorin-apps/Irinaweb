// Anonymous source/browser checks only. No accounts, forms, publication or external conversion calls.
const fs = require('node:fs');
const {chromium} = require(process.env.IRINA_PLAYWRIGHT || 'playwright');
const base = 'https://drairinagonzalez.com', out = 'docs/audit/2026-10-19';
const assert = (v, m) => { if (!v) throw new Error(m); };
(async () => {
  const browser = await chromium.launch({executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe'});
  const page = await browser.newPage();
  const errors = [];
  page.on('pageerror', e => errors.push({name:e.name}));
  await page.route('**/*', route => {
    const req=route.request(), host=new URL(req.url()).hostname;
    return req.method()!=='GET' || /googletagmanager|google-analytics|doctoralia|docplanner|maps.googleapis/.test(host) ? route.abort() : route.continue();
  });
  const rows = [];
  for (const [path, mobile, dark] of [['/sueno/', '68% 50%', true], ['/otorrinolaringologia/', '64% 50%', false]]) {
    for (const [width,height] of [[390,844],[393,852],[832,340],[1440,1000]]) {
      await page.setViewportSize({width,height});
      const response = await page.goto(base+path,{waitUntil:'networkidle'});
      await page.evaluate(() => document.fonts.ready);
      const data = await page.evaluate(() => {
        const section=document.querySelector('.di-bleed'), img=section?.querySelector('img');
        return {status:0, overflow:document.documentElement.scrollWidth>innerWidth,
          dark:document.body.classList.contains('di-dark'),border:getComputedStyle(document.body).getPropertyValue('--color-border').trim(),
          title:section?.querySelector('h1')?.textContent.trim(),bold:section?.querySelector('h1 b')?.textContent,italic:section?.querySelector('h1 em')?.textContent,
          photo:section?.classList.contains('di-bleed--photo'),shade:!!section?.querySelector('.di-bleed__shade'),breath:!!section?.querySelector('.di-breath'),
          focus:img?getComputedStyle(img).objectPosition:null,image:img?{path:new URL(img.currentSrc).pathname,width:img.naturalWidth,height:img.naturalHeight,loaded:img.complete&&img.naturalWidth>0}:null,
          htmlLang:document.documentElement.lang};
      });
      data.status=response.status();
      assert(data.status===200 && !data.overflow && data.photo && data.shade && !data.breath && data.image.loaded && data.dark===dark, 'Area regression');
      assert(data.focus===(width<900?mobile:'50% 0%'), 'Area crop differs from D-096');
      assert(data.bold && data.italic, 'Area title sequence missing');
      if(dark) assert(data.border==='#342b56', 'Night separators');
      rows.push({path,width,height,...data});
      await page.screenshot({path:`${out}/${dark?'sleep':'ent'}-${width}.png`});
    }
  }
  const manifest=JSON.parse(fs.readFileSync('tools/content/en/pages.json','utf8'));
  const fichas=JSON.parse(fs.readFileSync('tools/content/en/fichas-en.json','utf8'));
  const routes=[...Object.keys(manifest).map(s=>s==='en'?'/en/':`/en/${s}/`),...fichas.map(f=>`/en/sleep/${f.slug}/`)];
  const drafts=[];
  for(const path of routes){ const r=await page.request.get(base+path,{maxRedirects:0}); const h=await r.text(); const location=r.headers().location; drafts.push({path,status:r.status(),location,lang:(h.match(/<html[^>]+lang="([^"]+)"/)||[])[1]});assert(r.status()===404 || (path==='/en/contact/' && r.status()===301 && location===base+'/contacto/'),'Draft unexpectedly public'); }
  const index=await(await page.request.get(base+'/sitemap_index.xml')).text();
  const urls=[];
  for(const match of index.matchAll(/<loc>([^<]+)<\/loc>/g)){
    const h=await(await page.request.get(match[1])).text();
    urls.push(...[...h.matchAll(/<loc>([^<]+)<\/loc>/g)].map(m=>m[1]));
  }
  const home=await(await page.request.get(base+'/')).text();
  const live={sitemapCount:urls.length,englishInSitemap:urls.filter(u=>new URL(u).pathname.startsWith('/en/')).length,
    englishHreflang:/hreflang="en-US"/.test(home),switchVisible:/class="di-lang/.test(home)};
  assert(!live.englishInSitemap&&!live.englishHreflang&&!live.switchVisible,'English leaked before publication');
  assert(!errors.length,'JS exception');
  await browser.close();
  fs.writeFileSync(out+'/public.json',JSON.stringify({rows,drafts,live,errors,limits:'Chrome Windows at 390/393/832/1440, not physical iOS/Android. Only Spanish areas publicly rendered; EN15x404 and /en/contact/301 to ES, recorded as local FIX pending deployment. No English preview/account, real form, SMTP, GA conversion, deployment or publication. OG/hreflang/sitemap of published EN not verified.'},null,2)+'\n');
  console.log(`Areas: ${rows.length} views; EN: ${drafts.filter(r=>r.status===404).length}x404 + ${drafts.filter(r=>r.status===301).length}x301 to ES; sitemap ${urls.length}, no EN exposure.`);
})().catch(e=>{console.error(e.name,e.message);process.exit(1);});
