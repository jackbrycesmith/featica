const commonPlugins = [
  // require('postcss-import'),
  // require('postcss-preset-env')({
  //   autoprefixer: {
  //     flexbox: 'no-2009'
  //   },
  //   stage: 3
  // }),
  require('tailwindcss')('./tailwind.config.js'),
]

const plugins = process.env.NODE_ENV === 'production'
  ? [...commonPlugins, require('cssnano')]
  : [...commonPlugins]

module.exports = { plugins }
