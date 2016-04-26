<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */

/*
|--------------------------------------------------------------------------
| BlogModel
|--------------------------------------------------------------------------
|
| Called on /blog
| Posts database consists of:
|   post_id, post_name, post_preview, post_content, post_date
|
*/
namespace Nixhatter\ICMS\model;

class BlogModel extends Model {
    public $posts;
    public $container;

    public function __construct(\Pimple\Container $container) {
        $this->container = $container;
        $this->db = $container['db'];
    }
    public function update_post($postName, $postContent, $postID)
    {
        $query = $this->db->prepare("UPDATE `posts` SET
								`post_name`	    = ?,
								`post_date`	    = FROM_UNIXTIME(?),
                                `post_content`  = ?
								WHERE `post_id` = ?
								");

        $query->bindValue(1, $postName);
        $query->bindValue(2, time());
        $query->bindValue(3, htmlspecialchars($postContent));
        $query->bindValue(4, $postID);

        try {
            $query->execute();
            return true;
        } catch (\PDOException $e) {
            die($e->getMessage());
            return false;
        }
    }

    public function newBlogPost($postName,  $postContent)
    {
        $query  = $this->db->prepare('INSERT INTO posts (post_name, post_content, post_date) VALUES (:postName, :postContent, FROM_UNIXTIME(:time))');

        try {
            $query->execute(array(
                ':postName' => $postName,
                ':postContent' => htmlspecialchars($postContent),
                ':time' => time()
            ));
            return true;
        } catch (\PDOException $e) {
            return false;
            //die($e->getMessage());
        }
    }

    public function get_post($id)
    {
        $query = $this->db->prepare("SELECT * FROM `posts` WHERE `post_id`= ? ");
        $query->bindValue(1, $id);

        try {
            $query->execute();
            return $query->fetchAll();
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }

    public function get_posts()
    {
        $query = $this->db->prepare("SELECT * FROM `posts` ORDER BY `post_date` DESC");

        try {
            $query->execute();
        } catch (\PDOException $e) {
            die($e->getMessage());
        }

        return $query->fetchAll();

    }
    public function get_posts_fetch()
    {
        $query = $this->db->prepare("SELECT * FROM `posts` ORDER BY `post_date` DESC");
        try {
            $query->execute();
        } catch (\PDOException $e) {
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
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
        return true;
    }
}
