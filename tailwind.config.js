/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './*.php',
    './inc/**/*.php',
    './page-templates/**/*.php',
    './template-parts/**/*.php',
    './assets/js/**/*.js'
  ],
  theme: {
    extend: {
      colors: {
        verde: '#0d5257',
        dourado: '#bc945b',
        bege: '#eee8e5',
        branco: '#ffffff',
        preto: '#000000',
      },
      fontFamily: {
        display: ['OldStandardTT', 'serif'],
        body: ['Commissioner', 'sans-serif'],
      },
    },
  },
  plugins: [],
}
