<?php

include ('header.html');
include ('db_connect.php');

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
                //Insert data into db
                        $conn = connect();

                        $query = 'INSERT INTO posts (post_name, post_preview, post_content, post_date)
                                 VALUES (:postName, :postPreview, :postContent, now())';

                        $statement= $conn->prepare($query);

                        if( $statement->execute(array(
                                ':postName' => $postName,
                                ':postPreview' => $postPreview,
                                ':postContent' => $postContent))
                        ) //If successful return to admin index
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
<?php
include ('footer.html');
?>
