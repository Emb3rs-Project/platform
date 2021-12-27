const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
  content: [
    "./vendor/laravel/jetstream/**/*.blade.php",
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.js",
    "./resources/js/**/*.vue",
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: [
          'Inter',
          ...defaultTheme.fontFamily.sans
        ],
      },
    },
  },

  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography')
  ],
};
