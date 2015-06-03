<?php
namespace Nix\Icms\General;

class general
{
    public $user;
    public $user_id;
    public $settings;
    public function logged_in()
    {
        if (isset($_SESSION['id']) === true) {

            $user_id    = $_SESSION['id'];
            return true;
        }
        else {
            return false;
        }
    }

    public function logged_in_protect()
    {
        if ($this->logged_in() === true) {
            //header('Location: index.php');
            //exit();
        }
    }

    public function logged_out_protect()
    {
        if ($this->logged_in() === false) {
            //header('Location: index.php');
            //exit();
        }
    }

    public function file_newpath($path, $filename)
    {
        if ($pos = strrpos($filename, '.')) {
           $name = substr($filename, 0, $pos);
           $ext = substr($filename, $pos);
        } else {
           $name = $filename;
        }

        $newpath = $path.'/'.$filename;
        $newname = $filename;
        $counter = 0;

        while (file_exists($newpath)) {
           $newname = $name .'_'. $counter . $ext;
           $newpath = $path.'/'.$newname;
           $counter++;
        }

        return $newpath;
    }

    public function set($var)
    {
        if (isset($var)) {
            return $var;
        } else {
            return "";
        }
    }
}
