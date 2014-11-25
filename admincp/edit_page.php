<?php if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
} ?>
<?php
/* edit_page.php
Loads an existing file in the /pages/ directory into a php editor.
Actions:
    - Edit
    - Save
    - Delete
*/
$text = $url = $rows = $action = "";
$num_newlines = 5; //minimum amount of rows the editor should use

if (isset($_GET['url'])) $url = htmlentities($_GET['url']);
if (isset($_POST['url'])) $postUrl = $_POST['url'];
if (isset($_POST['text'])) $text = $_POST['text'];
if (isset($_GET['action'])) $action = $_GET['action'];


//User has edited a file and wants to save it
if (isset($_POST['submit'])) {
    $file = '../pages/' . $postUrl . '.php';
    $text = $pages->edit_page($file, $text);
    $rows = substr_count( $text, "\n" ) ;

    echo("<script> successAlert();</script>");
}
//User has given a URL and wants to edit it
if ($action == "edit") {
    if (!empty($url)) {
        if (empty($text)) {;
            $file = '../pages/' . $url . '.php';
            $text = file_get_contents($file);
            $rows = substr_count( $text, "\n" ) ;
        }
    }
}
//User wants to delete the given URL
elseif ($action == "delete") {
    if ($pages->delete_page($url) & $permissions->delete_all_page_permissions($url)) {
        echo("<script> successAlert();</script>");
    }
}
?>
<script src="http://cdnjs.cloudflare.com/ajax/libs/ace/1.1.3/ace.js"></script>
<body>
<div id="content">
    <div class="box">
        <div class="box-header">Admin Panel</div>
        <div class="box-body">
            <h1>Edit Page</h1>
            <?php
            $allpages = $pages->get_pages();
            if (!empty($allpages)) {
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
                    echo ('<tr><td>'.$showPage['title'].'</td>
                    <td>'. $showPage['url'].' </td>
                    <td> '. $showPage['content'].'</td>
                    <td> '. $showPage['ip'].' </td>
                    <td> '. $showPage['time'].' </td>
                    <td><a href="index.php?page=edit_page&action=edit&url='.$showPage['url'].'">Edit</a> </td>
                    <td><a href="index.php?page=edit_page&action=delete&url='.$showPage['url'].'">Delete</a> </td>
                    </tr>');
                }
                } else { ?>
                    <td> Home</td>
                    <td><a href='index.php?page=edit_page&action=edit&url=home'>Edit</a></td>
                    <td>index.php</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td> <a href="index.php?page=edit_page&action=delete&url=home">Delete</a></td>
                <?php
                }
                ?>
                </tbody>
                </table>
                <!-- Edit Page Form -->
                <form action="" method="post" name="Edit Page">
                    <input type="hidden" name="page" value="edit_page" />
                    <p>File Name:<br />
                        <input id="get-url" name="url" type="text" size="45" value="<?php echo $url?>"/>
                    </p>
                    <textarea name="text" data-editor="php" cols="100" rows="<?php echo $rows ?>" id="editpage"><?php echo htmlspecialchars($text) ?></textarea>
                    <br />
                    <input name="submit" type="submit" value="submit" id="editpage"/>
                </form>
                <?php
                if (empty($errors) === false) {
                    echo '<p>' . implode('</p><p>', $errors) . '</p>';
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
            textarea.closest('form').submit(function () {
                textarea.val(editor.getSession().getValue());
            })

        });
    });
</script>
</body>
</html>
