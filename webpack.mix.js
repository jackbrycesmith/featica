const mix = require('laravel-mix')
const path = require('path')

// Do not use Laravel mix's built-in SVG loader.
Mix.listen('configReady', function(config) {
  const rules = config.module.rules;
  const svgRegexPart = '|^((?!font).)*\\.svg';

  for (let rule of rules) {
    if (rule.test) {
      if (('' + rule.test).indexOf(svgRegexPart) > -1) {
        rule.test = new RegExp(('' + rule.test).replace(svgRegexPart, ''));
      }
    }
  }
});

mix
.setPublicPath('public/vendor/featica')
.js('resources/js/app.js', '').vue({
  version: 3,
  runtimeOnly: true,
  extractVueStyles: true,
  globalVueStyles: false
})
.postCss('resources/css/app.css', '')
.options({ autoprefixer: false })
.alias({
  '@': path.resolve(__dirname, 'resources/js/'),
})
.copy('public', '../demo.featica.com/public')
.webpackConfig(webpack => {
  return {
    stats: 'normal',
    module: {
      rules: [
        {
          test: /\.svg$/,
          use: [
            'vue-loader',
            'vue-svg-loader',
          ],
        },
      ],
    },
    plugins: [
      new webpack.optimize.LimitChunkCountPlugin({
        maxChunks: 1
      })
    ]
  }
})
.version()
