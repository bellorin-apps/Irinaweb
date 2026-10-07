# DECISIONS — Registro de decisiones

Formato: ID · Fecha · Decisión · Alternativas · Motivo · Estado (Vigente / Sustituida / OWNER_DECISION_REQUIRED)

| ID | Fecha | Decisión | Alternativas consideradas | Motivo | Estado |
|---|---|---|---|---|---|
| D-001 | 2026-10-07 | El documento constitucional se llama `MASTER_PROMPT.md` (no `PROJECT_CHARTER.md`) | PROJECT_CHARTER.md | Es el nombre preferido en el mandato y no existe arquitectura documental previa | Vigente |
| D-002 | 2026-10-07 | Avance calculado por pesos de tarea definidos en `PLAN.md` (1/2/3/5), no por conteo simple | Conteo simple; horas | Exigido por §150; evita inflar porcentaje con tareas pequeñas | Vigente |
| D-003 | 2026-10-07 | Arquitectura objetivo: Hello Elementor + child theme `irina-gonzalez` + plugin `dra-irina-core`; Elementor solo como capa editorial | Headless; theme comercial; todo en functions.php | Mandato §95–§101 | Vigente |
| D-004 | 2026-10-07 | Datos personales no profesionales del Drive (identificaciones, CURP, RFC, domicilio particular, correos personales) quedan **fuera** del repositorio y de la web | Documentarlos "por si acaso" | Minimización de datos; no son necesarios para el sitio | Vigente |
| D-005 | 2026-10-07 | Datos de contacto/dirección extraídos de tarjetas de presentación se registran como "POR CONFIRMAR" aunque coincidan con Doctoralia | Tratarlos como confirmados | §3: todo dato publicable requiere confirmación expresa de la Dra. | Vigente |
| D-006 | 2026-10-07 | Para operar el WordPress desde el entorno cloud se usará la conexión WPVibe (REST + WP-CLI emulado) como herramienta de desarrollo, con su plugin marcado para retirar en producción si no aporta valor | SSH/SFTP directo; solo panel del hosting | Es el acceso disponible sin pedir credenciales; evita pegar contraseñas en el chat | Vigente — pendiente autorización |
| D-007 | 2026-10-07 | Stack de plugins candidato (a validar en Fase 3): Rank Math (SEO), Fluent Forms, FluentSMTP, LiteSpeed Cache si el servidor es LiteSpeed, Safe SVG, ACF (si hay licencia Pro) | Yoast/SEOPress; Elementor Forms; WP Rocket | Coherencia con el stack que el propietario ya opera en idenbauer.com y reglas de §32 | Propuesta (decisión en 3.6) |
| D-008 | 2026-10-07 | La sesión fotográfica de 2022 NO se usa en el sitio | Reutilizarla | Es una sesión personal (maternidad), monocroma, sin contexto clínico | Vigente — requiere nueva sesión |

## Decisiones pendientes del propietario (OWNER_DECISION_REQUIRED)

| ID | Tema | Opciones | Recomendación |
|---|---|---|---|
| OD-001 | Género gramatical de la especialidad en la web | "Otorrinolaringóloga" / "Otorrinolaringólogo" (como figura en logo y tarjetas) / "Médico Otorrinolaringólogo" | Usar "Otorrinolaringóloga" en textos y SEO (coincide con búsquedas reales y con la persona) y mantener el logotipo tal cual si la Dra. lo prefiere así; confirmar con ella |
| OD-002 | Nombre del centro de recursos | "Recursos" / "Guías" / "Salud ORL y sueño" / "Biblioteca para pacientes" | Decidir en Fase 3 con keyword research; recomendación provisional: "Recursos para pacientes" en `/recursos/` |
| OD-003 | Canal principal de agenda | Doctoralia / WhatsApp / teléfono / formulario propio | Investigar flujo real en discovery; recomendación provisional: WhatsApp + Doctoralia como secundario, formulario mínimo de respaldo |
