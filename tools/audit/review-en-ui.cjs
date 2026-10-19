// Exercise real theme loader with translated attributes; third party blocked, no activation anywhere.
const fs = require('node:fs');
const {chromium} = require(process.env.IRINA_PLAYWRIGHT || 'playwright');
(async()=>{
 const browser=await chromium.launch({executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe'});
 const page=await browser.newPage();let requests=0;
 await page.route('**/*',r=>{requests++;return r.abort();});
 await page.setContent(fs.readFileSync('tmp/doctoralia-fixture.html','utf8'));
 const samples=JSON.parse(fs.readFileSync('docs/audit/2026-10-19/builds.json','utf8')).catalogSample;
 await page.evaluate(values=>{
   const box=document.querySelector('[data-di-doctoralia]');
   box.setAttribute('data-di-status-loading',values['Cargando opiniones de Doctoralia…']);
   box.setAttribute('data-di-status-error',values['No se han podido cargar las opiniones. Puedes reintentar o abrir el enlace de Doctoralia.']);
   window.IntersectionObserver=undefined;
 },samples);
 await page.addScriptTag({content:fs.readFileSync('wp-content/themes/irina-gonzalez/assets/js/main.js','utf8')});
 const before=requests;
 await page.locator('[data-di-doctoralia-load]').focus();await page.keyboard.press('Enter');
 await page.waitForFunction(()=>document.querySelector('[role=status]').textContent.startsWith('The reviews could not'));
 const status=await page.locator('[role=status]').innerText();
 if(before!==0||requests!==1)throw new Error('Unexpected third-party request count');
 await page.setContent('<section class="di-section--doctor-bg"><ul class="di-creds"><li>One</li><li>Two</li><li>Three</li></ul></section><form class="di-form" action="https://example.test/synthetic" novalidate><input name="di_name" required><input name="di_consent" type="checkbox" required><p class="di-form__status" hidden></p><span class="di-form__label">Send</span><span class="di-form__sending" hidden>Sending</span></form>');
 await page.evaluate(values=>{
   document.querySelector('.di-creds').setAttribute('data-label',values['Credencial %1$d de %2$d']);
   const form=document.querySelector('form');
   form.setAttribute('data-error-fields',values['Revisa los campos marcados.']);
   form.setAttribute('data-error-consent',values['Necesitamos tu consentimiento para contactarte.']);
   form.setAttribute('data-error-send',values['No pudimos enviar tu mensaje. Escríbenos por WhatsApp.']);
   window.syntheticFetchCalls=0;
   window.fetch=()=>{window.syntheticFetchCalls++;return Promise.reject(new Error('synthetic network failure'));};
 },samples);
 await page.addScriptTag({content:fs.readFileSync('wp-content/themes/irina-gonzalez/assets/js/main.js','utf8')});
 const label=await page.locator('.di-creds__dots button').first().getAttribute('aria-label');
 if(label!=='Credential 1 of 3')throw new Error('Credential label not localized');
 const submit=()=>page.locator('form').evaluate(f=>f.dispatchEvent(new Event('submit',{bubbles:true,cancelable:true})));
 await submit();const fieldsStatus=await page.locator('.di-form__status').innerText();
 if(fieldsStatus!==samples['Revisa los campos marcados.'])throw new Error('Fields message not localized');
 await page.locator('[name=di_name]').fill('Synthetic');await submit();
 const consentStatus=await page.locator('.di-form__status').innerText();
 if(consentStatus!==samples['Necesitamos tu consentimiento para contactarte.'])throw new Error('Consent message not localized');
 await page.locator('[name=di_consent]').check();await submit();
 await page.waitForFunction(()=>document.querySelector('.di-form__status').textContent.startsWith('We could not'));
 const sendStatus=await page.locator('.di-form__status').innerText();
 const fakeCalls=await page.evaluate(()=>window.syntheticFetchCalls);
 if(fakeCalls!==1||requests!==1)throw new Error('Form request escaped synthetic stub');
 await browser.close();
 fs.writeFileSync('docs/audit/2026-10-19/ui.json',JSON.stringify({requestsBeforeClick:before,requestsAfterClick:requests,status,label,fieldsStatus,consentStatus,sendStatus,syntheticFetchCalls:fakeCalls,limits:'Real JS with synthetic template helpers and English attributes from compiled GNU catalog. Form submit events use a stubbed fetch, no real form/network/SMTP. No real Doctoralia, cookies, patient data, activation, WordPress gettext stack or English page preview.'},null,2)+'\n');
 console.log('EN status UI passes with zero requests before click.');
})().catch(e=>{console.error(e.name,e.message);process.exit(1);});
