

  window.app = app = {
    edits: {}
  }

  app.init = () ->
    app.context = $('#page_iframe').contents().find('body')
    app.TextEditor = new TextEditor()
    app.ContextMenu = new ContextMenu()
    app

)
