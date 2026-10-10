"""Panel reproducible desde PLAN.md: solo DONE suma; inglés se cuenta aparte."""
from pathlib import Path
from collections import Counter
import re

root = Path(__file__).resolve().parents[2]
rows = []
for line in (root / "PLAN.md").read_text(encoding="utf-8").splitlines():
    cells = [cell.strip() for cell in line.split("|")]
    if len(cells) > 5 and re.fullmatch(r"\d+\.\d+", cells[1]) and cells[3].isdigit():
        rows.append((cells[1], int(cells[3]), cells[4]))
es = [row for row in rows if not row[0].startswith("13.")]
total = sum(row[1] for row in es)
done = sum(row[1] for row in es if row[2] == "DONE")
states = Counter(row[2] for row in es)
lines = ["# PROGRESS — Cálculo verificable", "", "Generado desde `PLAN.md` con `python tools/audit/progress.py`.", "", "Solo DONE suma. REVIEW, DOING, TODO, OWNER y BLOCKED suman cero; inglés aparte.", "", f"Español: **{done}/{total} pesos = {done / total:.2%}** · {states['DONE']}/{len(es)} tareas DONE.", "", "| Estado | Tareas |", "|---|---:|"]
lines += [f"| {state} | {states[state]} |" for state in ["DONE", "REVIEW", "DOING", "TODO", "OWNER", "BLOCKED"]]
lines += ["", "| Fase | Peso DONE | Peso total |", "|---|---:|---:|"]
for phase in range(14):
    group = [row for row in rows if row[0].split(".")[0] == str(phase)]
    lines.append(f"| {phase}{' (EN, aparte)' if phase == 13 else ''} | {sum(r[1] for r in group if r[2] == 'DONE')} | {sum(r[1] for r in group)} |")
lines += ["", "Los DONE heredados conservan la evidencia referida por PLAN; este cálculo no equivale a certificar todos los gates. Auditoría actual y límites: `docs/AUDITORIA_CODEX_2026-10-10.md`. No se elevan tareas a DONE por terminar una revisión local.", ""]
(root / "PROGRESS.md").write_text("\n".join(lines), encoding="utf-8")
print(f"{done}/{total} pesos; {states['DONE']}/{len(es)} DONE; {done / total:.2%}")
