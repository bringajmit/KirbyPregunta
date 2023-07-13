/** @type {import('tailwindcss').Config} */
module.exports = {
  purge: [
    './*.html',
    './*.php',
  ],
  theme: {
    fontFamily:{
      primary:'Irish Grover'
    },
    container:{
      padding:{
        DEFAULT: '15px'
      }
    },
    screens:{
      sm:'640px',
      md:'768px',
      lg:'960px',
      xl:'1140px',
    },
    extend: {},
  },
  plugins: [],
}