define(["jquery", "lodash", "backbone", "app/contextmenu/text"], ($, _, Backbone, TextMenu) ->
  # ContextMenus should be initialised with
  ContextMenu = Backbone.View.extend({
    tagName: "div",
    id: "app_contextMenu",

    contextMenus : {
      'H1|H2|H3|H4|H5|H6|P|A|SPAN': new TextMenu()
    },

    menu : null,

    # The position of the mouseclick for our menu
    position: {},

    initialize: ->
      @.options.context
        .on('contextmenu', _.bind(@.click, @))
        .on('click', _.bind(@.hideMenu, @))
        .append("<link rel='stylesheet' href='/_admin/assets/css/iframe.css' />")

    render: ->
      $(@.options.context).append(@.el)
      @.$el.css({ position: 'absolute', top: @.position.y, left: @.position.x })

    # Show the context menu
    click: (event) ->
      @.position.x = event.pageX
      @.position.y = event.pageY

      # Find the type of menu we're going to show
      for elements, menu of @.contextMenus
        elements = elements.split('|')
        if elements.indexOf(event.target.tagName) >= 0
          @.showMenu(menu, event.target)
          break

      return false

    showMenu: (type, element) ->

      # Hide the current menu
      @.hideMenu()

      # Render a new menu and delegate events, set our menu HTML and render our
      # menu
      @.menu = type
      @.menu.setTarget(element).render().delegateEvents()
      @.$el.html(@.menu.el)

      @.render()

    hideMenu: () ->
      # Remove any current menus
      if @.menu
        @.menu.undelegateEvents()
        @.menu.unsetTarget().remove()

        # Remove our container from the document, so there's no artifacts or
        # borders
        @.$el.remove()

      @.menu = null

  })
)
