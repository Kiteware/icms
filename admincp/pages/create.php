<?php if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
}
?>
<div id="content">
    <div class="box">
        <div class="box-header">Create a New Page</div>
        <div class="box-body">
            <form method="post" action="" enctype="multipart/form-data">
                <fieldset class="form-group">
                    <label for="pagetitle">Title</label>
                </fieldset>
                <input type="text" class="form-control" name="pageTitle" value="<?php if(isset($_POST['title'])) echo htmlentities($_POST['title']); ?>" >
                <fieldset class="form-group">
                    <h4>URL:</h4>
                    <input type="text" class="form-control" name="pageURL" value="<?php if(isset($_POST['url'])) echo htmlentities($_POST['url']); ?>"/>
                </fieldset>
                <fieldset class="form-group">
                    <h4>Position:</h4>
                    <input type="number" class="form-control" name="pagePosition" value="<?php if(isset($_POST['position'])) echo htmlentities($_POST['position']); ?> "min="1" max="5">
                </fieldset>
                <fieldset class="form-group">
                    <h4>Usergroups that have access:</h4>
                    <input type="text" class="form-control" name="pagePermission" value="<?php if (isset($_POST['permission'])) { echo htmlentities($_POST['permission']); } else { echo ("guest, user, administrator"); } ?>"/>
                </fieldset>
                <fieldset class="form-group">
                    <h4>Content:</h4>
                    <textarea class="form-control" name="pageContent"></textarea>
                </fieldset>
                <button type="submit" name="submit" class="btn btn-primary">Create Page</button>
            </form>
        </div>
    </div>
</div>
<script>
    var simplemde = new SimpleMDE();
</script>