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
|   post_id, post_title, post_preview, post_content, post_date
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
    public function update_post($title, $content, $id, $description, $ip, $published)
    {
        $query = $this->db->prepare("UPDATE `posts`
                                    SET `post_title` = :title,
                                    `post_date`	    = FROM_UNIXTIME(:time),
                                    `post_content`  = :content,
                                    `post_ip` = :ip,
                                    `post_description` = :description,
                                    `post_published` = :published
                                    WHERE `post_id` = :id
                                    ");
        try {
            $query->execute(array(
                ':title' => $title,
                ':time' => time(),
                ':content' => $content,
                ':ip' => $ip,
                ':description' => $description,
                ':published' => $published,
                ':id' => $id
            ));
            return true;
        } catch (\PDOException $e) {
            die($e->getMessage());
            return false;
        }
    }

    public function newBlogPost($title,  $content, $ip, $description, $published)
    {
        $query  = $this->db->prepare('INSERT INTO posts (post_title, post_content, post_description, post_ip, post_published, post_date ) VALUES (:title, :content, :description, :ip, :published, FROM_UNIXTIME(:time))');

        try {
            $query->execute(array(
                ':title' => $title,
                ':content' => $content,
                ':description' => $description,
                ':ip' => $ip,
                ':published' => $published,
                ':time' => time()
            ));
            return true;
        } catch (\PDOException $e) {
            //die($e->getMessage());
            return false;
        }
    }

    public function get_post($id)
    {
        $query = $this->db->prepare('SELECT * FROM `posts` WHERE `post_id`= :id ');
        $query->bindValue(1, $id);

        try {
            $query->execute(array(
                ':id' => $id
            ));
            return $query->fetchAll();
        } catch (\PDOException $e) {
            //die($e->getMessage());
            return false;
        }
    }

    public function get_posts()
    {
        $query = $this->db->prepare('SELECT * FROM `posts` ORDER BY `post_date` DESC');

        try {
            $query->execute();
        } catch (\PDOException $e) {
            die($e->getMessage());
        }

        return $query->fetchAll();

    }
    public function get_posts_fetch()
    {
        $query = $this->db->prepare('SELECT * FROM `posts` ORDER BY `post_date` DESC');
        try {
            $query->execute();
        } catch (\PDOException $e) {
            die($e->getMessage());
        }

        return $query->fetch();

    }
    public function delete_posts($id)
    {
        $query = $this->db->prepare('DELETE FROM posts WHERE post_id = :id');

        try {
            $query->execute(array(
                ':id' => $id
            ));
            $query->execute();
            return true;
        } catch (\PDOException $e) {
            //die($e->getMessage());
            return false;
        }
    }
}
