<?php
defined('_ICMS') or die;

$rows = substr_count( $this->controller->content, "\n" ) ;
?>
<script src="https://cdn.jsdelivr.net/ace/1.2.3/min/ace.js" type="text/javascript" charset="utf-8"></script>
<div class="box">
    <div class="box-header">Template Editor</div>
    <div class="box-body">
        <div class="col-md-12">
            <form action="" method="post">
                <fieldset>
                    <input type="text" name="file" value="<?php echo $this->controller->fileName ?>" class="form-control">
                </fieldset>
                <fieldset>
                    <textarea name="templateContent" data-editor="php" rows="<?php echo $rows ?>" cols="100" ><?php echo htmlspecialchars($this->controller->content) ?></textarea>
                </fieldset>
                <button name="submit" type="submit" class="btn btn-primary">Save</button>
                <a href="/admin" class="btn btn-danger pull-right">Cancel</a>
            </form>
        </div>
        <div class="container">
            <div class="col-sm-4">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Templates</th>
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
            <div class="col-sm-4">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Pages</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach (glob($this->controller->pages.'*.php') as $file) {
                        echo "<tr><td><form action='' method='post'><input type='submit' name='file' value='".$file."'class=\"btn btn-primary\"></form></td></tr>";
                    }
                    if (empty($errors) === false) {
                        echo '<p>' . implode('</p><p>', $errors) . '</p>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div class="col-sm-4">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Meta Data Files</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach (glob($this->controller->pages.'*.data') as $file) {
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