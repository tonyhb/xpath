define(["jquery", "lodash", "backbone"], ($, _, Backbone) ->

  TextMenu = Backbone.View.extend({
    tagName: "ul",

    events: {
      "click li" : "click"
    },

    target: undefined,

    initialize: ->

    render: ->
      @.$el.html("<li data-action='edit'>Edit</li>")
      @

    setTarget: (element) ->
      @.target = element
      @

    unsetTarget: ->
      @.target = null
      @

    # Performs the functionality to add the editable textbox/set the content to
    # editable
    #
    click: (event) ->
      @.target.setAttribute('contenteditable', true)
      @.target.focus()


  })

)
