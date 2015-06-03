<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */

/*
|--------------------------------------------------------------------------
| PagesModel
|--------------------------------------------------------------------------
|
| Pages Model Class - Called on /blog
|
*/

class pagesModel
{
    private $db;
    public $pages;
    public $action;
    public $id;

    public function __construct(\Pimple\Container $container) {
        $this->db = $container['db'];
    }

    public function get_page($id)
    {
            $query = $this->db->prepare("SELECT * FROM `pages` WHERE `page_id` = ?");

            $query->bindValue(1, $id);

            try {
                $query->execute();
            } catch (PDOException $e) {
                die($e->getMessage());
            }

            return $query->fetch();
    }
    public function get_pages()
    {
        $query = $this->db->prepare("SELECT * FROM `pages` ORDER BY `page_id` DESC");

        try {
            $query->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        return $query->fetchAll(PDO::FETCH_ASSOC);

    }
    public function create_page($title, $url, $content)
    {
        $time        = time();
        $ip        = $_SERVER['REMOTE_ADDR']; // getting the users IP address
        $url          = $url;
        $query    = $this->db->prepare("INSERT INTO `pages` (`title`, `url`, `content`, `ip`, `time`) VALUES (?, ?, ?, ?, ?) ");

        $query->bindValue(1, $title);
        $query->bindValue(2, $url);
        $query->bindValue(3, $content);
        $query->bindValue(4, $ip);
        $query->bindValue(5, $time);

        try {
            $query->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function edit_page($file, $cwd, $content)
    {
        try {
            $location = $cwd."/pages/".$file.".php";
            // save the text contents
            if(file_put_contents($location, $content)) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {

            die($e->getMessage());
        }

    }

    public function generate_page($title, $url, $content)
    {
        $url = "pages/".$url.".php";

        function getCurrentTemplatePath()
        {
            $templateName='default';
            return '../templates/'.$templateName.'/index.php';
        }
        $template = getCurrentTemplatePath();
        if ( copy($template,$url) ) {
            $data = file_get_contents($url);
            $data = str_replace("###CONTENT###", $content, $data);
            file_put_contents($url, $data);
        } else {
            //echo "Error generating file";
            echo error_get_last();
        }
    }
    public function delete_page($url)
    {
        $query    = $this->db->prepare("DELETE FROM `pages` WHERE `url`=?");
        $query->bindValue(1, $url);
        try {
            $query->execute();
            unlink(__DIR__."/pages/".$url.".php");
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    public function create_nav($name, $link, $position)
    {
        $query    = $this->db->prepare("INSERT INTO `navigation` (`nav_name`, `nav_link`, `nav_position`) VALUES (?, ?, ?) ");

        $query->bindValue(1, $name);
        $query->bindValue(2, $link);
        $query->bindValue(3, $position);
        try {
            $query->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    public function delete_nav($url)
    {
        $query    = $this->db->prepare("DELETE FROM `navigation` WHERE `nav_link`=?");

        $query->bindValue(1, $url);
        try {
            $query->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    public function update_nav($name, $link, $position)
    {
        $query    = $this->db->prepare("UPDATE `navigation` SET
                                                `nav_link`  =   ?,
                                                `nav_position`  =   ?
                                                WHERE `nav_name` = ?
                                                ");
        $query->bindValue(1, $link);
        $query->bindValue(2, $position);
        $query->bindValue(4, $name);
        try {
            $query->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    public function list_nav()
    {
        $query = $this->db->prepare("SELECT * FROM `navigation` ORDER BY `nav_position` ASC");

        try {
            $query->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        return $query->fetchAll(\PDO::FETCH_ASSOC);

    }
 }
