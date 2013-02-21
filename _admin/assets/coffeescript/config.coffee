require.config({

  # Main app
  deps: ['main'],

  # Libraries
  paths: {
    libs: '/_admin/assets/js/libs',
    jquery: '/_admin/assets/js/libs/jquery.1.9.1.min',
    lodash: '/_admin/assets/js/libs/lodash.min',
    backbone: '/_admin/assets/js/libs/backbone-min',
  },

  shim: {
    backbone: {
      deps: ['jquery', 'lodash'],
      exports: 'Backbone',
    },
    lodash: {
      exports: '_'
    }
  }
})
