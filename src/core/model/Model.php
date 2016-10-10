<?php
namespace Nixhatter\ICMS\model;

/**
 * Model
 *
 * @package ICMS
 * @author Dillon Aykac
 */

defined('_ICMS') or die;

use PHPMailer\PHPMailer;

class Model {
    protected $db;
    public $text;
    public $posts;
    public $container;
    public $settings;

    public function __construct() {

    }

    public function mail($email, $name, $subject, $body) {
        $email_settings = array($this->settings->production->site->name,
            $this->settings->production->site->email,
            $this->settings->production->email->host,
            $this->settings->production->email->port,
            $this->settings->production->email->user,
            $this->settings->production->email->pass,
            $this->settings->production->email->auth);

        if($this->checkIfEmpty($email_settings)) {
            $email_auth = $this->settings->production->email->auth;
            if ($email_auth == 'XOAUTH2') {
                return $this->oauthMail($email, $name, $subject, $body);
            } else {
                return $this->basicMail($email, $name, $subject, $body);
            }
        }
    }

    private function oauthMail($registeredEmail, $registeredUsername, $subject, $body) {
        $site_name = $this->settings->production->site->name;
        $site_email = $this->settings->production->site->email;
        $email_host = $this->settings->production->email->host;
        $email_port = $this->settings->production->email->port;
        $email_user = $this->settings->production->email->user;
        $email_clientid = $this->settings->production->email->clientid;
        $email_clientsecret = $this->settings->production->email->clientsecret;
        $email_refreshtoken = $this->settings->production->email->refreshtoken;

        $mail = new PHPMailer\PHPMailerOAuth;
        $mail->SMTPDebug = 0;
        $mail->isSMTP();                                    // Set mailer to use SMTP
        $mail->Host = $email_host;                          // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;
        $mail->AuthType = 'XOAUTH2';
        //User Email to use for SMTP authentication - Use the same Email used in Google Developer Console
        $mail->oauthUserEmail = $email_user;
        //Obtained From Google Developer Console
        $mail->oauthClientId = $email_clientid;
        //Obtained From Google Developer Console
        $mail->oauthClientSecret = $email_clientsecret;
        //Obtained By running get_oauth_token.php after setting up APP in Google Developer Console.
        //Set Redirect URI in Developer Console as [https/http]://<yourdomain>/<folder>/get_oauth_token.php
        // eg: http://localhost/phpmail/get_oauth_token.php
        $mail->oauthRefreshToken = $email_refreshtoken;
        $mail->Username = $email_user;                      // SMTP username
        //$mail->Password = $email_pass;                      // SMTP password
        $mail->SMTPSecure = 'tls';                          // Enable TLS encryption, `ssl` also accepted
        $mail->Port = $email_port;                          // TCP port to connect to

        $mail->setFrom($site_email, $site_name);
        $mail->addAddress($registeredEmail, $registeredUsername);               // Add a recipient
        $mail->addReplyTo($site_email, $site_name);

        //$mail->isHTML(true);                                // Set email format to HTML

        $mail->Subject = $subject;
        $mail->Body    = $body;

        if(!$mail->send()) {
            //echo 'Message could not be sent.';
            //echo 'Mailer Error: ' . $mail->ErrorInfo;
            return False;
        } else {
            return True;
        }
    }

    public function checkIfEmpty($array) {
        $bool = true;

        foreach ($array as $value) {

            if(empty($value)) $bool = false;

        }

        return $bool;
    }
    private function basicMail($registeredEmail, $registeredUsername, $subject, $body) {
        $site_name = $this->settings->production->site->name;
        $site_email = $this->settings->production->site->email;
        $email_host = $this->settings->production->email->host;
        $email_port = $this->settings->production->email->port;
        $email_user = $this->settings->production->email->user;
        $email_pass = $this->settings->production->email->pass;
        $mail = new PHPMailer\PHPMailer;

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = $email_host;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $email_user;                 // SMTP username
        $mail->Password = $email_pass;                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = $email_port;                                    // TCP port to connect to

        $mail->FromName = $site_name . ' Support';
        $mail->addAddress($registeredEmail, $registeredUsername);               // Add a recipient
        $mail->addReplyTo($site_email, $site_name);

        //$mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = $subject;
        $mail->Body    = $body;

        if(!$mail->send()) {
            error_log('Mailer Error: ' . $mail->ErrorInfo, 0);
            return False;
        } else {
            return True;
        }
    }

    public function error($message)
    {
        if($this->settings->production->debug === "true") {
            error_log('Error: ' . $message, 0);
            print('Error: ' . $message);
        }

        exit();
    }
}
