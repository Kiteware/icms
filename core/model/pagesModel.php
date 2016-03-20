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
namespace Nixhatter\ICMS\model;

class pagesModel extends Model {
    public $pages;
    public $action;
    public $id;
    public $users;
    public $container;
    private $purifier;

    public function __construct(\Pimple\Container $container) {
        $this->container = $container;
        $this->db = $container['db'];
        $this->users = new UserModel($container);
        $config = \HTMLPurifier_Config::createDefault();
        $this->purifier = new \HTMLPurifier($config);
    }

    public function get_page($id)
    {
            $query = $this->db->prepare("SELECT * FROM `pages` WHERE `page_id` = ?");

            $query->bindValue(1, $id);

            try {
                $query->execute();
            } catch (\PDOException $e) {
                die($e->getMessage());
            }

            return $query->fetch();
    }
    public function get_pages()
    {
        $query = $this->db->prepare("SELECT * FROM `pages` ORDER BY `page_id` DESC");

        try {
            $query->execute();
        } catch (\PDOException $e) {
            die($e->getMessage());
        }

        return $query->fetchAll(\PDO::FETCH_ASSOC);

    }

    public function edit_page($file, $cwd, $content)
    {
        try {
            $pageContent = $this->purifier->purify($content);
            $location = $cwd."/pages/".$file.".php";
            // save the text contents
            if(file_put_contents($location, $pageContent)) {
                return true;
            } else {
                return false;
            }
        } catch (\PDOException $e) {

            die($e->getMessage());
        }

    }

    /**
     * @param $title
     * @param $url
     * @param $content
     *
     * Create a Page
     *  Add an entry into the database
     *  Create the flat file based on the template
     */
    public function generate_page($title, $url, $content)
    {
        $pageContent = $this->purifier->purify($content);
        $ip        = $_SERVER['REMOTE_ADDR']; // getting the users IP address
        $query    = $this->db->prepare("INSERT INTO `pages` (`title`, `url`, `content`, `ip`, `time`) VALUES (?, ?, ?, ?, ?) ");

        $query->bindValue(1, $title);
        $query->bindValue(2, $url);
        $query->bindValue(3, $pageContent);
        $query->bindValue(4, $ip);
        $query->bindValue(5, time());

        try {
            $query->execute();
        } catch (\PDOException $e) {
            die($e->getMessage());
        }

        $url = "pages/".$url.".php";

        function getCurrentTemplatePath()
        {
            $templateName='default';
            return 'templates/'.$templateName.'/index.php';
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

    /**
     * @param $id
     * Deleting a Page
     *  Delete the page from the db
     *  Delete the flat file
     *  Delete the navigation item from the db
     *  Should not fail if any of these don't exist
     */
    public function delete_page($id)
    {
        $page   = $this->get_page($id);
        $query    = $this->db->prepare("DELETE FROM `pages` WHERE `page_id`=?");
        $query->bindValue(1, $id);
        try {
            $query->execute();
            unlink("pages/".$page['url'].".php");
            $this->delete_nav("/pages/".$page['url']);
            $this->users->delete_all_page_permissions($page['url']);
            return true;
        } catch (\PDOException $e) {
            return false;
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
            return true;
        } catch (\PDOException $e) {
            return false;
            //die($e->getMessage());
        }
    }
    public function delete_nav($url)
    {
        $query    = $this->db->prepare("DELETE FROM `navigation` WHERE `nav_link`=?");

        $query->bindValue(1, $url);
        try {
            $query->execute();
            return true;
        } catch (\PDOException $e) {
            return false;
            //die($e->getMessage());
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
            return true;
        } catch (\PDOException $e) {
            return false;
            //die($e->getMessage());
        }
    }
    public function list_nav()
    {
        $query = $this->db->prepare("SELECT * FROM `navigation` ORDER BY `nav_position` ASC");

        try {
            $query->execute();
        } catch (\PDOException $e) {
            die($e->getMessage());
        }

        return $query->fetchAll(\PDO::FETCH_ASSOC);

    }
 }
