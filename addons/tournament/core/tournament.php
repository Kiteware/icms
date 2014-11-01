<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/30/2014
 * Time: 3:24 PM
 */

class tournament {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function get_tournaments() {

        $query = $this->db->prepare("SELECT * FROM `tournaments`");

        try{
            $query->execute();
        }catch(PDOException $e){
            die($e->getMessage());
        }

        return $query->fetchAll();

    }
    public function get_matches($tournament_name) {

        $query = $this->db->prepare("SELECT `home`, `away`, `winner`, `mid` FROM `tourn_matches` WHERE `tid` = ?");
        $query->bindValue(1, $tournament_name);
        try{
            $query->execute();
        }catch(PDOException $e){
            die($e->getMessage());
        }

        return $query->fetchAll();

    }
    public function get_info($tournament) {

        $query = $this->db->prepare("SELECT * FROM `tournaments` WHERE `tid` = ?");
        $query->bindValue(1, $tournament);

        try{
            $query->execute();
            return $query->fetchAll();
        }catch(PDOException $e){
            die($e->getMessage());
        }
    }
    public function new_tourn( $name, $bracket, $size, $prize, $status){

        $query 	= $this->db->prepare('INSERT INTO `tournaments` (  name, bracket, size, prize, status) VALUES ( :name, :bracket, :size, :prize, :status)');

        try{
            $query->execute(array(
                ':name' => $name,
                ':bracket' => $bracket,
                ':size' => $size,
                ':prize' => $prize,
                ':status' => $status));

        }catch(PDOException $e){
            die($e->getMessage());
        }
    }
    public function join_tourn( $tid, $player_name){

        $query 	= $this->db->prepare('INSERT INTO `tourn_players` (tid, player_name) VALUES ( :tid, :player_name)');

        try{
            $query->execute(array(
                ':tid' => $tid,
                ':player_name' => $player_name));

        }catch(PDOException $e){
            die($e->getMessage());
        }
    }

    public function new_tourn_match($mid, $home, $away, $winner){

        $query 	= $this->db->prepare('INSERT INTO `tourn_matches` (mid, home, away, winner) VALUES (:mid, :home, :away, :winner)');

        try{
            $query->execute(array(
                ':mid' => $mid,
                ':home' => $home,
                ':away' => $away,
                ':winner' => $winner));

        }catch(PDOException $e){
            die($e->getMessage());
        }
    }
    public function get_teams($tid) {

        $query = $this->db->prepare("SELECT `player_name` FROM `tourn_players` WHERE `tid` = ?");
        $query->bindValue(1, $tid);

        try{
            $query->execute();
            $teams = $query->fetchAll(PDO::FETCH_COLUMN, 0);
            return $teams;
        }catch(PDOException $e){
            die($e->getMessage());
        }
    }
    //Creator: http://stackoverflow.com/users/1360252/d-d-m-van-zelst
    function scheduler($teams){
        if (count($teams)%2 != 0){
            array_push($teams,"bye");
        }
        $away = array_splice($teams,(count($teams)/2));
        $home = $teams;
        for ($i=0; $i < count($home)+count($away)-1; $i++){
            for ($j=0; $j<count($home); $j++){
                $round[$i][$j]["Home"]=$home[$j];
                $round[$i][$j]["Away"]=$away[$j];
            }
            if(count($home)+count($away)-1 > 2){
                $home_splice = array_splice($home,1,1);
                $home_shift = array_shift($home_splice);
                array_unshift($away, $home_shift);
                array_push($home,array_pop($away));
            }
        }
        return $round;
    }
    function create_match($home, $away) {
        $query 	= $this->db->prepare('INSERT INTO `tourn_matches` (home, away) VALUES (:home, :away)');

        try{
            $query->execute(array(
                ':home' => $home,
                ':away' => $away));

        }catch(PDOException $e){
            die($e->getMessage());
        }
    }
    function check_steamid($playername) {
        $query = $this->db->prepare("SELECT `steamid` FROM `tourn_players` WHERE `player_name` = ?");
        $query->bindValue(1, $playername);

        try{
            $query->execute();
            $steamid = $query->fetch();
            if ($steamid != null) {
                return true;
            } else {
                return false;
            }
        }catch(PDOException $e){
            die($e->getMessage());
        }
    }
    function add_steamid($playername, $steamid) {
        $query 	= $this->db->prepare('UPDATE `tourn_players` SET `steamid`= :steamid WHERE `player_name`= :playername');

        try{
            $query->execute(array(
                ':steamid' => $steamid,
                ':playername' => $playername));

        }catch(PDOException $e){
            die($e->getMessage());
        }
    }
} 