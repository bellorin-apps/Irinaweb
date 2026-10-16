"""Datos del consultorio para los builds de contenido (5.3, Q-042): una sola fuente para la dirección y el contacto.

Deben coincidir con los ajustes del plugin (wp-admin › Dra. Irina › Consultorio), que alimentan el schema, la barra móvil, el pie,
el formulario y los enlaces de WhatsApp. tools/audit/nap-check.py compara este archivo con el schema publicado y avisa si un build
vuelve a escribir la dirección o los teléfonos a mano. Si el consultorio cambia: editar aquí y en Consultorio, regenerar los builds
y cargar con setup-pages.sh / setup-home.sh.
"""

CENTRO = "CAB Medical Headquarters"
CALLE = "Av. Paseo de los Leones 2341"
INTERIOR = "Consultorio 6, Piso 2"
COLONIA = "Cumbres 2.º Sector"
CP = "64610"
CIUDAD = "Monterrey"
ESTADO = "Nuevo León"
ZONA = "Cumbres"  # forma corta de la colonia en los textos de marca
TELEFONO = "+528115695744"
WHATSAPP = "+528123685381"

# Formatos usados en los textos aprobados (no cambiar sin revisar el texto donde aparecen).
DIRECCION_CORTA = f"{CENTRO}, {CALLE}, {INTERIOR}"  # Contacto, entradilla
DIRECCION_COMPLETA = f"{DIRECCION_CORTA}, {COLONIA}, {CIUDAD}"  # FAQ: dónde está el consultorio
DIRECCION_SEO = f"{CENTRO}, {CALLE}, {COLONIA}, {CIUDAD}"  # descripción SEO de Contacto
STREET_ADDRESS = f"{CALLE}, {INTERIOR}, {COLONIA}"  # como lo emite el schema (PostalAddress.streetAddress)
