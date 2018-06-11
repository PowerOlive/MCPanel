var path = require('path'),
    merge = require('webpack-merge'),
    htmlWebpackPlugin = require('html-webpack-plugin'),
    webpackBaseConfig = require('./config/webpack.default.config'),
    pages = require('./config/pages.config')

var webpackConfig = merge(webpackBaseConfig)

for(page in pages) {
  var hwp = new htmlWebpackPlugin({
    title: pages[page].title,
    filename: pages[page].filename,
    template: pages[page].template,
    inject: false,
    excludeChunks: Object.keys(pages).filter(item => item != page)
  })
  webpackConfig.plugins.push(hwp)
}

module.exports = webpackConfig