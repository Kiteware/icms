<?php
class blog
{
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

    public function update_post($postName, $postContent, $postID)
    {
        $time        = time();

        $query = $this->db->prepare("UPDATE `posts` SET
								`post_name`	= ?,
								`post_date`		= ?,
                                `post_content`  = ?
								WHERE `post_id` = ?
								");

        $query->bindValue(1, $postName);
        $query->bindValue(2, $time);
        $query->bindValue(3, $postContent);
        $query->bindValue(4, $postID);

        try {
            $query->execute();

            return true;
        } catch (PDOException $e) {
            die($e->getMessage());

            return false;
        }
    }

    public function newBlogPost($postName,  $postContent)
    {
        $time        = time();
        $ip        = $_SERVER['REMOTE_ADDR']; // getting the users IP address

        $query    = $this->db->prepare('INSERT INTO posts (post_name, post_content, post_date) VALUES (:postName, :postContent, now())');

        try {
            $query->execute(array(
             ':postName' => $postName,
             ':postContent' => $postContent));

        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function get_post($id)
    {
        $query = $this->db->prepare("SELECT * FROM `posts` WHERE `post_id`= ? ");
        $query->bindValue(1, $id);

        try {

            $query->execute();

            return $query->fetch();

        } catch (PDOException $e) {

            die($e->getMessage());
        }

    }

    public function get_posts()
    {
        $query = $this->db->prepare("SELECT * FROM `posts` ORDER BY `post_date` DESC");

        try {
            $query->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        return $query->fetchAll();

    }
    public function get_posts_fetch()
    {
        $query = $this->db->prepare("SELECT * FROM `posts` ORDER BY `post_date` DESC");

        try {
            $query->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        return $query->fetch();

    }
    public function delete_posts($postID)
    {
        $query = $this->db->prepare('DELETE FROM posts WHERE post_id = ?');
        $query->bindValue(1, $postID);

        try {
            $query->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        return true;
    }
}
