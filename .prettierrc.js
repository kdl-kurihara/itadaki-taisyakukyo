module.exports = {
  singleQuote: true,
  semi: true,
  trailingComma: 'es5',
  bracketSpacing: true,
  bracketSameLine: true,
  plugins: [
    require('prettier-plugin-twig-melody'),
    require('prettier-plugin-tailwindcss'),
  ],
  twigPrintWidth: 120,
};
