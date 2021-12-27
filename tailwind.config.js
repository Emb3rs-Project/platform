const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
  content: [
    "./vendor/laravel/jetstream/**/*.blade.php",
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: [
          'Inter', ...defaultTheme.fontFamily.sans
        ],
      },
      spacing: {
        'content': '70vh',
      },
    },
  },

  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography')
  ],
};
