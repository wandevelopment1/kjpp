// tailwind.config.js
export default {
  prefix: 'tw-',
  content: [
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.js",
    "./resources/js/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: "#ff5b2e",
      },
    },
  },
  plugins: [],
}
