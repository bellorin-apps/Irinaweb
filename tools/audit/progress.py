"""Panel reproducible desde PLAN.md: solo DONE suma; inglés se cuenta aparte. DESCOPED (fuera de alcance por decisión del propietario,
D-076) no entra en el total y se informa aparte."""
from pathlib import Path
from collections import Counter
import re

root = Path(__file__).resolve().parents[2]
rows = []
for line in (root / "PLAN.md").read_text(encoding="utf-8").splitlines():
    cells = [cell.strip() for cell in line.split("|")]
    if len(cells) > 5 and re.fullmatch(r"\d+\.\d+", cells[1]) and cells[3].isdigit():
        rows.append((cells[1], int(cells[3]), cells[4]))
es_all = [row for row in rows if not row[0].startswith("13.")]
es = [row for row in es_all if row[2] != "DESCOPED"]
descoped = [row for row in es_all if row[2] == "DESCOPED"]
total = sum(row[1] for row in es)
done = sum(row[1] for row in es if row[2] == "DONE")
states = Counter(row[2] for row in es)
lines = ["# PROGRESS — Cálculo verificable", "", "Generado desde `PLAN.md` con `python tools/audit/progress.py`.", "", "Solo DONE suma. REVIEW, DOING, TODO, OWNER y BLOCKED suman cero; inglés aparte. DESCOPED (fuera de alcance por decisión del propietario) no entra en el total.", "", f"Español: **{done}/{total} pesos = {done / total:.2%}** · {states['DONE']}/{len(es)} tareas DONE.", "", f"Fuera de alcance (DESCOPED): {len(descoped)} tareas, {sum(r[1] for r in descoped)} pesos: " + (", ".join(r[0] for r in descoped) or "ninguna") + ".", "", "| Estado | Tareas |", "|---|---:|"]
lines += [f"| {state} | {states[state]} |" for state in ["DONE", "REVIEW", "DOING", "TODO", "OWNER", "BLOCKED"]]
lines += ["", "| Fase | Peso DONE | Peso total |", "|---|---:|---:|"]
for phase in range(14):
    group = [row for row in rows if row[0].split(".")[0] == str(phase)]
    lines.append(f"| {phase}{' (EN, aparte)' if phase == 13 else ''} | {sum(r[1] for r in group if r[2] == 'DONE')} | {sum(r[1] for r in group if r[2] != 'DESCOPED')} |")
lines += ["", "Los DONE conservan la evidencia referida por PLAN; este cálculo no equivale a certificar todos los gates. Auditorías y límites: `QA.md`, `BATON.md`, `docs/AUDITORIA_CODEX_2026-10-10.md` y `docs/REVISION_2026-10-12.md`. La revisión documental no certifica staging, despliegue ni aprobación clínica.", ""]
(root / "PROGRESS.md").write_text("\n".join(lines), encoding="utf-8")
print(f"{done}/{total} pesos; {states['DONE']}/{len(es)} DONE; {done / total:.2%}")
