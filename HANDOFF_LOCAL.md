# HANDOFF — Sesión local en la PC de José (acceso SSH como Sensia)

Pegar este encargo en una sesión de Claude Code abierta en una carpeta nueva de la PC (por ejemplo `C:\Users\bellg\Documents\Irinaweb`):

```
Clona https://github.com/bellorin-apps/Irinaweb rama ccr-6254502d-xly8ww en esta carpeta.
Lee MASTER_PROMPT.md, STATUS.md, NEXT.md, BATON.md y docs/DEPLOY.md.
Copia tools/deploy/env.example.sh a tools/deploy/env.sh y ajusta SSH_KEY a la clave del alias `gibelab` de tu ~/.ssh/config (misma cuenta Hostinger u855694717). No pegues claves en el chat.
Ejecuta en orden, desde Git Bash o WSL:
  bash tools/deploy/inventory.sh        (solo lectura; guarda docs/audit/inventory-*.txt)
  bash tools/deploy/deploy-briefing.sh  (sube /briefing/ y muestra la URL con token)
Haz commit del inventario y push a la misma rama. Envía a la sesión cloud "Sitio web" (session_01P2pMHfo6sjjDF1P9dmdq9w) un mensaje con la URL del briefing y un resumen del inventario, sin credenciales.
```

Después, cada vez que la sesión cloud lo pida: `git pull` y `bash tools/deploy/deploy-code.sh`.
