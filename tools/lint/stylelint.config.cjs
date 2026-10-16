// PLAN 5.5: stylelint-config-recommended detecta errores (sintaxis, propiedades o unidades inválidas, bloques vacíos, duplicados), no estilo.
module.exports = {
  extends: ['stylelint-config-recommended'],
  rules: {
    // El orden de la cascada es intencional (variantes por contexto tras la regla base); la regla da falsos positivos aquí.
    'no-descending-specificity': null,
    // Selectores repetidos a propósito: ajustes posteriores del propietario añadidos como capas; fusionarlos cambiaría la cascada.
    'no-duplicate-selectors': null,
    // `clip` se mantiene en .screen-reader-text por compatibilidad (patrón de WordPress), junto a clip-path.
    'property-no-deprecated': [true, { ignoreProperties: ['clip'] }],
  },
};
