<?php
require '../core/init.php';

        echo ('<h2>Write New Post</h2>');

        // check for a submitted form
        if(isset($_POST['add_post'])){
                $postName = $_POST['postName'];
                $postPreview = $_POST['postPreview'];
                $postContent = $_POST['postContent'];

                //Check to make sure fields are filled in       
                if(empty($postName) OR empty($postPreview) OR empty ($postContent)){
                        echo ('Make sure you filled out all the fields!');
                }
                else{
                
					$blog->newBlogPost($postName, $postPreview, $postContent);
					{ header("Location: index.php");}
                }
        }
?>

<form action="" method="post" name="post">
        <p>Name:<br />
        <input name="postName" type="text" size="45" />
        </p>

        <p>Preview:<br />
        <textarea name="postPreview" cols="100" rows="3"></textarea>
        </p>

        <p>Content:<br />
        <textarea name="postContent" cols="100" rows="10"></textarea>
        </p>

        <input name="add_post" type="submit" value="Add Post"/>
</form>
