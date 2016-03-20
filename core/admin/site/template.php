<?php if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
}
$rows = substr_count( $this->controller->content, "\n" ) ;
?>
<script src="http://cdnjs.cloudflare.com/ajax/libs/ace/1.1.3/ace.js"></script>
<div id="content">
    <div class="box">
        <div class="box-header">Template Editor</div>
        <div class="box-body">
            <form action="" method="post">
                <fieldset>
                    <input type="text" name="file" value="<?php echo $this->controller->fileName ?>" class="form-control">
                </fieldset>
                <fieldset>
                    <textarea name="templateContent" data-editor="php" rows="<?php echo $rows ?>" cols="100" ><?php echo htmlspecialchars($this->controller->content) ?></textarea>
                </fieldset>
                <button name="submit" type="submit" class="btn btn-primary">Save</button>
            </form>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Edit</th>
                </tr>
                </thead>
                    <tbody>
                        <?php
                        foreach (glob($this->controller->template.'*.*') as $file) {
                            echo "<tr><td><form action='' method='post'><input type='submit' name='file' value='".$file."'class=\"btn btn-primary\"></form></td></tr>";
                        }
                        if (empty($errors) === false) {
                            echo '<p>' . implode('</p><p>', $errors) . '</p>';
                        }
                        ?>
                    </tbody>
                </table>
        </div>
    </div>
</div>
<script>
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