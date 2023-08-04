module.exports = {
  '*.js': ['prettier --write', 'eslint --fix'],
  '!(*-lock).{twig,css}': ['prettier --write'],
  '*.{jpeg,jpg,png,svg}': ['node imagemin.mjs'],
};
