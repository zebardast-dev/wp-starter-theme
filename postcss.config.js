const cssnano = require('cssnano')
// postcss.config.js
module.exports = {
    plugins: {
      'postcss-import': {},
      'tailwindcss': {},
      'tailwindcss/nesting': {},
      'autoprefixer': {},
      'cssnano': {
          preset: 'default'
      }
      
    }
  }