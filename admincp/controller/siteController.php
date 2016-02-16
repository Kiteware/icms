<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */

/*
|--------------------------------------------------------------------------
| Site Controller
|--------------------------------------------------------------------------
|
| For use on website related admin pages.
|   Settings
|   Template
*/
class siteController {
    public $model;
    public $user_id;
    public $fileName;
    public $user;
    public $content;
    public $template;
    private $settings;

    public function getName() {
        return 'site';
    }

    public function __construct(SiteModel $model) {
        $this->model = $model;
        $this->model->posts = $model->posts;
        $this->settings = $model->container['settings'];

    }

    public function success() {
        echo ("success");
    }

    public function settings() {
        if (isset($_POST['submit'])) {
            $failed = false;
            $failedArray = array();

            foreach ($_POST as $key => $field) {
                if (empty($field)) {
                    $failed = true;
                    $failedArray[] = $key;
                }
            }
            if ($failed == True) {
                echo '<div class="alert alert-danger" role="alert">
                    <p>Update failed! These fields need to be filled: ';
                foreach ($failedArray as $fail) {
                    echo $fail . " ";
                }
                echo '</p></div>';

            } else {
                $dbhost        = $_POST['dbhost'];
                $dbname        = $_POST['dbname'];
                $dbuser        = $_POST['dbuser'];
                $dbpass        = $this->settings->production->database->password;
                $dbport        = $_POST['dbport'];
                $emailAuth        = $_POST['emailAuth'];
                $emailHost        = $_POST['emailHost'];
                $emailPort        = $_POST['emailPort'];
                $emailUser        = $_POST['emailUser'];
                $emailClientID        = $_POST['emailClientID'];
                $emailClientSecret        = $_POST['emailClientSecret'];

                $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
                $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

                //Encryption is just a POC right now, still in development
                $secret_key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");

                $ciphertext_dec = base64_decode($dbpass);

                # retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
                $iv_dec = substr($ciphertext_dec, 0, $iv_size);

                # retrieves the cipher text (everything except the $iv_size in the front)
                $ciphertext_dec = substr($ciphertext_dec, $iv_size);

                # may remove 00h valued characters from end of plain text
                $decrypted_password = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $secret_key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);


                try {
                    $dbTestConnection = new \PDO('mysql:host='.$dbhost.';port='.$dbport.';dbname='.$dbname,
                        $dbuser,
                        $decrypted_password);
                    echo("<script> successAlert();</script>");
                } catch (PDOException $e) {
                    echo "Database connection failed ". $e;
                    die();
                }

                // config file
                $file = 'core/configuration.php';

                // Writing to config file
                $data =
"environment = production

[production]

site.name = \"" . $_POST['sitename'] . "\"
site.cwd = \"" . $_POST['cwd'] . "\"
site.url = \"" . $_POST['url'] . "\"
site.email = \"" . $_POST['email'] . "\"
site.template = \"default\"
site.version = \"0.5.1\"
database.name = \"" . $dbname . "\"
database.user = \"" . $dbuser . "\"
database.password = \"" . $dbpass . "\"
database.host = \"" . $dbhost . "\"
database.port = \"" . $dbport . "\"
email.auth = \"" . $emailAuth . "\"
email.host = \"" . $emailHost . "\"
email.port = \"" . $emailPort . "\"
email.user = \"" . $emailUser . "\"
email.clientid = \"" . $emailClientID . "\"
email.clientsecret = \"" . $emailClientSecret . "\"
email.refreshtoken = \"\"
debug = \"false\"";

                // Write the contents back to the file
                if (!file_put_contents($file, $data)) {
                    echo "The configuration file could not be created. Check Permissions";
                    die();
                }
                echo("<script> successAlert();</script>");
            }
        }
    }

    public function template() {
        $this->template = $this->model->getCurrentTemplatePath("default");
        if (isset($_POST['file'])) {
            $file = $_POST['file'];
        } else {
            $file = $this->template.'index.php';
        }
        if (isset($_POST['submit'])) {
            if (empty($errors) === true) {
                if($this->model->editTemplate($file, $_POST['templateContent'])){
                    echo("<script> successAlert();</script>");
                }
            }
        }
        $this->content = file_get_contents($file);
        $this->fileName = $file;
    }

    public function scan() {
    if (isset($_POST['cwd'])) {
            //Scan Root folder
            $files = scandir(".");
            $pages = scandir("pages/");
            //Scan Admin folder
            $dir=getcwd();
            $admin = scandir($dir);


            $lines = file('core/configuration.php');
            $result = '';

            foreach ($admin as $key=>&$value) {
                if (strlen($value) < 3) {
                    unset($admin[$key]);
                }
            }

            foreach($lines as $line) {
                if(substr($line, 0, 9) == 'site.core') {
                    $result .= "site.core = [".implode (", ", $files)."]\n";
                } elseif (substr($line, 0, 10) == 'site.pages'){
                    $result .= "site.pages = [".implode (", ", $pages)."]\n";
                } elseif (substr($line, 0, 10) == 'site.admin') {
                    $result .= "site.admin = [".implode (", ", $admin)."]\n";
                } else {
                    $result .= $line;
                }
            }
            file_put_contents('core/configuration.php', $result);
            echo("<script> successAlert();</script>");
        }
    }
    public function oauth() {
        //header("Location: /get_oauth_token.php");
        $redirectUri = "http://".$this->settings->production->site->url."/admin/site/oauth";
        //$redirectUri = "http://".$this->settings->production->site->url."/get_oauth_token.php";

        //These details obtained are by setting up app in Google developer console.
        $clientId = $this->settings->production->email->clientid;
        $clientSecret = $this->settings->production->email->clientsecret;
        require 'get_oauth_token.php';
        die();
    }
}
