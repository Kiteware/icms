<?php if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
}
use Respect\Validation\Validator as v;

if (isset($_POST['submit'])) {

    $config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);

    if (!isset($_POST['title']) || !isset($_POST['url']) || !isset($_POST['editPage']) || !isset($_POST['permission']) || !isset($_POST['position']) ) {

        $errors[] = 'All fields are required.';

    } else {

        if (v::alnum()->notEmpty()->validate($_POST['title'])) {
            $title = $_POST['title'];
        } else {
            $errors[] = 'invalid title';
        }
        if (v::alnum()->notEmpty()->validate($_POST['url'])) {
            $url = $_POST['url'];
        } else {
            $errors[] = 'invalid URL';
        }

        $editPage = $purifier->purify($_POST['editPage']);

        if (v::alnum(',')->validate($_POST['permission'])) {
            $permission = $_POST['permission'];
        } else {
            $errors[] = 'invalid permissions';
        }
        if (v::int()->validate($_POST['position'])) {
            $position = htmlentities($_POST['position']);
        } else {
            $errors[] = 'invalid position';
        }
    }
    if (empty($errors) === true) {
        $userArray = explode(', ', $permission); //split string into array seperated by ', '
        foreach($userArray as $usergroup) //loop over values
        {
            $permissions->add_usergroup($usergroup, $url);
        }

        $pages->create_page($title, $url, $editPage);
        $pageArray = $pages->get_page($url);
        $pages->generate_page($pageArray['title'], $settings->production->site->cwd, $url, $pageArray['content']);
        $url = "index.php?page=".$url;
        $pages->create_nav($title, $url, $position);
        echo("<script> successAlert();</script>");

    }
}
if(isset($_POST['editPage'])) $text = htmlentities($_POST['editPage']);
?>
<script src="http://cdnjs.cloudflare.com/ajax/libs/ace/1.1.3/ace.js"></script>
<body>
<div id="content">
    <div class="box">
        <div class="box-header">Admin Panel</div>
        <div class="box-body">
            <h1>Create Page</h1>

            <?php
            if (isset($_GET['success']) && empty($_GET['success'])) {
                echo 'Page created.';
            }
            ?>

            <form method="post" action="">
                <h4>Title:</h4>
                <input type="text" name="title" value="<?php if(isset($_POST['title'])) echo htmlentities($_POST['title']); ?>" >
                <h4>URL:</h4>
                <input type="text" name="url" value="<?php if(isset($_POST['url'])) echo htmlentities($_POST['url']); ?>"/>
                <h4>Position:</h4>
                <input type="number" name="position" value="<?php if(isset($_POST['position'])) echo htmlentities($_POST['position']); ?> "min="1" max="5">
                <h4>Usergroups that have access:</h4>
                <input type="text" name="permission" value="<?php if (isset($_POST['permission'])) { echo htmlentities($_POST['permission']); } else { echo ("guest, user, administrator"); } ?>"/>
                <h4>Content:</h4>
                <textarea name="editPage" id="editPage" data-editor="php" cols="100" rows="50"></textarea>

                <br />
                <input type="submit" name="submit" />
            </form>

            <?php
            if (empty($errors) === false) {
                echo '<p>' . implode('</p><p>', $errors) . '</p>';
            }

            ?>
        </div>
    </div>
</div>

</body>
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
</html>
