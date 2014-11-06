<?php if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
} ?>
<?php
$url = $template->getCurrentTemplatePath($settings->production->site->template);
$text = "";
if (isset($_GET['template'])) {
    $file = $_GET['template'];
} else {
    $file = '../'.$url.'index.php';
}
if (isset($_POST['submit'])) {
    if (empty($errors) === true) {
        $text = $pages->edit_page($file, $_POST['text']);
    }
} else {
    $text = file_get_contents($file);
}
$rows = substr_count( $text, "\n" ) ;
?>
<script src="http://cdnjs.cloudflare.com/ajax/libs/ace/1.1.3/ace.js"></script>
<body>
<div id="content">
    <div class="box">
        <div class="box-header">Template Editor</div>
        <div class="box-body">
            <h1><?php echo $file ?></h1>

            <?php
            if (isset($_GET['success']) && empty($_GET['success'])) {
                echo 'Page created.';
            }
            ?>
            <form action="" method="post" name="post">
                <p>
                    <textarea name="text" data-editor="php" rows="<?php echo $rows ?>" cols="100" ><?php echo htmlspecialchars($text) ?></textarea>
                </p>
                <input name="submit" type="submit" value="submit"/>
            </form>
            <?php
            foreach (glob('../'.$url.'*.*') as $file) {
                echo "<a href='?page=template&template=".$file."'>".$file."</a>\n";
            }
            if (empty($errors) === false) {
                echo '<p>' . implode('</p><p>', $errors) . '</p>';
            }
            ?>
        </div>
    </div>
</div>
</body>
<script>
    // Hook up ACE editor to all textareas with data-editor attribute
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
            // editor.setTheme("ace/theme/idle_fingers");
            // copy back to textarea on form submit...
            textarea.closest('form').submit(function () {
                textarea.val(editor.getSession().getValue());
            })

        });
    });
</script>
