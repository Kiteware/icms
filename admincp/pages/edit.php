<?php if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
}
/* edit_page.php
Loads an existing file in the /pages/ directory into a php editor.
Actions:
    - Edit
    - Save
    - Delete
*/
$url = $text = $rows = $action = "";
if (isset($_GET['url'])) $url = htmlentities($_GET['url']);
if (isset($_GET['action'])) $action = $_GET['action'];

?>
<div id="content">
    <div class="box">
        <div class="box-header">Edit Page</div>
        <div class="box-body">
            <div id="result"></div>
            <?php
            if (isset($this->model->id)) {
                $ID = $this->model->id;
                $selectPage = $this->model->get_page($ID);
                $url = $selectPage['url'];
            if ($action == "delete") {

                //Confirm with user they want to delete, if yes refresh and do isset['yes']
                echo('
                Are you sure you want to permanently delete ' . $selectPage['title'] . '?
				<form action="/admin/pages/delete/'. $ID .'" method="post" id="deletePageForm">
				<input name="pageURL" type="hidden" value="' . $selectPage['url'] . '">
				<input name="delete" type="submit" value="yes" />
				<input name="no" ONCLICK="history.go(-1)" type="button" value="No" />
				</form>');
            }
            //User has given a URL and wants to edit it
            else {
                if (empty($text)) {
                    ;
                    $file = 'pages/' . $url . '.php';
                    $text = file_get_contents($file);
                    $rows = substr_count($text, "\n");
                }
                ?>
                <form action="/admin/pages/update/<?php echo $ID ?>" method="post" name="Edit Page" id="editPageForm">
                    <input type="hidden" name="page" value="edit_page"/>
                    <fieldset class="form-group">
                        <label for="pageURL">URL</label>
                        <input type="text" class="form-control" name="pageURL" id="pageURL" value="<?php echo $url ?>">
                    </fieldset>
                    <fieldset class="form-group">
                        <label for="pageContent">Content</label>
                        <textarea class="form-control" name="pageContent"><?php echo htmlspecialchars($text) ?></textarea>
                    </fieldset>

                    <button name="submit" id="editpage" type="submit" class="btn btn-primary">Submit</button>
                </form>
                <?php
            }
            } else {
            $allpages = $this->model->get_pages();
            ?>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>URL</th>
                    <th>Content</th>
                    <th>IP</th>
                    <th>Time</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($allpages as $showPage) {
                    //displaying posts
                    echo('<tr><td>' . $showPage['title'] . '</td>
                    <td>' . $showPage['url'] . ' </td>
                    <td> ' . substr($showPage['content'], 0, 200) . '</td>
                    <td> ' . $showPage['ip'] . ' </td>
                    <td> ' . $showPage['time'] . ' </td>
                    <td> <a href="/admin/pages/edit/' . $showPage['page_id'] . '">Edit</a></td>
                    <td> <a href="/admin/pages/delete/' . $showPage['page_id'] .'">Delete</a></td>
                    </tr>');
                } ?>
                </tbody>
            </table>

            <?php
            if (empty($errors) === false) {
                echo '<p>' . implode('</p><p>', $errors) . '</p>';
            }
            ?>
        </div>
    </div>
</div>
<?php
}
?>
</div>
</div>
</div>
<script>
    var simplemde = new SimpleMDE();
    //SAVE NEW PAGE FORM
    // Attach a submit handler to the form
    $( "#editPageForm" ).submit(function( event ) {
// Stop form from submitting normally
        event.preventDefault();
// Get some values from elements on the page:
        var $form = $( this ),
            pageURL = $form.find( "input[name='pageURL']" ).val(),
            pageContent = $form.find( "textarea[name='pageContent']" ).val(),
            url = $form.attr( "action" );
// Send the data using post
        var posting = $.post( url, {
            pageURL: pageURL,
            pageContent: pageContent,
            action: 'update',
            token: '<?php echo $hashed;?>'
        } );
// Put the results in a div
        posting.done(function( data ) {
            $( "#result" ).empty().append( "Success!" );
        });
    });
    //DELETE Page FORM
    // Attach a submit handler to the form
    $( "#deletePageForm" ).submit(function( event ) {
// Stop form from submitting normally
        event.preventDefault();
// Get some values from elements on the page:
        var $form = $( this ),
            pageURL = $form.find( "input[name='pageURL']" ).val(),
            url = $form.attr( "action" );
// Send the data using post
        var posting = $.post( url, {
            pageURL: pageURL,
            action: 'delete',
            token: '<?php echo $hashed;?>'
        } );
// Put the results in a div
        posting.done(function( data ) {
            $( "#result" ).empty().append( "Deleted Successfully" );
        });
    });
</script>
