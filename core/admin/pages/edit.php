<?php defined('_ICMS') or die;
/*
 * Actions:
 *  - Edit
 *  - Save
 * - Delete
*/
$pages = $this->controller->pages;
?>
<div class="box">
    <div class="box-header">Edit Page</div>
    <div class="box-body">
        <div id="result"></div>
        <?php
        if (!empty($this->controller->id)) {
            $ID = $this->controller->id;
            $parser = new \IniParser('templates/'. $this->controller->settings->production->site->template .'/' . $pages['url'] . '.data');
            $data = $parser->parse();
            ?>
            <form action="/admin/pages/update/<?php echo $ID ?>" method="post" class="no-reload-form">
                <input type="hidden" name="page" value="edit_page"/>
                <fieldset class="form-group">
                    <label for="pageTitle">Title:</label>
                    <input type="text" class="form-control" name="pageTitle" id="pageTitle" value="<?php echo $pages['title'] ?>"/>
                </fieldset>
                <fieldset class="form-group">
                    <label for="pageURL">URL</label>
                    <input type="text" class="form-control" name="pageURL" id="pageURL" value="<?php echo $pages['url'] ?>" />
                </fieldset>
                <fieldset class="form-group">
                    <label for="pageContent">Content</label>
                    <textarea class="form-control" name="pageContent"><?php echo htmlspecialchars($pages['content']) ?></textarea>
                </fieldset>
                <fieldset class="form-group">
                    <label for="pageKeywords">Meta Keywords</label>
                    <input type="text" class="form-control" name="pageKeywords" id="pageKeywords" value="<?php echo $data->keywords ?>" />
                </fieldset>
                <fieldset class="form-group">
                    <label for="pageDesc">Meta Description</label>
                    <input type="text" class="form-control" name="pageDesc" id="pageDesc" value="<?php echo $data->description ?>" />
                </fieldset>
                <button name="submit" id="editpage" type="submit" class="btn btn-primary">Submit</button>
            </form>
            <?php
        } else {

            echo('
            <div class="pull-left">
                <h2> Manage Pages </h2>
            </div>
            <div class="pull-right">
                <h2><a class="btn btn-primary" href="/admin/pages/create" role="button">New Page</a></h2>
            </div>');
            ?>
            <table class="table table-striped" id="manage-pages">
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
                foreach ($pages as $key => $attr) {
                    //displaying posts
                    echo('<tr><td>' . $attr['title'] . '</td>
                    <td>' . $attr['url'] . ' </td>
                    <td> ' . substr($attr['content'], 0, 200) . '</td>
                    <td> ' . $attr['ip'] . ' </td>
                    <td> ' . $attr['time'] . ' </td>
                    <td> <a href="/admin/pages/edit/' . $key . '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
                    <td> <a onClick=\'ajaxCall("/admin/pages/delete/' . $key .'", "manage-pages")\'> <i class="fa fa-trash" aria-hidden="true"></i> </a></td>
                    </tr>');
                } ?>
                </tbody>
            </table>
            <?php
        }
        ?>
    </div>
</div>
