<?php
/**
 * Control Center
 * For all site related notices
 */
if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
} ?>
<div id="content">
    <div class="box">
        <div class="box-header">Control Center</div>
        <div class="box-body">
            <h1>High Priority</h1>
            <?php
            if (file_exists("install.php")) {
                echo "<p>Install file should be deleted for security reasons.</p>";
            }
            ?>
            <h1>Info</h1>
            <?php
            echo '<p>Current script owner: ' . get_current_user().'</p>';

            ?>
        </div>
    </div>
</div>