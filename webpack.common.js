const path = require('path');
const packages = require('./package.json');

const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');

const getSiteFolderPath = function() {
  let dir = __dirname.replace(/\\/g, "/").split('/');

  dir.forEach((path, index) => {
    if (path === 'htdocs' || path === 'www' || path === 'Local Sites') {
      dir.splice(index + 1, dir.length);
    }
  });

  return path.relative(dir.join('/'), path.resolve(__dirname, '../../../'));
}

module.exports = isProduction => ({
  stats: {
    children: false
  },
  entry: {
    app: './src/js/index.js',
    vendor: Object.keys(packages.dependencies)
  },
  output: {
    path: path.resolve(__dirname, 'dist'),
    filename: isProduction ? 'js/[name].[contenthash:8].bundle.js' : 'js/[name].dev.js',
    pathinfo: false,
  },
  optimization: {
    /*splitChunks: {
      cacheGroups: {
        default: 'test',
      },
      chunks: 'all',
      name: 'vendor',
      filename: isProduction ? 'js/[name].[chunkhash:8].bundle.js' : 'js/[name].dev.js'
    }*/
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        include: path.resolve(__dirname, 'src'),
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env'],
            plugins: ['@babel/plugin-proposal-class-properties']
          }
        }
      },
      {
        test: /\.scss$/,
        include: path.resolve(__dirname, 'src'),
        use: [
          { loader: MiniCssExtractPlugin.loader },
          {
            loader: 'css-loader',
            options: { sourceMap: !isProduction }
          },
          { loader: 'postcss-loader',
            options: {
              sourceMap: !isProduction,
              postcssOptions: {
                plugins: [
                  require('autoprefixer'),
                  require('cssnano')
                ]
              }
            }
          },
          {
            loader: 'sass-loader',
            options: {
              sourceMap: !isProduction,
              additionalData: `
                $env: ${isProduction ? 'production' : 'development'};
                $theme-folder-name: ${path.basename(__dirname)};
                $site-folder-name: ${path.basename(path.resolve(__dirname, '../../../'))};
                $site-folder-path: ${getSiteFolderPath()};
              `,
            }
          }
        ]
      }
    ]
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: isProduction ? 'css/app.[contenthash:8].css' : 'css/app.dev.css',
    }),
    new CopyWebpackPlugin(
      [
        {
          from: './src/',
          to: './',
          force: true,
          ignore: ['wp.style.css']
        },
        {
          from: './src/wp.style.css',
          to: './style.css',
          force: true
        }
      ],
      {
        copyUnmodified: true,
        ignore: [
          '*.js',
          '*.scss',
          '*.DS_Store',
          '.gitkeep'
        ]
      }
    )
  ]
})