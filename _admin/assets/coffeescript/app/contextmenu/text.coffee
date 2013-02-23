define(["jquery", "lodash", "backbone"], ($, _, Backbone) ->

  TextMenu = Backbone.View.extend({
    tagName: "ul",

    # We bind events to each LI and listen for context menu clicks
    events: {
      "click li" : "click"
    }

    # The target element we have selected to perform actions on
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
      app.TextEditor.create(@.target)


  })

)
