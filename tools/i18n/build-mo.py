#!/usr/bin/env python3
"""Genera los catálogos en_US (D-095): .po legible y .mo binario para el tema y el plugin, desde tools/i18n/strings_en.py.

Salida:
  wp-content/themes/irina-gonzalez/languages/en_US.po|.mo        (load_theme_textdomain con la carpeta del tema)
  wp-content/plugins/dra-irina-core/languages/dra-irina-core-en_US.po|.mo
Comprueba que cada cadena traducida existe en el código (si no, avisa: texto cambiado o cadena sobrante).
"""
import pathlib, re, struct, sys

sys.dont_write_bytecode = True
root = pathlib.Path(__file__).resolve().parents[2]
sys.path.insert(0, str(root / "tools/i18n"))
from strings_en import PLUGIN, THEME  # noqa: E402

CALL = re.compile(r"""(?:__|_e|esc_html__|esc_html_e|esc_attr__|esc_attr_e)\(\s*'((?:[^'\\]|\\.)*)'\s*,\s*'([a-z-]+)'""")


def source_strings(base: pathlib.Path, domain: str) -> set:
    found = set()
    for php in base.rglob("*.php"):
        if "vendor" in php.parts or "node_modules" in php.parts:
            continue
        for msg, dom in CALL.findall(php.read_text(encoding="utf-8")):
            if dom == domain:
                found.add(msg.replace("\\'", "'"))
    return found


def po_escape(s: str) -> str:
    return s.replace("\\", "\\\\").replace('"', '\\"').replace("\n", "\\n")


def write(catalog: dict, domain: str, po: pathlib.Path, mo: pathlib.Path) -> None:
    po.parent.mkdir(parents=True, exist_ok=True)
    head = ("Project-Id-Version: drairinagonzalez.com\nLanguage: en_US\nMIME-Version: 1.0\n"
            "Content-Type: text/plain; charset=UTF-8\nContent-Transfer-Encoding: 8bit\nPlural-Forms: nplurals=2; plural=(n != 1);\n"
            f"X-Domain: {domain}\n")
    lines = ['# Generado por tools/i18n/build-mo.py desde tools/i18n/strings_en.py (D-095). No editar a mano.', 'msgid ""', f'msgstr "{po_escape(head)}"', ""]
    for k in sorted(catalog):
        lines += [f'msgid "{po_escape(k)}"', f'msgstr "{po_escape(catalog[k])}"', ""]
    po.write_text("\n".join(lines), encoding="utf-8")
    entries = sorted([("", head)] + list(catalog.items()), key=lambda kv: kv[0].encode("utf-8"))
    ids = [k.encode("utf-8") for k, _ in entries]
    strs = [v.encode("utf-8") for _, v in entries]
    n = len(entries)
    o_ids, o_strs = 28, 28 + n * 8
    start = o_strs + n * 8
    id_tab, str_tab, data = [], [], b""
    for b in ids:
        id_tab.append((len(b), start + len(data)))
        data += b + b"\0"
    for b in strs:
        str_tab.append((len(b), start + len(data)))
        data += b + b"\0"
    out = struct.pack("<7I", 0x950412DE, 0, n, o_ids, o_strs, 0, 0)
    out += b"".join(struct.pack("<2I", *t) for t in id_tab) + b"".join(struct.pack("<2I", *t) for t in str_tab) + data
    mo.write_bytes(out)


problems = 0
for catalog, domain, base, po, mo in [
    (THEME, "irina-gonzalez", root / "wp-content/themes/irina-gonzalez", "languages/en_US.po", "languages/en_US.mo"),
    (PLUGIN, "dra-irina-core", root / "wp-content/plugins/dra-irina-core", "languages/dra-irina-core-en_US.po", "languages/dra-irina-core-en_US.mo"),
]:
    have = source_strings(base, domain)
    for k in catalog:
        if k not in have:
            problems += 1
            print(f"AVISO {domain}: «{k[:70]}» no aparece en el código")
    write(catalog, domain, base / po, base / mo)
    print(f"{domain}: {len(catalog)} cadenas → {po}, {mo}")
sys.exit(1 if problems else 0)
