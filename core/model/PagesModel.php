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
use Respect\Validation\Validator as v;


class PagesModel extends Model {
    public $pages;
    public $id;
    public $users;
    public $container;
    private $purifier;
    private $parsedown;

    public function __construct(\Pimple\Container $container) {
        $this->container = $container;
        $this->db = $container['db'];
        $this->users = new UserModel($container);
        $config = \HTMLPurifier_Config::createDefault();
        $this->purifier = new \HTMLPurifier($config);
        $this->parsedown = new \Parsedown();
    }

    public function get_page($id)
    {
            $query = $this->db->prepare("SELECT * FROM `pages` WHERE `page_id` = ?");

            $query->bindValue(1, $id);

            try {
                $query->execute();
            } catch (\PDOException $e) {
                exit($e->getMessage());
            }

            return $query->fetch();
    }
    public function get_pages()
    {
        $query = $this->db->prepare("SELECT * FROM `pages` ORDER BY `page_id` DESC");

        try {
            $query->execute();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }

        return $query->fetchAll(\PDO::FETCH_ASSOC);

    }

    public function edit_page($file, $content)
    {
        try {
            $template = $this->container['settings']->production->site->template;
            $location = "templates/".$template."/".$file.".php";
            /*if(!file_exists($location)) {
                touch($location);
            }*/
            //$pageContent = $this->parsedown->text($content);
            if(file_put_contents($location, $content)) {
                return true;
            }
            return false;
        } catch (\PDOException $e) {

            exit($e->getMessage());
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
        $pageContent = $this->parsedown->text($this->purifier->purify($content));
        $ip        = $_SERVER['REMOTE_ADDR']; // getting the users IP address
        $query    = $this->db->prepare("INSERT INTO `pages` (`title`, `url`, `content`, `ip`, `time`) VALUES (?, ?, ?, ?, FROM_UNIXTIME(?)) ");

        $query->bindValue(1, $title);
        $query->bindValue(2, $url);
        $query->bindValue(3, $pageContent);
        $query->bindValue(4, $ip);
        $query->bindValue(5, time());

        try {
            $query->execute();
        } catch (\PDOException $e) {
            exit($e->getMessage());
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
            $data = str_replace("###CONTENT###", $pageContent, $data);
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
            $this->delete_nav("/user/".$page['url']);
            $this->users->delete_all_page_permissions($page['url']);
            return true;
        } catch (\PDOException $e) {
            return false;
            exit($e->getMessage());
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
            //exit($e->getMessage());
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
            //exit($e->getMessage());
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
            //exit($e->getMessage());
        }
    }
    public function list_nav()
    {
        $query = $this->db->prepare("SELECT * FROM `navigation` ORDER BY `nav_position` ASC");

        try {
            $query->execute();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }

        return $query->fetchAll(\PDO::FETCH_ASSOC);

    }

    public function editPageData($page, $key, $value) {
        if (v::alpha('.')->validate($key)) {
            $reading = fopen('pages/'.$page.'.data', 'r');
            $writing = fopen('pages/'.$page.'.tmp', 'w');

            $replaced = false;

            while (!feof($reading)) {
                $line = fgets($reading);
                if (stristr($line, $key)) {
                    $line = $key . " = \"" . $value . "\"\r\n";
                    $replaced = true;
                }
                fputs($writing, $line);
            }
            fclose($reading);
            fclose($writing);
            // might as well not overwrite the file if we didn't replace anything
            if ($replaced) {
                rename('pages/'.$page.'.tmp', 'pages/'.$page.'.data');
            } else {
                unlink('pages/'.$page.'.tmp');
            }
        }
    }
 }
