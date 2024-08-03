var path = require('path');

module.exports = {
  	entry: {
		main: './assets/js/index.js',
	},
	output: {
		filename: '[name].min.js',
		path: path.resolve(__dirname, 'assets/dist/js')
	},
	mode: 'development',
};
