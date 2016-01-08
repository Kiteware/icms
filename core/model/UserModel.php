<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */

/*
|--------------------------------------------------------------------------
| User Model
|--------------------------------------------------------------------------
|
| User Model Class
|
*/
class UserModel {
    public $text;
    public $posts;
    private $db;
    public $user;
    public $user_id;
    private $rounds;


    public function __construct(\Pimple\Container $container) {
        $this->db = $container['db'];
        $blog        = new BlogModel($container);
        $this->posts        =$blog->get_posts();
        $this->text = 'Hello world!';
        if (CRYPT_BLOWFISH != 1) {
            throw new Exception("Bcrypt is not supported, it is required for password hashing. http://php.net/crypt");
        }
        $this->rounds = 12;
    }

    public function update_user($username, $full_name, $gender, $bio, $image_location, $id)
    {
        $query = $this->db->prepare("UPDATE `users` SET
								`username`	= ?,
								`full_name`		= ?,
								`gender`		= ?,
								`bio`			= ?,
								`image_location`= ?

								WHERE `id` 		= ?
								");

        $query->bindValue(1, $username);
        $query->bindValue(2, $full_name);
        $query->bindValue(3, $gender);
        $query->bindValue(4, $bio);
        $query->bindValue(5, $image_location);
        $query->bindValue(6, $id);

        try {
            $query->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function change_password($user_id, $password)
    {
        global $bcrypt;

        /* Two create a Hash you do */
        $password_hash = $bcrypt->genHash($password);

        $query = $this->db->prepare("UPDATE `users` SET `password` = ? WHERE `id` = ?");

        $query->bindValue(1, $password_hash);
        $query->bindValue(2, $user_id);

        try {
            $query->execute();

            return true;
        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function recover($email, $generated_string)
    {
        if ($generated_string == 0) {
            return false;
        } else {

            $query = $this->db->prepare("SELECT COUNT(`id`) FROM `users` WHERE `email` = ? AND `generated_string` = ?");

            $query->bindValue(1, $email);
            $query->bindValue(2, $generated_string);

            try {

                $query->execute();
                $rows = $query->fetchColumn();

                if ($rows == 1) {

                    global $bcrypt;

                    $username = $this->fetch_info('username', 'email', $email); // getting username for the use in the email.
                    $user_id  = $this->fetch_info('id', 'email', $email);// We want to keep things standard and use the user's id for most of the operations. Therefore, we use id instead of email.

                    $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                    $generated_password = substr(str_shuffle($charset),0, 10);

                    $this->change_password($user_id, $generated_password);

                    $query = $this->db->prepare("UPDATE `users` SET `generated_string` = 0 WHERE `id` = ?");

                    $query->bindValue(1, $user_id);

                    $query->execute();

                    mail($email, 'Recover Password', "Hello " . $username . ",\n\nYour your new password is: " . $generated_password . "\n\nPlease change your password once you have logged in using this password.\n\n");

                } else {
                    return false;
                }

            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }
    }

    public function fetch_info($what, $field, $value)
    {
        $allowed = array('id', 'username', 'full_name', 'gender', 'bio', 'email'); // I have only added few, but you can add more. However do not add 'password' eventhough the parameters will only be given by you and not the user, in our system.
        if (!in_array($what, $allowed, true) || !in_array($field, $allowed, true)) {
            throw new InvalidArgumentException();
        } else {

            $query = $this->db->prepare("SELECT $what FROM `users` WHERE $field = ?");

            $query->bindValue(1, $value);

            try {

                $query->execute();

            } catch (PDOException $e) {

                die($e->getMessage());
            }

            return $query->fetchColumn();
        }
    }

    public function confirm_recover($email, $url)
    {
        $username = $this->fetch_info('username', 'email', $email);// We want the 'id' WHERE 'email' = user's email ($email)

        $unique = uniqid('',true);
        $random = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),0, 10);

        $generated_string = $unique . $random; // a random and unique string

        $query = $this->db->prepare("UPDATE `users` SET `generated_string` = ? WHERE `email` = ?");

        $query->bindValue(1, $generated_string);
        $query->bindValue(2, $email);

        try {

            $query->execute();

            mail($email, 'Recover Password', "Hello " . $username. ",\r\nPlease click the link below:\r\n\r\n".$url."index.php?page=recover.php&email=" . $email . "&generated_string=" . $generated_string . "\r\n\r\n We will generate a new password for you and send it back to your email.\r\n\r\n");

        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function user_exists($username)
    {
        $query = $this->db->prepare("SELECT COUNT(`id`) FROM `users` WHERE `username`= ?");
        $query->bindValue(1, $username);

        try {

            $query->execute();
            $rows = $query->fetchColumn();

            if ($rows == 1) {
                return true;
            } else {
                return false;
            }

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function email_exists($email)
    {
        $query = $this->db->prepare("SELECT COUNT(`id`) FROM `users` WHERE `email`= ?");
        $query->bindValue(1, $email);

        try {

            $query->execute();
            $rows = $query->fetchColumn();

            if ($rows == 1) {
                return true;
            } else {
                return false;
            }

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function register($username, $password, $email, $url, $sitename, $site_email)
    {
        global $bcrypt; // making the $bcrypt variable global so we can use here

        $time        = time();
        $ip        = $_SERVER['REMOTE_ADDR']; // getting the users IP address
        $email_code = $email_code = uniqid('code_',true); // Creating a unique string.

        $password   = $bcrypt->genHash($password);

        $query    = $this->db->prepare("INSERT INTO `users` (`username`, `password`, `email`, `ip`, `time`, `email_code`) VALUES (?, ?, ?, ?, ?, ?) ");

        $query->bindValue(1, $username);
        $query->bindValue(2, $password);
        $query->bindValue(3, $email);
        $query->bindValue(4, $ip);
        $query->bindValue(5, $time);
        $query->bindValue(6, $email_code);

        try {
            $query->execute();

            mail($email, 'Activate your account', "Hello " . $username. ",\r\nThank you for registering! Please visit the link below to activate your account:\r\n\r\n".
                $url."/index.php?page=activate&email=" . $email . "&email_code=" . $email_code . "\r\n\r\n-- ".$sitename, 'From:'. $site_email);

            //require 'includes/phpmailer/PHPMailerAutoload.php';

            /* $mail = new PHPMailer;

            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';  // Specify main and backup server
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'bot@sitename.com';                      // SMTP username
            $mail->Password = 'password';                         // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted

            $mail->From = 'bot@sitename.com';
            $mail->FromName = 'SiteName';
            $mail->addAddress($email, $username);  // Add a recipient
            //$mail->addAddress($email);               // Name is optional
            $mail->addReplyTo('bot@site.com', 'Bot');

            $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            $mail->isHTML(true);                                  // Set email format to HTML

            $mail->Subject = 'Please Activate your Account';
            $mail->Body    = "Hello " . $username. ",\r\nThank you for registering with us. Please visit the link below so we can activate your account:\r\n\r\nhttp://www.nixx.co/activate.php?email=" . $email . "&email_code=" . $email_code . "\r\n\r\n-- ICMS";
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            if (!$mail->send()) {
                echo 'Message could not be sent.';
                echo 'Mailer Error: ' . $mail->ErrorInfo;
                exit;
            }*/
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function activate($email, $email_code)
    {
        $query = $this->db->prepare("SELECT COUNT(`id`) FROM `users` WHERE `email` = ? AND `email_code` = ? AND `confirmed` = ?");

        $query->bindValue(1, $email);
        $query->bindValue(2, $email_code);
        $query->bindValue(3, 0);

        try {

            $query->execute();
            $rows = $query->fetchColumn();

            if ($rows == 1) {

                $query_2 = $this->db->prepare("UPDATE `users` SET `confirmed` = ? WHERE `email` = ?");

                $query_2->bindValue(1, 1);
                $query_2->bindValue(2, $email);

                $query_2->execute();

                return true;

            } else {
                return false;
            }

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function email_confirmed($username)
    {
        $query = $this->db->prepare("SELECT COUNT(`id`) FROM `users` WHERE `username`= ? AND `confirmed` = ?");
        $query->bindValue(1, $username);
        $query->bindValue(2, 1);

        try {

            $query->execute();
            $rows = $query->fetchColumn();

            if ($rows == 1) {
                return true;
            } else {
                return false;
            }

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function login($username, $password)
    {

        $query = $this->db->prepare("SELECT `password`, `id` FROM `users` WHERE `username` = ?");
        $query->bindValue(1, $username);

        try {

            $query->execute();
            $data                = $query->fetch();
            $stored_password    = $data['password']; // stored hashed password
            $id                = $data['id']; // id of the user to be returned if the password is verified, below.

            if ($this->verify($password, $stored_password) === true) { // using the verify method to compare the password with the stored hashed password.

                return $id;    // returning the user's id.
            } else {
                return false;
            }

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function userdata($id)
    {
        $query = $this->db->prepare("SELECT * FROM `users` WHERE `id`= ?");
        $query->bindValue(1, $id);

        try {

            $query->execute();

            return $query->fetch();

        } catch (PDOException $e) {

            die($e->getMessage());
        }

    }

    public function get_users()
    {
        $query = $this->db->prepare("SELECT * FROM `users` ORDER BY `time` DESC");

        try {
            $query->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        return $query->fetchAll();

    }
    public function delete_users($ID)
    {
        $query = $this->db->prepare('DELETE FROM users WHERE id = ?');
        $query->bindValue(1, $ID);

        try {
            $query->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        return true;
    }
    public function get_user_permission($ID)
    {
        $query = $this->db->prepare("SELECT `permission` FROM `users` WHERE id = ?");
        $query->bindValue(1, $ID);

        try {
            $query->execute();

            return $query->fetch();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    /* Gen Salt */
    private function genSalt()
    {
        $string = str_shuffle(mt_rand());
        $salt    = uniqid($string ,true);

        return $salt;
    }

    /* Gen Hash */
    public function genHash($password)
    {
        $hash = crypt($password, '$2y$' . $this->rounds . '$' . $this->genSalt());

        return $hash;
    }

    /* Verify Password */
    public function verify($password, $existingHash)
    {
        /* Hash new password with old hash */
        $hash = crypt($password, $existingHash);

        /* Do the hashes match? */
        if ($hash === $existingHash) {
            return true;
        } else {
            return false;
        }
    }

    /* PERMISISSIONS */

        public function has_access($userID, $pageName, $usergroupID)
        {
            $query = $this->db->prepare("SELECT * FROM `permissions` WHERE `pageName` = ? AND (`userID`= ?  OR `usergroupID` = ?)");
            $query->bindValue(1, $pageName);
            $query->bindValue(2, $userID);
            $query->bindValue(3, $usergroupID);

            try {

                $query->execute();
                $rows = $query->fetch(PDO::FETCH_ASSOC);

                if (!$rows) {
                    return false;
                } else {
                    return true;
                }

            } catch (PDOException $e) {
                die($e->getMessage());
            }

        }
        public function user_access($userID, $pageName)
        {
            if (!empty($userID)) {
                $query = $this->db->prepare("SELECT * FROM `permissions` WHERE `pageName` = ? AND `usergroupID` = 'user'");
                $query->bindValue(1, $pageName);

                  try {

                    $query->execute();
                    $rows = $query->fetch(PDO::FETCH_ASSOC);

                    if (!$rows) {
                        return false;
                    } else {
                        return true;
                    }

                } catch (PDOException $e) {
                    die($e->getMessage());
                }
            }
        }

        public function add_permission($userID, $pageName)
        {
            $query    = $this->db->prepare("INSERT INTO `permissions` (`userID`, `pageName`) VALUES (?, ?) ");

            $query->bindValue(1, $userID);
            $query->bindValue(2, $pageName);

            try {
                $query->execute();
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }

        public function delete_permission($userID, $pageName)
        {
            $query    = $this->db->prepare("DELETE FROM `permissions` WHERE `userID` = ? AND `pageName` = ?");

            $query->bindValue(1, $userID);
            $query->bindValue(2, $pageName);

            try {
                $query->execute();
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }

        public function add_usergroup($usergroupID, $pageName)
        {
            $query    = $this->db->prepare("INSERT INTO `permissions` (`usergroupID`, `pageName`) VALUES (?, ?) ");

            $query->bindValue(1, $usergroupID);
            $query->bindValue(2, $pageName);

            try {
                $query->execute();
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }

        public function delete_usergroup($usergroupID, $pageName)
        {
            $query    = $this->db->prepare("DELETE FROM `permissions` WHERE `usergroupID` = ? AND `pageName` = ?");

            $query->bindValue(1, $usergroupID);
            $query->bindValue(2, $pageName);

            try {
                $query->execute();
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }

        public function get_permission($id)
        {
            $query = $this->db->prepare("SELECT * FROM `permissions` WHERE `userID`= ? or `usergroupID` = ?");
            $query->bindValue(1, $id);
            $query->bindValue(2, $id);

            try {

                $query->execute();

            } catch (PDOException $e) {

                die($e->getMessage());
            }

            return $query->fetch();
        }
        public function get_permissions()
        {
            $query = $this->db->prepare("SELECT * FROM `permissions` WHERE `userID` IS NOT NULL ORDER BY `userID` DESC");

            try {
                $query->execute();
            } catch (PDOException $e) {
                die($e->getMessage());
            }

            return $query->fetchAll();
        }
        public function get_usergroups()
        {
            $query = $this->db->prepare("SELECT * FROM `permissions` WHERE `usergroupID` IS NOT NULL ORDER BY `usergroupID` ASC");

            try {
                $query->execute();
            } catch (PDOException $e) {
                die($e->getMessage());
            }

            return $query->fetchAll();
        }
        public function delete_all_page_permissions($pageName)
        {
            $query    = $this->db->prepare("DELETE FROM `permissions` WHERE  `pageName` = ?");

            $query->bindValue(1, $pageName);

            try {
                $query->execute();
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }
        public function delete_all_user_permissions($userID)
        {
            $query    = $this->db->prepare("DELETE FROM `permissions` WHERE  `userID` = ?");

            $query->bindValue(1, $userID);

            try {
                $query->execute();
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }
        public function delete_all_usergroup_permissions($usergroupID)
        {
            $query    = $this->db->prepare("DELETE FROM `permissions` WHERE  `usergroupID` = ?");

            $query->bindValue(1, $usergroupID);

            try {
                $query->execute();
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }
}
