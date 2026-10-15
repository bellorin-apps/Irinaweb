# LAUNCH_CHECKLIST — drairinagonzalez.com

Actualizado: 2026-10-15 · Claude cloud. El sitio está en producción desde el go-live de la portada (D-034) y se ha ido publicando por tramos con aprobación médica (D-078/D-082). Esta lista confirma que el sitio corto (D-076) está listo para considerarse lanzado (PLAN 11.5) y deja a la vista lo que depende de José (CHECKPOINT 5, 11.6).

Leyenda: ✅ hecho y verificado · ⏳ pendiente · 👤 depende de José

## Contenido y aprobación

- ✅ Páginas públicas: Inicio, Conóceme, Otorrino, Sueño, Primera consulta, Contacto, Preguntas frecuentes, 3 legales, 5 fichas de sueño (15 URL).
- ✅ Contenido clínico aprobado por la Dra. en su app (ORL, Sueño, 26 fichas; se publican 5 por decisión de José, D-082). Las 21 restantes en borrador, sin enlaces (404).
- ✅ Textos de marca y títulos dictados por José respetados (D-049).
- ✅ Aviso de privacidad, aviso médico y términos publicados; Cloudflare mencionado en lenguaje sencillo.
- 👤 Revisión legal/regulatoria final (COFEPRIS, privacidad) — PLAN 8.6.

## Técnica

- ✅ 15/15 URL 200, 404 propia, sin enlaces rotos, sin desbordes en 5 anchos, sin errores JS (docs/audit/2026-10-15/qa-cierre.md).
- ✅ HTTPS, HSTS y cabeceras de seguridad; versiones ocultas; enumeración de usuarios cerrada; XML-RPC desactivado (SECURITY.md).
- ✅ 2FA en WordPress, Hostinger y Google; sin contraseñas de aplicación activas.
- ✅ Formulario con datos mínimos, antispam y captcha; SMTP operativo desde el 10-oct.
- 👤 Una prueba real del formulario con «PRUEBA» en el mensaje (llega al consultorio).
- ✅ Respaldo automático antes de cada despliegue y copias del hosting.

## SEO

- ✅ Títulos y descripciones dentro de límites; canónicas propias; index en todo lo público.
- ✅ Sitemaps de Rank Math solo con lo publicado (sin archivos 404 ni recursos); reenviado en Search Console.
- ✅ Schema validado sin errores ni advertencias (Physician+Person, ProfilePage, ContactPage, FAQPage, MedicalWebPage, BreadcrumbList).
- ✅ 301 del dominio aparcado y de las rutas de tipo a /sueno/{slug}/.
- ✅ Search Console: portada, Conóceme, Primera consulta y Contacto indexadas; motivos de no indexación revisados sin problemas.
- 👤 Google Business Profile y Doctoralia alineados con el NAP del sitio (PLAN 9.5; horario de GBP).

## Rendimiento y accesibilidad

- ✅ Lighthouse «antes/después» (PERFORMANCE.md): CLS de portada 0,236 → 0,001; escritorio a menos de la mitad de peso con fotos q95.
- ✅ Accesibilidad: axe WCAG 2.2 AA sin violaciones reales; Lighthouse a11y 100 en las páginas medidas; menú con teclado.
- ⏳ Lector de pantalla manual (VoiceOver/TalkBack) y navegadores Safari/Firefox reales (PLAN 11.2).

## Medición

- ✅ GA4 con carga diferida, exclusión de editores y eventos (WhatsApp, llamada, cómo llegar, Doctoralia, formulario).
- ⏳ Día 7 y día 30 tras el cierre: revisar Search Console y GA4 (PLAN 12.3, MAINTENANCE.md).

## Para dar el lanzamiento por cerrado (CHECKPOINT 5)

1. 👤 José: prueba del formulario, GBP/Doctoralia y revisión legal (o aceptar que quedan como mejora continua).
2. ⏳ Pasada en iPhone (Safari) y Firefox.
3. 👤 José aprueba esta lista.
