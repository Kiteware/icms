<?php
namespace Nixhatter\ICMS\admin\controller;
use Nixhatter\ICMS\model;
if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
}
/*
|--------------------------------------------------------------------------
| Site Controller
|--------------------------------------------------------------------------
|
| For use on website related admin pages.
|   Settings
|   Template
*/
class siteController extends Controller{
    public $model;
    public $user_id;
    public $fileName;
    public $user;
    public $content;
    public $template;
    public $pages;
    private $settings;

    public function getName() {
        return 'site';
    }

    public function __construct(model\SiteModel $model) {
        $this->model = $model;
        $this->model->posts = $model->posts;
        $this->settings = $model->container['settings'];
        $this->pages = "pages/";

    }

    public function success() {
        echo ("success");
    }

    public function settings() {
        if (isset($_POST['submit'])) {
            $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
            $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

            //Encryption is just a POC right now, still in development
            $secret_key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");

            $failed = false;
            $failedArray = array();

//            foreach ($_POST as $key => $field) {
//                if (empty($field)) {
//                    $failed = true;
//                    $failedArray[] = $key;
//                }
//            }
            if ($failed == True) {
                echo '<div class="alert alert-danger" role="alert">
                    <p>Could not save changes! These fields need to be filled: ';
                foreach ($failedArray as $fail) {
                    echo $fail . " ";
                }
                echo '</p></div>';

            } else {
                $siteName = !empty($_POST['sitename']) ? $this->postValidation($_POST['sitename']) : NULL;
                $siteDesc   = !empty($_POST['sitedesc']) ? $this->postValidation($_POST['sitedesc']): NULL;
                $siteCWD    = !empty($_POST['cwd']) ? $this->slashValidation($_POST['cwd']) : NULL;
                $siteURL    = !empty($_POST['url']) ? $this->dotslashValidation($_POST['url']) : NULL;
                $siteEmail  = !empty($_POST['email']) ? $this->postValidation($_POST['email']) : NULL;
                $siteTemplate = !empty($_POST['template']) ?  $this->strictValidation($_POST['template']) : NULL;
                $dbhost     = !empty($_POST['dbhost'])  ?  $this->dotslashValidation($_POST['dbhost']) : NULL;
                $dbname     = !empty($_POST['dbname']) ? $this->strictValidation($_POST['dbname']) : NULL;
                $dbuser     = !empty($_POST['dbuser']) ? $this->strictValidation($_POST['dbuser']) : NULL;
                $dbport     = !empty($_POST['dbport']) ? $this->strictValidation($_POST['dbport']) : NULL;
                $emailHost  = !empty($_POST['emailHost']) ? $this->dotslashValidation($_POST['emailHost']) : NULL;
                $emailPort  = !empty($_POST['emailPort']) ? $this->postValidation($_POST['emailPort']): NULL;
                $emailUser  = !empty($_POST['emailUser']) ?$this->postValidation($_POST['emailUser']): NULL;
                $mailchimpapi = !empty($_POST['mailchimpapi']) ? $this->postValidation($_POST['mailchimpapi']): NULL;
                $mailchimplistid = !empty($_POST['mailchimplistid']) ? $this->postValidation($_POST['mailchimplistid']): NULL;


                if (!empty($_POST['emailClientID']) && !empty($_POST['emailClientSecret'])) {
                    $emailAuth = "XOAUTH2";
                    $emailClientID      = $_POST['emailClientID'];
                    $emailClientSecret  = $_POST['emailClientSecret'];
                    $this->model->hasConfigChanged("email", "clientid", $emailClientID);
                    $this->model->hasConfigChanged("email", "clientsecret", $emailClientSecret);
                } else if (!empty($_POST['emailPassword'])) {
                    $emailAuth      = "BASIC";
                    $emailPass      = $_POST['emailPassword'];
                    $this->model->hasConfigChanged("email", "pass", $emailPass);
                }

                if($_POST['dbpass'] != "unchanged") {
                    $dbpass = $_POST['dbpass'];
                    $encrypted_string = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $secret_key, $dbpass, MCRYPT_MODE_CBC, $iv);
                    $encrypted_string = $iv . $encrypted_string;
                    $dbpass = base64_encode($encrypted_string);
                    $config = "database.password";
                    $this->model->editConfig($config, $dbpass);
                } else {
                    $dbpass        = $this->settings->production->database->password;
                }

                $ciphertext_dec = base64_decode($dbpass);

                # retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
                $iv_dec = substr($ciphertext_dec, 0, $iv_size);

                # retrieves the cipher text (everything except the $iv_size in the front)
                $ciphertext_dec = substr($ciphertext_dec, $iv_size);

                # may remove 00h valued characters from end of plain text
                $decrypted_password = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $secret_key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);


                try {
                    $dbTestConnection = new \PDO('mysql:host='.$dbhost.';port='.$dbport.';dbname='.$dbname, $dbuser, $decrypted_password);
                    if($dbTestConnection) $response = array('result' => "success", 'message' => 'Updated Settings');
                } catch (\PDOException $e) {
                    $response = array('result' => "fail", 'message' => "Database connection failed ". $e);
                }

                $this->model->hasConfigChanged("site", "name", $siteName);
                $this->model->hasConfigChanged("site", "description", $siteDesc);
                $this->model->hasConfigChanged("site", "cwd", $siteCWD);
                $this->model->hasConfigChanged("site", "url", $siteURL);
                $this->model->hasConfigChanged("site", "email", $siteEmail);
                $this->model->hasConfigChanged("site", "template", $siteTemplate);
                $this->model->hasConfigChanged("database", "host", $dbhost);
                $this->model->hasConfigChanged("database", "name", $dbname);
                $this->model->hasConfigChanged("database", "port", $dbport);
                $this->model->hasConfigChanged("database", "user", $dbuser);
                $this->model->hasConfigChanged("database", "password", $dbpass);
                $this->model->hasConfigChanged("email", "auth", $emailAuth);
                $this->model->hasConfigChanged("email", "host", $emailHost);
                $this->model->hasConfigChanged("email", "port", $emailPort);
                $this->model->hasConfigChanged("email", "user", $emailUser);


                $this->model->hasConfigChanged("addons", "mailchimpapi", $mailchimpapi);
                $this->model->hasConfigChanged("addons", "mailchimplistid", $mailchimplistid);

                exit(json_encode($response));
            }
        }
    }

    public function template() {
        $this->template = $this->model->getCurrentTemplatePath("default");
        if (!empty($_POST['file'])) {
            $file = $this->fileValidation($_POST['file']);
        } else {
            $file = $this->template.'index.php';
        }
        if (isset($_POST['submit'])) {
            // TODO: Sanitize TemplateContent
                if($this->model->editTemplate($file, $_POST['templateContent'])){
                    $response = array('result' => "success", 'message' => 'Updated Template');
                    echo(json_encode($response));
                    exit();
                }

        }
        $this->content = file_get_contents($file);
        $this->fileName = $file;
    }

    // Depreciated
    public function scan() {
        if (isset($_GET['scan'])) {
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
            $response = array('result' => "success", 'message' => 'Scanned Successfully');
            echo(json_encode($response));
            exit();
        }
    }

    //TODO: A security orientated dashboard
    public function controlcenter() {

    }

    // For getting Googles oauth token
    public function oauth() {
        //header("Location: /get_oauth_token.php");
        $redirectUri = $this->settings->production->site->url."/admin/site/oauth";
        //$redirectUri = "http://".$this->settings->production->site->url."/get_oauth_token.php";

        //These details obtained are by setting up app in Google developer console.
        $clientId = $this->settings->production->email->clientid;
        $clientSecret = $this->settings->production->email->clientsecret;
        require 'get_oauth_token.php';
        //header('Location: /admin/site/settings');
        exit();
    }

    // Defines the CSS Class for an active variable
    public function isActive($variable) {
        if (!empty($variable)) {
            return "active";
        } else {
            return "";
        }
    }

    /**
     * Automatic Sitemap Generation
     * It uses a crawler that follows all links and
     * then generates the sitemap in the root directory.
     */
    public function sitemap() {
        include_once ("core/admin/controller/Sitemap.php");
        new \Sitemap($this->settings->production->site->url, "daily");
    }

    /**
     * Combine all required CSS files and minify them.
     */
    public function minifyCSS() {
        $mincss = "";

        // CSS Minifier => https://gist.github.com/tovic/d7b310dea3b33e4732c0
        function minify_css($input) {
            if(trim($input) === "") return $input;
            // Force white-space(s) in `calc()`
            if(strpos($input, 'calc(') !== false) {
                $input = preg_replace_callback('#(?<=[\s:])calc\(\s*(.*?)\s*\)#', function($matches) {
                    return 'calc(' . preg_replace('#\s+#', "\x1A", $matches[1]) . ')';
                }, $input);
            }
            return preg_replace(
                array(
                    // Remove comment(s)
                    '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
                    // Remove unused white-space(s)
                    '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
                    // Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
                    '#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
                    // Replace `:0 0 0 0` with `:0`
                    '#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
                    // Replace `background-position:0` with `background-position:0 0`
                    '#(background-position):0(?=[;\}])#si',
                    // Replace `0.6` with `.6`, but only when preceded by a white-space or `=`, `:`, `,`, `(`, `-`
                    '#(?<=[\s=:,\(\-]|&\#32;)0+\.(\d+)#s',
                    // Minify string value
                    '#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][-\w]*?)\2(?=[\s\{\}\];,])#si',
                    '#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
                    // Minify HEX color code
                    '#(?<=[\s=:,\(]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
                    // Replace `(border|outline):none` with `(border|outline):0`
                    '#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
                    // Remove empty selector(s)
                    '#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s',
                    '#\x1A#'
                ),
                array(
                    '$1',
                    '$1$2$3$4$5$6$7',
                    '$1',
                    ':0',
                    '$1:0 0',
                    '.$1',
                    '$1$3',
                    '$1$2$4$5',
                    '$1$2$3',
                    '$1:0',
                    '$1$2',
                    ' '
                ),
                $input);
        }

        unlink("templates/".$this->settings->production->site->template."/css/style.min.css");
        foreach (glob("templates/".$this->settings->production->site->template."/css/*.css") as $file) {
            $stylesheet = file_get_contents ($file);
            $stylesheet = minify_css($stylesheet);
            $mincss .= $stylesheet;
        }

        if(file_put_contents("templates/".$this->settings->production->site->template."/css/style.min.css",$mincss)) {
            $response = array('result' => "success", 'message' => 'style.min.css generated successfully.');
        } else {
            $response = array('result' => "failed", 'message' => 'style.min.css could not be generated');
        }
        echo(json_encode($response));
        exit();
    }

    // TODO: Find a working expression to minify JS
    public function minifyJS() {
        $minjs = "";

        // JavaScript Minifier -> https://gist.github.com/tovic/d7b310dea3b33e4732c0
        function minify_js($input) {
           return $input;
        }

        unlink("templates/".$this->settings->production->site->template."/js/main.min.js");
        foreach (glob("templates/".$this->settings->production->site->template."/js/*.js") as $file) {
            $javascript = file_get_contents ($file);
            $javascript = minify_js($javascript);
            $minjs .= $javascript;
        }

        if(file_put_contents("templates/".$this->settings->production->site->template."/js/main.min.js",$minjs)) {
            $response = array('result' => "success", 'message' => 'main.min.js generated successfully.');
        } else {
            $response = array('result' => "failed", 'message' => 'main.min.js could not be generated');
        }
        echo(json_encode($response));
        exit();
    }

    /**
     * Send a test email to the site admin to see if it works.
     */
    public function testEmail() {
        $mail = $this->model->mail($this->settings->production->site->email, $this->settings->production->site->name, "Test Email", "If you've received this email then everything's working properly.");
        if ($mail) {
            $response = array('result' => "success", 'message' => 'Email has been sent!');
        } else {
            $response = array('result' => "failed", 'message' => 'Could not send email.');
        }
        echo(json_encode($response));
        exit();
    }
}
