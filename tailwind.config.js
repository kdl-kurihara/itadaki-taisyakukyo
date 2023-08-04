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
  plugins: [require('@tailwindcss/typography')],
};
