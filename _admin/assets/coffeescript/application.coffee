# POC: Need to transfer to Backbone in the future.
window.App = App = () ->

App.prototype.xpath = (element, iframe) ->

  # Gets the total number of previous siblings that are of the same tag type. This
  # is used to give an index to an array of nodes for a particular element (eg
  # /div[5]/ is the 5th div in the XPath query.)
  #
  # An optional ID or class can be passed into opts to ensure we get a key for 
  # siblings with matching classes or ids
  #
  getElementCount = (element, opts) ->
    previous = element.previousSibling
    i = 1
    while previous
      # This may be an empty series of spaces between elements etc... we only want
      # HTML nodes.
      if previous.nodeType != 1
        previous = previous.previousSibling
        continue
      # If we're looking for sibling tags that need to have the same class or ID
      # skip if they don't match
      if (opts && opts.class && previous.getAttribute('class') != element.getAttribute('class')) || (opts && opts.id && previous.getAttribute('id') != element.getAttribute('id'))
        previous = previous.previousSibling
        continue
      # If the tag name (div/a etc) matches our target element, so increase the
      # counter
      i++ if (previous.localName == element.localName)
      previous = previous.previousSibling
    return i

  # We may be accessing an iframe's document
  if iframe
    all_nodes = frames[iframe].document.getElementsByTagName('*')
  else
    all_nodes = document.getElementsByTagName('*')

  # Loop through all nodes and work out our xpath
  segments = []
  while element && element.nodeType == 1
    if element.hasAttribute('id')
      # This element has an ID, so we're going to use this in the XPath
      # There are no unique IDs at the moment
      uniqueIdCount = 0
      # Loop through all nodes to find if the ID of our element is unique - we
      # need this to know how we're going to represent the ID in the XPath
      for node in all_nodes
        uniqueIdCount++ if node.hasAttribute('id') and node.id == element.id
        break if uniqueIdCount > 1 # More than 1 id exists... we've done our job
      # We can represent this using the id("$id") format if there's only 1,
      # otherwise we have to use the format '$tagname[@id="$id"]'
      if uniqueIdCount == 1
        segments.unshift 'id("' + element.getAttribute('id') + '")'
        return segments.join("/") # With a unique ID we can break and return
      else
        segments.unshift element.localName.toLowerCase() + '[@id="' + element.getAttribute('id') + '"][' + getElementCount(element, { id : element.getAttte('id') }) + ']'
    else
      # There's no class or ID, so we need to build a list of preceding elements
      # by element type
      segments.unshift element.localName.toLowerCase() + '[' + getElementCount(element) + ']'
    # Go to the parent and loop over
    element = element.parentNode

  if segments.length
    return '/' + segments.join('/')
  else
    return null

