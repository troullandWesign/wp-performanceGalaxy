const { merge } = require('webpack-merge');
const Common = require('./webpack.common.js');

const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');

module.exports = merge(Common(false), {
  mode: 'development',
  devtool: 'inline-source-map',
  plugins: [
    new BrowserSyncPlugin({
      proxy: 'http://perfomancegalaxy.local',
      notify: false,
      open: true
    }),
    new CleanWebpackPlugin({
      verbose: true,
      cleanOnceBeforeBuildPatterns: [
        './js/*',
        './css/*',
      ]
    })
  ]
});