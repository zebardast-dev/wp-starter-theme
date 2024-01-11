var path = require('path');

module.exports = {
  entry: './assets/js/index.js',
  entry: {
    main: './assets/js/index.js',
  },
  output: {
    filename: '[name].min.js',
    path: path.resolve(__dirname, 'assets/dist/js')
  },
};
