<div id="page_new">
    <h1>404 Not Found</h1>

    <h2>Create a new page at this URL</h2>
    <form id="page_new_form" action="/admin/page/create" method="post" enctype="multipart/form-data">
        <p><input type="text" name="page[name]" id="page_name" placeholder="Name this page" /> and <label class="link" for="page_html">upload a html file</label> (you can also drag &amp; drop) <button type="submit">Create page</button></p>
        <input type="hidden" name="page[uri]" id="page_uri" value="<?= $uri ?>" /> 
        <input type="file" name="page[html]" id="page_html" />
    </form>

</div>
