// Small anonymous HTTP follow-up; GET only, record selected public metadata, never full HTML.
const {chromium}=require(process.env.IRINA_PLAYWRIGHT||'playwright');
const sharp=require(process.env.IRINA_SHARP||'sharp');
const fs=require('node:fs');
(async()=>{
 const b=await chromium.launch({executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe'});
 const c=await b.newContext();const base='https://drairinagonzalez.com';
 const publicData=JSON.parse(fs.readFileSync('docs/audit/2026-10-14b/public.json','utf8'));
 const social=[...new Set(publicData.rows.filter(r=>r.status===200).map(r=>r.og))];const og=[];
 for(const url of social){const r=await c.request.get(url);const bytes=await r.body();const m=await sharp(bytes).metadata();og.push({status:r.status(),format:m.format,width:m.width,height:m.height,bytes:bytes.length});}
 const index=await(await c.request.get(base+'/sitemap_index.xml')).text();const sitemaps=[];
 for(const m of index.matchAll(/<loc>([^<]+)<\/loc>/g)){const url=m[1];if(new URL(url).origin!==base)continue;const r=await c.request.get(url);const text=await r.text();sitemaps.push({path:new URL(url).pathname,status:r.status(),locations:[...text.matchAll(/<loc>([^<]+)<\/loc>/g)].map(m=>m[1])});}
 const checks=[];for(const route of ['/','/contacto/','/padecimientos/','/tratamientos/','/recursos/','/links/','/gracias/']){const r=await c.request.get(base+route,{maxRedirects:0});const text=await r.text();checks.push({route,status:r.status(),robots:(text.match(/<meta name="robots" content="([^"]+)"/)||[])[1],headers:Object.fromEntries(Object.entries(r.headers()).filter(([k])=>['strict-transport-security','x-content-type-options','x-frame-options','referrer-policy','permissions-policy','x-powered-by'].includes(k)))});}
 const redirects=[];for(const row of publicData.rows.filter(r=>r.route.startsWith('/sueno/')&&r.route!=='/sueno/')){const prefix=row.types.includes('MedicalCondition')?'padecimientos':'tratamientos';const slug=row.route.split('/')[2];const r=await c.request.get(base+'/'+prefix+'/'+slug+'/',{maxRedirects:0});redirects.push({route:'/'+prefix+'/'+slug+'/',status:r.status(),location:r.headers().location});}
 await b.close();fs.writeFileSync('docs/audit/2026-10-14b/support.json',JSON.stringify({og,sitemaps,checks,redirects},null,2)+'\n');console.log(JSON.stringify({og,sitemaps:sitemaps.map(s=>({path:s.path,status:s.status,count:s.locations.length})),checks:checks.map(c=>({route:c.route,status:c.status,robots:c.robots})),redirects}));
})().catch(e=>{console.error(e.name,e.message);process.exit(1)});
