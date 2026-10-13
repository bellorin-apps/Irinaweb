const vm=require('node:vm'),fs=require('node:fs'),assert=require('node:assert/strict');
const scripts=JSON.parse(fs.readFileSync('tmp/review-ga4.json'));
const rows=[];
for(const dnt of [false,true]){
 const listeners={},idle=[],loaded=[];
 const ctx={navigator:{doNotTrack:dnt?'1':'0'},location:{pathname:'/contacto/'},Date,document:{readyState:'loading',addEventListener:(n,f)=>listeners[n]=f,createElement:()=>({}),head:{appendChild:s=>loaded.push(s.src)}},requestIdleCallback:f=>idle.push(f)};
 ctx.window=ctx;ctx.addEventListener=(n,f)=>listeners[n]=f;vm.createContext(ctx);
 vm.runInContext(scripts['di-ga4'],ctx);vm.runInContext(scripts['di-events'],ctx);
 const click=n=>listeners.click({target:{closest:()=>({getAttribute:()=>n})}});
 if(dnt){let count=0;ctx.gtag=()=>count++;click('appointment_click');assert.equal(count,0);assert.equal(loaded.length,0);rows.push({dnt:true,blocked:true});}
 else{click('appointment_click');click('unknown');assert.equal(ctx.dataLayer.length,3);assert.equal(loaded.length,0);listeners.load();assert.equal(idle.length,1);idle[0]();assert.equal(loaded.length,1);assert.equal(ctx.dataLayer[2][0],'event');assert.deepEqual(JSON.parse(JSON.stringify(ctx.dataLayer[2][2])),{page_path:'/contacto/'});rows.push({dnt:false,queuedBeforeLoad:true,idleLoadsOnce:true,unknownBlocked:true,noPersonalData:true});}
}
fs.writeFileSync('docs/audit/2026-10-12/ga4-fixtures.json',JSON.stringify(rows,null,2)+'\n');console.log(rows);
