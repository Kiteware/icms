<?php
require "../../core/init.php";
use Respect\Validation\Validator as v;

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
    //AJAX Request Detected
    if(crypt($_SESSION['token'], $_POST['token']) == $_POST['token']) {
        if (isset($_POST['action'])) {
            $post_name_validator = v::alnum();

            $config = HTMLPurifier_Config::createDefault();
            $purifier = new HTMLPurifier($config);

            $action = $_POST['action'];

            /**************************************************************
             * DELETE CONFIRMATION CHECK
             ***************************************************************/
            if ($action == "delete") {
                $postID = $_POST['postID']; //get post id

                //echo confirmation if successful
                if ($blog->delete_posts($postID)) {
                    echo("<script> successAlert(); history.go(-1);</script>");
                } else {
                    echo 'Delete Failed.';
                }
                /**************************************************************
                 * UPDATE BLOG POST
                 ***************************************************************/
            } elseif ($action == "update") {
                if (isset($_POST['postName'])) {
                    $postName = $_POST['postName'];
                } else {
                    $errors[] = 'Post Name is Required';
                }
                if (isset($_POST['html'])) {
                    $postContent = $purifier->purify($_POST['html']);
                } else {
                    $errors[] = 'Post Content is Required';
                }
                if (isset($_POST['postID'])) {
                    $postID = $_POST['postID'];
                } else {
                    $errors[] = 'Post ID is Required';
                }

                if ($post_name_validator->validate($postName) == false) {
                    $errors[] = 'Only Alphanumeric Values allowed in the post name ';
                }
                if (v::int()->validate($postID) == false) {
                    $errors[] = 'Post ID must be a valid int.';
                }
                if (empty($errors) === true) {
                    if ($blog->update_post($postName, $postContent, $postID)) {
                        echo("<script> successAlert();</script>");
                    }
                } elseif (empty($errors) === false) {
                    echo '<p>' . implode('</p><p>', $errors) . '</p>';
                }
            }
        }
    }
}