define(["jquery", "lodash", "backbone", "wysihtml5", "rules_simple"], ($, _, Backbone, Wysihtml5, rules) ->

  TextEditor = Backbone.View.extend({
    id: 'app_wysihtml5_editor'
    tagName: "textarea"
    target: undefined
    editor: undefined
    toolbar: "<div id='app_wysihtml5_toolbar'></div>"
    offset: {
      left: undefined,
      top: undefined,
    }

    initialize: ->


    create: (target, coords) ->
      try
        @.undelegateEvents()
        @.$el.remove()
        @.el.value = ''
      catch err
      @.target = target
      @.render(coords)


    # Adds the textbox & wysihtml5 elements to the context document
    render: ->
      @.el.value = @.target.innerHTML

      $('body').append(@.el, @.toolbar)

      # Add wysihtml5
      @.editor = new Wysihtml5.Editor(@.el, {
        toolbar: 'app_wysihtml5_toolbar',
        parserRules: rules
      })
      @.setCss()


    delegateEvents: () ->
      $(frames['page_iframe'].window).on('scroll', _.bind(@.scroll, @))
      $(frames['page_iframe'].window).on('blur', _.bind(@.blur, @))

    scroll: () ->
      top = @.offset.top - app.context.scrollTop()
      @.editor.currentView.iframe.style.top = top + 'px'

    setCss: ->
      # Get the target element's offset and font information to use in our
      # editor
      @.offset = $(@.target).offset()
      @.offset.top = (@.offset.top + 60)

      css = {
        top: (@.offset.top - app.context.scrollTop()) + 'px'
        left: @.offset.left + 'px'
        height: ($(@.target).height() + 3) + 'px'
        width: $(@.target).width() + 'px'
        border: 0
        color: Wysihtml5.dom.getStyle('color').from(@.target)
        padding: Wysihtml5.dom.getStyle('padding').from(@.target)
        font: Wysihtml5.dom.getStyle('font').from(@.target)
      }
      @.$el.css(css)
      $(@.editor.currentView.iframe).css(css)

  })
)
