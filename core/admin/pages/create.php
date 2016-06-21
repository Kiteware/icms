<?php if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
} ?>

<div class="box">
    <div class="box-header">Create a New Page</div>
    <div class="box-body">
        <form method="post" action="/admin/pages/create" class="reload-form" enctype="multipart/form-data">
            <fieldset class="form-group">
                <label for="pagetitle">Title</label>
            </fieldset>
            <input type="text" class="form-control" name="pageTitle"  >
            <fieldset class="form-group">
                <h4>URL:</h4>
                <input type="text" class="form-control" name="pageURL"/>
            </fieldset>
            <fieldset class="form-group">
                <h4>Position:</h4>
                <input type="number" class="form-control" name="pagePosition" "min="1" max="5">
            </fieldset>
            <fieldset class="form-group">
                <h4>Usergroups that have access:</h4>
                <input type="text" class="form-control" name="pagePermission" value="guest, user, administrator"/>
            </fieldset>
            <fieldset class="form-group">
                <h4>Content:</h4>
                <textarea class="form-control" name="pageContent"></textarea>
            </fieldset>
            <fieldset class="form-group">
                <label for="pageKeywords">Meta Keywords</label>
                <input type="text" class="form-control" name="pageKeywords" id="pageKeywords">
            </fieldset>
            <fieldset class="form-group">
                <label for="pageDesc">Meta Description</label>
                <input type="text" class="form-control" name="pageDesc" id="pageDesc">
            </fieldset>
            <button type="submit" name="submit" class="btn btn-primary">Create Page</button>
        </form>
    </div>
</div>
