const { path } = require('path');
const { glob } = require('glob');

const { merge } = require('webpack-merge');
const Common = require('./webpack.common.js');

const TerserPlugin = require('terser-webpack-plugin');
const PurgecssPlugin = require('purgecss-webpack-plugin');
const purgecssWordpress = require('purgecss-with-wordpress');
const purgecssWoocommerce = require('purgecss-with-woocommerce');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');

module.exports = merge(Common(true), {
  mode: 'production',
  optimization: {
    minimize: true,
    minimizer: [
      new TerserPlugin({
        extractComments: false,
        terserOptions: {
          output: {
            comments: false
          }
        }
      })
    ]
  },
  plugins: [
    new CleanWebpackPlugin(),
    // new PurgecssPlugin({
    //   paths: glob.sync(`${path.join(__dirname, 'src')}/**/*`, { nodir: true }),
    //   whitelist: [
    //     ...purgecssWordpress.whitelist,
    //     ...purgecssWoocommerce.whitelist
    //   ],
    //   whitelistPatterns: [
    //     ...purgecssWordpress.whitelistPatterns,
    //     ...purgecssWoocommerce.whitelistPatterns
    //   ]
    // })
  ]
});