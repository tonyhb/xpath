<div id="app_loading">
</div>
<iframe id="page_iframe" src="/admin/page/show<?= $uri ?>" onload="_load()"></iframe>
<script>
  // The script is included at the footer of the page and is run through AMD, 
  // therefore we need to defer loading until our app has loaded.
  _load = function() {
    if ( ! window.app) {
        window.setTimeout(_load, 100);
    } else {
        window.app.init();
        document.getElementById('app_loading').parentNode.removeChild(document.getElementById('app_loading'));
    }
  }
</script>
