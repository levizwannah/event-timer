/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#1E40AF', // blue-800
          light: '#2563EB',   // blue-600
          dark: '#1E3A8A',    // blue-900
        },
      },
    },
  },
  plugins: [],
}
