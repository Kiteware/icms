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
$num_newlines = 5; //minimum amount of rows the editor should use

if (isset($_GET['url'])) $url = htmlentities($_GET['url']);
if (isset($_GET['action'])) $action = $_GET['action'];

?>
<script src="http://cdnjs.cloudflare.com/ajax/libs/ace/1.1.3/ace.js"></script>
<body>
<div id="content">
    <div class="box">
        <div class="box-header">Admin Panel</div>
        <div class="box-body">
            <div id="result"></div>
            <?php

if ($action == "delete") {

    $selectPage = $pages->get_page($url);
    //Confirm with user they want to delete, if yes refresh and do isset['yes']
    echo('
    Are you sure you want to permanently delete ' . $selectPage['title'] . '?
				<form action="controller/edit_page_controller.php" method="post" id="deletePageForm">
				<input name="pageURL" type="hidden" value="' . $selectPage['url'] . '">
				<input name="delete" type="submit" value="yes" />
				<input name="no" ONCLICK="history.go(-1)" type="button" value="No" />
				</form>
');
}
//User has given a URL and wants to edit it
elseif ($action == "edit" && !empty($url)) {
    if (empty($text)) {
        ;
        $file = '../pages/' . $url . '.php';
        $text = file_get_contents($file);
        $rows = substr_count($text, "\n");
    }
    ?>
    <form action="controller/edit_page_controller.php" method="post" name="Edit Page" id="editPageForm">
        <input type="hidden" name="page" value="edit_page"/>

        <p>File Name:<br/>
            <input id="url" name="url" type="text" size="45" value="<?php echo $url ?>"/>
        </p>
                <textarea id="text" name="text" data-editor="php" cols="100"
                          rows="<?php echo $rows ?>"><?php echo htmlspecialchars($text) ?></textarea>
        <br/>
        <input name="submit" type="submit" value="submit" id="editpage"/>
    </form>
<?php
} else {
            ?>
            <h1>Edit Page</h1>
            <?php
            $allpages = $pages->get_pages();
            ?>
            <table>
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
                    <td><a href="index.php?page=edit_page&action=edit&url=' . $showPage['url'] . '">Edit</a> </td>
                    <td><a href="index.php?page=edit_page&action=delete&url=' . $showPage['url'] . '">Delete</a> </td>
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
    // Hook up ACE editor to all textareas with the 'editor' attribute
    $(function () {
        $('textarea[data-editor]').each(function () {
            var textarea = $(this);

            var mode = textarea.data('editor');

            var editDiv = $('<div>', {
                position: 'absolute',
                width: textarea.width(),
                height: textarea.height(),
                'class': textarea.attr('class')
            }).insertBefore(textarea);

            textarea.css('display', 'none');

            var editor = ace.edit(editDiv[0]);
            editor.renderer.setShowGutter(false);
            editor.getSession().setValue(textarea.val());
            editor.getSession().setMode("ace/mode/" + mode);
            // copy back to textarea on form submit...
            editor.getSession().on('change', function(e) {
                textarea.val(editor.getSession().getValue());
            });
        });
    });

    //SAVE NEW PAGE FORM
    // Attach a submit handler to the form
    $( "#editPageForm" ).submit(function( event ) {
// Stop form from submitting normally
        event.preventDefault();
// Get some values from elements on the page:
        var $form = $( this ),
            pageURL = $form.find( "input[name='url']" ).val(),
            text = $form.find( "textarea[name='text']" ).val(),
            url = $form.attr( "action" );
// Send the data using post
        var posting = $.post( url, {
            pageURL: pageURL,
            text: text,
            action: 'update',
            token: '<?php echo $hashed;?>'
        } );
// Put the results in a div
        posting.done(function( data ) {
            var content = $( data );
            $( "#result" ).empty().append( content );
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
            var content = $( data );
            $( "#result" ).empty().append( content );
        });
    });
</script>
</body>
</html>
