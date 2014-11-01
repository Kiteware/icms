<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/30/2014
 * Time: 8:04 PM
 */

class Addons
{

    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

    public function get_addon_location($name)
    {

        $query = $this->db->prepare("SELECT `location` FROM `addons` WHERE `name`= ? ");
        $query->bindValue(1, $name);

        try {

            $query->execute();

            return $query->fetchColumn();

        } catch (PDOException $e) {

            die($e->getMessage());
        }

    }
}