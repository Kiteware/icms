<?php
namespace Nixhatter\ICMS\model;

/**
 * ICMS - Intelligent Content Management System
 *
 * Called on /blog
 * Posts database consists of:
 *      post_id, post_title, post_preview, post_content, post_date
 * @package ICMS
 * @author Dillon Aykac
 */

defined('_ICMS') or die;

class BlogModel extends Model
{
    public $container;

    public function __construct(\Pimple\Container $container)
    {
        $this->container = $container;
        $this->db = $container['db'];
    }
    public function update_post($title, $content, $id, $description, $ip, $published, $author, $tags)
    {
        $query = $this->db->prepare("UPDATE `posts`
                                    SET `post_title` = :title,
                                    `post_date`	    = FROM_UNIXTIME(:time),
                                    `post_content`  = :content,
                                    `post_ip` = :ip,
                                    `post_description` = :description,
                                    `post_published` = :published,
                                    `post_author` = :author,
                                    `post_tags` = :tags
                                    WHERE `post_id` = :id");
        try {
            $query->execute(array(
                ':title' => $title,
                ':time' => time(),
                ':content' => $content,
                ':ip' => $ip,
                ':description' => $description,
                ':published' => $published,
                ':author'   => $author,
                ':tags'   => $tags,
                ':id' => $id
            ));
            return true;
        } catch (\PDOException $e) {
            //$this->error($e->getMessage());
            return false;
        }
    }

    public function newBlogPost($title, $content, $ip, $description, $published, $author, $tags)
    {
        $query  = $this->db->prepare('INSERT INTO posts (post_title, post_content, post_description, post_ip, post_published, post_date, post_author, post_tags) 
        VALUES (:title, :content, :description, :ip, :published, FROM_UNIXTIME(:time), :author, :post_tags)');

        try {
            $query->execute(array(
                ':title' => $title,
                ':content' => $content,
                ':description' => $description,
                ':ip' => $ip,
                ':published' => $published,
                ':time' => time(),
                ':author' => $author,
                ':post_tags' => $tags
            ));
            return true;
        } catch (\PDOException $e) {
            //$this->error($e->getMessage());
            return false;
        }
    }

    public function get_post($id)
    {
        $query = $this->db->prepare('SELECT * FROM `posts` WHERE `post_id`= :id');
        $query->bindValue(1, $id);

        try {
            $query->execute(array(
                ':id' => $id
            ));
            return $query->fetchAll();
        } catch (\PDOException $e) {
            //$this->error($e->getMessage());
            return false;
        }
    }

    public function get_published()
    {
        $query = $this->db->prepare('SELECT * FROM `posts` WHERE `post_published` = 1 ORDER BY `post_date` DESC');

        try {
            $query->execute();
        } catch (\PDOException $e) {
            $this->error($e->getMessage());
        }

        return $query->fetchAll();
    }

    public function get_posts()
    {
        $query = $this->db->prepare('SELECT * FROM `posts` ORDER BY `post_date` DESC');

        try {
            $query->execute();
        } catch (\PDOException $e) {
            $this->error($e->getMessage());
        }

        return $query->fetchAll();
    }

    public function get_posts_by_tag($tag)
    {
        $query = $this->db->prepare('SELECT * FROM `posts` WHERE `post_tags` LIKE :post_tags OR `post_tags` = :tag ORDER BY `post_date` DESC');
        $query->bindValue(':post_tags', '% '.$tag.'%');
        $query->bindValue(':tag', $tag);
        try {
            $query->execute();
        } catch (\PDOException $e) {
            $this->error($e->getMessage());
        }

        return $query->fetchAll();
    }

    public function get_posts_fetch()
    {
        $query = $this->db->prepare('SELECT * FROM `posts` ORDER BY `post_date` DESC');
        try {
            $query->execute();
        } catch (\PDOException $e) {
            $this->error($e->getMessage());
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
            //$this->error($e->getMessage());
            return false;
        }
    }
}
