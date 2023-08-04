const mix = require('laravel-mix');

require('dotenv').config();

// paths
const paths = {
  src: 'static',
  dist: `web/themes/${process.env.PRODUCTION_NAME}/assets`,
};

mix
  .setPublicPath(paths.dist)
  .options({
    processCssUrls: false,
  })
  .postCss(`${paths.src}/styles/style.css`, 'styles')
  .postCss(`${paths.src}/styles/editor-style.css`, 'styles')
  .js(`${paths.src}/scripts/script.js`, 'scripts')
  .copyDirectory(`${paths.src}/images`, `${paths.dist}/images`)
  .sourceMaps(false, 'inline-cheap-module-source-map')
  .browserSync({
    proxy: 'localhost:' + process.env.LOCAL_SERVER_PORT,
    reloadOnRestart: true,
    open: false,
    notify: false,
    ghostMode: false,
    scrollProportionally: false,
    files: [
      `web/themes/${process.env.PRODUCTION_NAME}/**/*`,
      'web/mu-plugins/**/*',
    ],
  });

if (mix.inProduction()) {
  mix.version();
}
