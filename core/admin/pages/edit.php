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
<div class="box">
    <div class="box-header">Edit Page</div>
    <div class="box-body">
        <div id="result"></div>
        <?php
        if (isset($this->model->id)) {
            $ID = $this->model->id;
            $selectPage = $this->model->get_page($ID);
            $url = $selectPage['url'];

            if (empty($text)) {
                $file = 'pages/' . $url . '.php';
                $text = file_get_contents($file);
                $rows = substr_count($text, "\n");
                $parser = new \IniParser('pages/' . $url . '.data');
                $data = $parser->parse();
            }
            ?>
            <form action="/admin/pages/update/<?php echo $ID ?>" method="post" class="no-reload-form">
                <input type="hidden" name="page" value="edit_page"/>
                <fieldset class="form-group">
                    <label for="pageURL">URL</label>
                    <input type="text" class="form-control" name="pageURL" id="pageURL" value="<?php echo $url ?>">
                </fieldset>
                <fieldset class="form-group">
                    <label for="pageContent">Content</label>
                    <textarea class="form-control" name="pageContent"><?php echo htmlspecialchars($text) ?></textarea>
                </fieldset>
                <fieldset class="form-group">
                    <label for="pageKeywords">Meta Keywords</label>
                    <input type="text" class="form-control" name="pageKeywords" id="pageKeywords" value="<?php echo $data->keywords ?>">
                </fieldset>
                <fieldset class="form-group">
                    <label for="pageDesc">Meta Description</label>
                    <input type="text" class="form-control" name="pageDesc" id="pageDesc" value="<?php echo $data->description ?>">
                </fieldset>
                <button name="submit" id="editpage" type="submit" class="btn btn-primary">Submit</button>
            </form>
            <?php
        } else {
            $allpages = $this->model->get_pages();
            echo('
            <div class="col-sm-11">
                <h2> Manage Pages </h2>
            </div>
            <div class="col-sm-1">
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
                foreach ($allpages as $showPage) {
                    //displaying posts
                    echo('<tr><td>' . $showPage['title'] . '</td>
                    <td>' . $showPage['url'] . ' </td>
                    <td> ' . substr(htmlspecialchars($showPage['content']), 0, 200) . '</td>
                    <td> ' . $showPage['ip'] . ' </td>
                    <td> ' . $showPage['time'] . ' </td>
                    <td> <a href="/admin/pages/edit/' . $showPage['page_id'] . '">Edit</a></td>
                    <td> <a onClick=\'ajaxCall("/admin/pages/delete/' . $showPage['page_id'] .'", "manage-pages")\'> Delete </a></td>
                    </tr>');
                } ?>
                </tbody>
            </table>
            <?php
        }
        ?>
    </div>
</div>
