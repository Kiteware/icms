<?php
namespace Nixhatter\ICMS\model;

/**
 * Pages Model
 * Includes:
 * + flat file generation
 * + database changes to the pages table
 * + menu manager
 *
 * @package ICMS
 * @author Dillon Aykac
 */

defined('_ICMS') or die;

class PagesModel extends Model
{
    public $pages;
    public $id;
    public $users;
    public $container;
    private $template;
    private $purifier;

    public function __construct(\Pimple\Container $container)
    {
        $this->container = $container;
        $this->db = $container['db'];
        $this->users = new UserModel($container);
        $config = \HTMLPurifier_Config::createDefault();
        $this->purifier = new \HTMLPurifier($config);
        $this->template = $this->container['settings']->production->site->template;
    }

    public function get_page($id)
    {
        $query = $this->db->prepare("SELECT * FROM `pages` WHERE `page_id` = ?");

        $query->bindValue(1, $id);

        try {
            $query->execute();
        } catch (\PDOException $e) {
           $this->error($e->getMessage());
        }

        return $query->fetch();
    }

    public function get_pages()
    {
        $query = $this->db->prepare("SELECT * FROM `pages` ORDER BY `page_id` DESC");

        try {
            $query->execute();
        } catch (\PDOException $e) {
           $this->error($e->getMessage());
        }

        return $query->fetchAll(\PDO::FETCH_ASSOC|\PDO::FETCH_UNIQUE);

    }

    public function generate_page($file, $content)
    {
        try {
            $tempIndex = $this->getTemplatePath($this->template);

            $Parsedown = new \Parsedown();
            $parsedContent =  $Parsedown->text($content);
            $pureContent = $this->purifier->purify($parsedContent);


            $tempIndexContent = file_get_contents($tempIndex);
            $finalPage = str_replace("###CONTENT###", $pureContent, $tempIndexContent);

            $location = "templates/" . $this->template . "/" . $file . ".php";
            $locationData = "templates/" . $this->template . "/" . $file . ".data";
            touch($locationData);

            if (file_put_contents($location, $finalPage)) {
                return true;
            }

            return false;

        } catch (\PDOException $e) {
           $this->error($e->getMessage());
        }
    }

    private function getTemplatePath($template)
    {
        return 'templates/' . $template . '/index.php';
    }

    /**
     * @param $title
     * @param $url
     * @param $content
     *
     * Create a Page
     *  Add an entry into the database
     *  Create the flat file based on the template
     *
     * TODO: Refactor
     */
    public function new_page($title, $url, $content)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $query = $this->db->prepare("INSERT INTO `pages` (`title`, `url`, `content`, `ip`, `time`) VALUES (?, ?, ?, ?, FROM_UNIXTIME(?)) ");

        $query->bindValue(1, $title);
        $query->bindValue(2, $url);
        $query->bindValue(3, $content);
        $query->bindValue(4, $ip);
        $query->bindValue(5, time());

        try {
            $query->execute();
        } catch (\PDOException $e) {
            return false;
        }

        return $this->generate_page($url, $content);
    }

    public function update_page($title, $content, $url, $pid)
    {
        $ip = $_SERVER['REMOTE_ADDR'];

        $query = $this->db->prepare("UPDATE `pages`
                                    SET `title` = :title,
                                    `url`       = :url,
                                    `time`	    = FROM_UNIXTIME(:time),
                                    `content`   = :content,
                                    `ip`        = :ip
                                    WHERE `page_id` = :pid
                                    ");
        try {
            $query->execute(array(
                ':title' => $title,
                ':url'   => $url,
                ':time' => time(),
                ':content' => $content,
                ':ip' => $ip,
                ':pid' => $pid
            ));
            return $this->generate_page($url, $content);
        } catch (\PDOException $e) {
            return false;
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
        $page = $this->get_page($id);
        $query = $this->db->prepare("DELETE FROM `pages` WHERE `page_id`=?");
        $query->bindValue(1, $id);
        try {
            $query->execute();
            unlink("templates/". $this->template ."/" . $page['url'] . ".php");
            unlink("templates/". $this->template ."/" . $page['url'] . ".data");
            $this->delete_nav($page['url']);
            $this->users->delete_all_page_permissions($page['url']);
            return true;
        } catch (\PDOException $e) {
            return false;
           $this->error($e->getMessage());
        }
    }

    public function create_nav($name, $link, $position, $parent)
    {
        $query = $this->db->prepare("INSERT INTO `navigation` (`nav_name`, `nav_link`, `nav_position`, `parent`) VALUES (?, ?, ?, ?) ");

        $query->bindValue(1, $name);
        $query->bindValue(2, $link);
        $query->bindValue(3, $position);
        $query->bindValue(4, $parent);
        try {
            $query->execute();
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function delete_nav($nav_id)
    {
        $query = $this->db->prepare("DELETE FROM `navigation` WHERE `nav_id`=?");

        $query->bindValue(1, $nav_id);
        try {
            $query->execute();
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function update_nav($name, $link, $parent, $position, $nav_id)
    {
        $query = $this->db->prepare("UPDATE `navigation` SET
                                                `nav_name`      = ?,
                                                `nav_link`      = ?,
                                                `nav_position`  = ?,
                                                `parent`        = ?
                                                WHERE `nav_id` = ?
                                                ");
        $query->bindValue(1, $name);
        $query->bindValue(2, $link);
        $query->bindValue(3, $position);
        $query->bindValue(4, $parent);
        $query->bindValue(5, $nav_id);
        try {
            $query->execute();
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function list_nav()
    {
        $query = $this->db->prepare("SELECT * FROM `navigation` ORDER BY `nav_position` ASC");

        try {
            $query->execute();
        } catch (\PDOException $e) {
           $this->error($e->getMessage());
        }

        $menus = $query->fetchAll(\PDO::FETCH_ASSOC);

        foreach($menus as $key => $menu) {
            $menus[$key]['nav_link'] = $this->addhttp($menu['nav_link']);
        }
        return $menus;

    }

    public function listNavAdmin()
    {
        $query = $this->db->prepare("SELECT * FROM `navigation` ORDER BY `nav_id` ASC, `nav_position`");

        try {
            $query->execute();
        } catch (\PDOException $e) {
           $this->error($e->getMessage());
        }

        $menus = $query->fetchAll(\PDO::FETCH_ASSOC);

        foreach($menus as $key => $menu) {
            $menus[$key]['nav_link'] = $this->addhttp($menu['nav_link']);
        }
        return $menus;

    }

    public function addhttp($url) {
        if (substr($url, 0, 7) === 'http://') {
            $short_url = substr($url, 7);
            $url = "http://" . urlencode($short_url) ;
        } elseif (substr($url, 0, 8) === 'https://') {
            $short_url = substr($url, 8);
            $url = "https://" . urlencode($short_url) ;
        } elseif (substr($url, 0, 1) !== '/') {
            $url = "/" . urlencode($url) ;
        }
        return $url;
    }


    public function editPageData($page, $metadata)
    {
        if (file_exists($page . '.data')) {

            $reading = fopen($page . '.data', 'r');
            $writing = fopen($page . '.tmp', 'w');

            $replaced = false;

            while (!feof($reading)) {
                foreach ($metadata as $key => $value) {
                    $line = fgets($reading);
                    if (stristr($line, $key)) {
                        $line = $key . " = \"" . $value . "\"\r\n";
                        $replaced = true;
                    }
                    fwrite($writing, $line);
                }
            }
            fclose($reading);
            fclose($writing);
            // might as well not overwrite the file if we didn't replace anything
            if ($replaced) {
                rename($page . '.tmp', $page . '.data');
            } else {
                unlink($page . '.tmp');
            }
        } else {
            $data = "";
            foreach ($metadata as $key => $value) {
                $data .= $key . " = \"" . $value . "\"\r\n";
            }
            return file_put_contents($page. '.data', $data);
        }
    }
}
