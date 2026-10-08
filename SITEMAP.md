# SITEMAP — Arquitectura de información

Versión 0.1 · 2026-10-07 · Estado: REVIEW. Deriva de `KEYWORDS.md`. Cada URL tiene una sola intención. Las páginas de servicio se publican únicamente si la Dra. confirma OFRECE.

## 1. Convenciones

- Dominio canónico: `https://drairinagonzalez.com/` (sin `www`, D-022). Redirección 301 de `www`, de `http` y de `otorrino-monterrey.com`.
- Slugs en minúsculas, sin acentos ni eñe, con guiones. Barra final siempre.
- Un pilar por especialidad; las páginas hijas cuelgan del pilar al que pertenecen.
- `noindex`: gracias, 404, búsqueda, staging, páginas legales secundarias si no aportan valor de búsqueda (el aviso de privacidad sí se indexa).

## 2. Árbol

```
/                                   Home · otorrinolaringólogo en Monterrey
├── /dra-irina-gonzalez-saez/        Página de entidad (persona, credenciales, trayectoria)
├── /otorrinolaringologia/           Pilar ORL
│   ├── /padecimientos/              Hub de padecimientos (índice por zona: oído, nariz, garganta)
│   │   ├── /padecimientos/otitis/
│   │   ├── /padecimientos/tapon-de-cerumen/
│   │   ├── /padecimientos/hipoacusia/
│   │   ├── /padecimientos/tinnitus/
│   │   ├── /padecimientos/vertigo/
│   │   ├── /padecimientos/rinitis/
│   │   ├── /padecimientos/sinusitis/
│   │   ├── /padecimientos/desviacion-de-tabique-nasal/
│   │   ├── /padecimientos/polipos-nasales/
│   │   ├── /padecimientos/epistaxis/
│   │   ├── /padecimientos/amigdalitis/
│   │   └── /padecimientos/reflujo-laringofaringeo/
│   └── /tratamientos/               Hub de tratamientos y procedimientos
│       ├── /tratamientos/septoplastia/
│       ├── /tratamientos/turbinoplastia/
│       ├── /tratamientos/amigdalas-y-adenoides/
│       ├── /tratamientos/cirugia-endoscopica-nasal/        (si OFRECE)
│       └── /tratamientos/rinoplastia/                      (RESERVADA, OD-009: borrador hasta que la Dra. la realice de forma independiente)
├── /sueno/                          Pilar Sueño (sub-marca visual)
│   ├── /sueno/ronquido/
│   ├── /sueno/apnea-obstructiva-del-sueno/
│   ├── /sueno/estudio-del-sueno/
│   ├── /sueno/cpap/                                         (si OFRECE seguimiento)
│   ├── /sueno/cirugia-de-ronquido-y-apnea/                  (faringoplastia como H2)
│   ├── /sueno/dispositivo-de-avance-mandibular/             (si OFRECE o coordina)
│   └── /sueno/endoscopia-de-sueno/                          (si OFRECE)
├── /primera-consulta/               Qué esperar, qué llevar, cuánto dura, cómo agendar
├── /preguntas-frecuentes/           Preguntas de servicio (horario, seguros, pagos, en línea)
├── /recursos/                       Centro de recursos para pacientes (artículos y guías)
│   └── /recursos/{slug}/
├── /links/                          Enlaces para redes (se conserva, noindex)
├── /contacto/                       Ubicación, mapa, cómo llegar, estacionamiento, horario, formulario
├── /gracias/                        Confirmación de formulario (noindex)
├── /aviso-de-privacidad/
├── /terminos-de-uso/
├── /politica-de-cookies/            (solo si se usan cookies no esenciales)
└── /aviso-medico/                   Disclaimer: contenido educativo, no sustituye consulta, no es canal de urgencias
```

Páginas que NO existen a propósito: `/otorrino-cumbres/`, `/otorrino-san-pedro/`, `/servicios/` como página única gigante, `/blog/` genérico (se usa `/recursos/`), `/otorrinolaringologa/` como duplicado de género.

## 3. Jerarquía de navegación

**Header (desktop):** Logo · Otorrinolaringología (menú: Padecimientos, Tratamientos) · Sueño (menú: Ronquido, Apnea, Estudio del sueño, Cirugía) · Dra. Irina · Primera consulta · Contacto · [Agendar por WhatsApp]

**Header (móvil):** Logo · menú · botón WhatsApp. Barra inferior: WhatsApp · Llamar · Cómo llegar.

**Footer:** logo y nombre; especialidad; NAP; horario; enlaces principales; redes oficiales; Doctoralia; aviso de privacidad; aviso médico; copyright; datos regulatorios que apliquen (cédulas, si la Dra. autoriza).

## 4. Breadcrumbs

`Inicio › Sueño › Apnea obstructiva del sueño` · `Inicio › Otorrinolaringología › Padecimientos › Sinusitis` · `Inicio › Dra. Irina González Sáez`. Emitidos como `BreadcrumbList`.

## 5. Enlazado interno (clusters)

- Cada padecimiento enlaza a: su pilar, sus tratamientos relacionados, la página de la Dra. (autora/revisora), primera consulta y CTA.
- Cada tratamiento enlaza a: los padecimientos que resuelve, el estudio diagnóstico previo (si aplica) y la página de la Dra.
- Sueño: Ronquido ↔ Apnea ↔ Estudio del sueño → (CPAP | Cirugía | Dispositivo) → Dra. Irina → WhatsApp.
- ORL: Desviación de tabique → Septoplastia; Rinitis/Sinusitis → Turbinoplastia / Cirugía endoscópica; Amigdalitis → Amígdalas y adenoides → Ronquido en niños (puente a Sueño).
- Home enlaza a los dos pilares, a 6 motivos de consulta frecuentes, a la Dra., a primera consulta y a contacto.

## 6. Mapa de redirecciones

Se completa en Fase 1.4 cuando se conozcan las URLs existentes en el WordPress actual y en Search Console. Regla: `OLD → NEW` con 301 directo, sin cadenas, sin redirigir todo a Home.

## 7. Estado de cada URL

| URL | Tipo WP | Estado |
|---|---|---|
| `/` | Página (Elementor, datos del core) | TODO |
| `/dra-irina-gonzalez-saez/` | Página (template entidad) | TODO |
| `/otorrinolaringologia/`, `/sueno/` | Página pilar | TODO |
| `/padecimientos/*`, `/tratamientos/*`, `/sueno/*` | CPT `condicion` / `tratamiento` con prefijo de URL por área | TODO · cada una OFRECE/NO OFRECE por confirmar |
| `/primera-consulta/`, `/preguntas-frecuentes/`, `/contacto/`, legales | Página | TODO |
| `/recursos/*` | CPT `recurso` (o `post` renombrado) | TODO · fase posterior |
