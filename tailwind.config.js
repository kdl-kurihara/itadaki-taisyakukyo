const plugin = require('tailwindcss/plugin');
require('dotenv').config();

module.exports = {
  content: [
    `./web/themes/${process.env.PRODUCTION_NAME}/functions.php`,
    `./web/themes/${process.env.PRODUCTION_NAME}/templates/**/*.twig`,
    './static/scripts/**/*.js',
  ],
  theme: {
    container: {
      center: true,
      padding: '1.25rem',
    },
    screens: {
      sm: { max: '559px' },
      md: '560px',
      lg: 'calc(1000px + 1.25rem * 2)',
    },
    extend: {
      colors: {
        primary: {
          DEFAULT: '#d91817',
        },
        body: {
          base: '#eaeaea',
          text: '#242424',
        },
      },
      fontFamily: {
        body: ['Noto Sans JP', 'sans-serif'],
        display: ['Red Hat Display', 'sans-serif'],
      },
      listStyleType: {
        alpha: 'lower-alpha',
      },
      typography: ({ theme }) => ({
        DEFAULT: {
          css: {
            'max-width': theme('container.screens.lg'),
            '--tw-prose-links': theme('colors.primary.DEFAULT'),
          },
        },
      }),
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
    plugin(({ addUtilities, theme }) => {
      addUtilities({
        '.border-accent': {
          'border-image': `linear-gradient(
            90deg,
            ${theme('colors.primary.DEFAULT')} ${theme('spacing.20')},
            ${theme('colors.white')} ${theme('spacing.20')})`,
          'border-image-slice': '1',
        },
      });
    }),
  ],
};
