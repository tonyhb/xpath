require.config({

  # Main app
  deps: ['main'],

  # Libraries
  paths: {
    libs: '/_admin/assets/js/libs',
    jquery: '/_admin/assets/js/libs/jquery.1.9.1.min',
    lodash: '/_admin/assets/js/libs/lodash.min',
    backbone: '/_admin/assets/js/libs/backbone-min',
    rules_simple: '/_admin/assets/js/libs/rules.simple',
    wysihtml5: '/_admin/assets/js/libs/wysihtml5-0.3.0',
  },

  shim: {
    backbone: {
      deps: ['jquery', 'lodash'],
      exports: 'Backbone',
    },
    lodash: {
      exports: '_'
    }
    wysihtml5: {
      exports: 'wysihtml5'
    }
  }
})
