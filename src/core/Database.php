<?php
namespace Nixhatter\ICMS;

/**
 * Database Connection Class
 * Grabs the credentials from the configuration.php file and starts a connection
 * Credentials are encoded, so we need to decode them first. Encoding is still a work in progress
 *
 * User: Dillon
 * Date: 3/23/2015
 */

defined('_ICMS') or die;

class Database {
    private $db;

    public function __construct($settings) {
        $error = "Error connecting to the database";

        $config = array(
            'host'    => $settings->production->database->host,
            'username'    => $settings->production->database->user,
            'password'    => $settings->production->database->password,
            'dbname'    => $settings->production->database->name,
            'port'    => $settings->production->database->port
        );

        try {
            $this->db = new \PDO('mysql:host=' . $config['host'] . ';port='. $config['port'] .'; dbname=' . $config['dbname'],
                $config['username'],
                $config['password']);
            $this->db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch(\PDOException $e) {
            if($settings->production->debug === "true") {
                print( "Database Error: " . $e->getMessage());
            }
            exit();
        } catch (Exception $e) {
            if($settings->production->debug  === "true") {
                print("Caught Exception: " . $e->getMessage());
            }
            exit();
        }
    }
    public function load() {
        return $this->db;
    }
}
