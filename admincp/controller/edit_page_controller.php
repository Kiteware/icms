<?php
require "../../core/init.php";
use Respect\Validation\Validator as v;

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
    //AJAX Request Detected
    if (crypt($_SESSION['token'], $_POST['token']) == $_POST['token']) {
        if (isset($_POST['action'])) {
            $action = $_POST['action'];

            if (isset($_POST['pageURL'])) {
                $pageUrl = $_POST['pageURL'];
            } else {
                $errors[] = 'URL is Required';
            }
            if (empty($errors) === true) {
                //User wants to delete the given URL
                if ($action == "delete") {
                    $pages->delete_nav("index.php?page=".$pageUrl);
                    $permissions->delete_all_page_permissions($pageUrl);
                            if ($pages->delete_page($pageUrl, $settings->production->site->cwd)) {
                                echo("<script> successAlert();history.go(-1);</script>");
                            } else {
                                $errors[] = 'Delete page MAY HAVE failed. Return to the Edit pages to find out! ';
                            }
                }
                //User has edited a file and wants to save it
                elseif ($action == "update") {
                    if (isset($_POST['text'])) {
                        $text = $_POST['text'];
                    if($pages->edit_page($pageUrl, $settings->production->site->cwd, $text)) {
                        echo("<script> successAlert();</script>");
                    } else {
                        $errors[] = 'Failed updating the page.';
                    }
                    } else {
                        $errors[] = 'Text is Required';
                    }

                }
            }
        }
        if (empty($errors) === false) {
            echo '<p>' . implode('</p><p>', $errors) . '</p>';
        }
    }
}