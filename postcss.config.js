/** @type {import('postcss-load-config').Config} */
const loadConfig = require('tailwindcss/loadConfig');
const resolveConfig = require('tailwindcss/resolveConfig');
const twConfig = resolveConfig(
  loadConfig(`${process.env.PWD}/tailwind.config.js`)
);
const colors = {};
for (const i of Object.keys(twConfig.theme.colors)) {
  for (const j of Object.keys(twConfig.theme.colors[i])) {
    colors[`color-${i}${j !== 'DEFAULT' ? `-${j}` : ''}`] =
      twConfig.theme.colors[i][j];
  }
}
const lineHeight = {};
for (const i of Object.keys(twConfig.theme.lineHeight)) {
  lineHeight[`leading-${i}`] = twConfig.theme.lineHeight[i];
}

const config = {
  plugins: [
    require('postcss-simple-vars')({
      variables: { ...colors, ...lineHeight },
    }),
    require('tailwindcss/nesting'),
    require('tailwindcss'),
    require('postcss-inline-svg')({
      removeFill: true,
    }),
    require('autoprefixer'),
  ],
};

module.exports = config;
