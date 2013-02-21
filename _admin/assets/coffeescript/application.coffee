# POC: Need to transfer to Backbone in the future.
App = () ->

# Initialises the app by binding to context menu events and setting the page
# iframe height
#
# *Opts* ->
#   + *options* - An object of options, including the document context for the
#                 app. This will be where context menus etc. are added to.
App.prototype.init = (options) ->

  if options
    @.context = options.context
    $(@.context).height(($(window).height() - 60) + 'px')
  else
    @.context = document

  @.contextMenu = new ContextMenu(@.context)

  # Add event listeners for right-clicking (context-menus)
  document.body.addEventListener('contextmenu', _.bind(@.mouseDown, @))
  frames['page_iframe'].document.body.addEventListener('contextmenu', _.bind(@.mouseDown, @))


# Here for testing: may be moved to contextMenu if the contextmenu event is
# cross-browser. This defers functionality to the contextMenu method if the
# event was a right click
App.prototype.mouseDown = (event) ->
  if event.which == 3 or event.button == 2
    @.contextMenu.process(event.target)
    event.preventDefault()
    return false


# A context menu object for creating context menus when right clicking on an
# object.
# 
# *Opts* ->
#   + *options* - The document context for the menu
#
ContextMenu = (context) ->
  @.context = context
  @.container = document.createElement('div')
  $(@.context).contents().find("body").append(@.container)

  return this

ContextMenu.prototype.elementContextMenus = {
  'H1|H2|H3|H4|H5|H6|P|A|SPAN': 'textBlock'
}

# Iterates over each ContextMenu.elements key, matching the current element's
# tagName and calling the specified method.
ContextMenu.prototype.process = (element) ->
  for elements, methodName of this.elementContextMenus
    elements = elements.split('|')

    if elements.indexOf(element.tagName) >= 0
      @[methodName].call(@, element)
      break

ContextMenu.prototype.textBlock = (element) ->

# Create a new app method, bound to the main window's App global
window.App = new App()

# Initialise the app once we've got our iframe content loaded. We need to do
# this in the context of our App object so the App's context methods have 'this'
# in the right context.
document.getElementById('page_iframe').onload = _.bind(window.App.init, window.App, { context: $('#page_iframe') })
