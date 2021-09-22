const path = require('path');

module.exports = {
  entry: './js/bundle.js',
  mode: 'production',
  output: {
    filename: 'bundle.js',
    path: path.resolve(__dirname, 'js/dist'),
  },
};