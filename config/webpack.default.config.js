var path = require('path'),
    webpack = require('webpack')

module.exports = {
  entry: {
    index: path.join(__dirname, '../src', 'index')
  },
  output: {
    path: path.join(__dirname, '../dist'),
    filename: '[name].[hash:7].js',
    publicPath: '',
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        loader: 'babel-loader',
        exclude: /node_modules/
      },
      {
        test: /\.vue$/,
        loader: 'vue-loader',
        include: path.join(__dirname, '../src')
      },
      {
        test: /\.css$/,
        loader: [ 'style-loader', 'css-loader' ]
      },
      {
        test: /\.less$/,
        loader: [ 'style-loader', 'css-loader', 'less-loader' ]
      },
      {
        test: /\.(jpe?g|png|gif|svg)(\?.*)?$/,
        loader: 'url-loader',
        query: {
          limit: 40000,
          name: '[name].[hash:7].[ext]',
          output: 'static/images'
        }
      },
      {
        test: /\.(woff2?|ttf|eof)(\?.*)?$/,
        loader: 'url-loader',
        query: {
          limit: 40000,
          name: '[name].[hash:7].[ext]',
          output: 'static/fonts'
        }
      }
    ]
  },
  resolve: {
    alias: {
      vue: 'vue/dist/vue.js',
      '@': path.resolve('src')
    }
  },
  optimization: {
    splitChunks: {
      cacheGroups: {
        vendor: {
          name: 'vendor',
          chunks: 'all',
          test: /[\\/]node_modules[\\/]/
        }
      }
    }
  },
  plugins: []
}