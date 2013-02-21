require(["jquery", "lodash", "backbone", "app/xpath", "app/contextmenu"], ($, _, Backbone, Xpath, ContextMenu) ->

  window.app = app = {}

  app.init = () ->
    app.ContextMenu = new ContextMenu({
      context: $('#page_iframe').contents().find('body')
    })
    app

)
