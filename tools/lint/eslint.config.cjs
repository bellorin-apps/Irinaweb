// PLAN 5.5: reglas recomendadas de ESLint para el JS del tema (scripts clásicos sin módulos ni dependencias).
const js = require('@eslint/js');
const globals = require('globals');

module.exports = [
  js.configs.recommended,
  {
    files: ['**/*.js'],
    languageOptions: {
      ecmaVersion: 2020,
      sourceType: 'script',
      globals: { ...globals.browser, google: 'readonly' },
    },
  },
];
