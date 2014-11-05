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
    public function getMatchInfo($mid) {

        $query = $this->db->prepare("SELECT `home`, `away`, `winner`, `mid` FROM `tourn_matches` WHERE `mid` = ?");
        $query->bindValue(1, $mid);
        try{
            $query->execute();
        }catch(PDOException $e){
            die($e->getMessage());
        }

        return $query->fetch();

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

        $query = $this->db->prepare("SELECT `steamid` FROM `tourn_players` WHERE `player_name` = ? AND `tid` = ?");
        $query->bindValue(1, $player_name);
        $query->bindValue(2, $tid);

        try{

            $query->execute();
            $duplicate = $query->fetchColumn();
            if (empty($duplicate)) {
                $query 	= $this->db->prepare('INSERT INTO `tourn_players` (tid, player_name) VALUES ( :tid, :player_name)');

                $query->execute(array(
                    ':tid' => $tid,
                    ':player_name' => $player_name));

            } else {
                return false;
            }
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
    public function insertMatchScore($mid, $home, $away, $winner){

        $query 	= $this->db->prepare('UPDATE `tourn_matches` SET `winner` = :winner WHERE `mid` = :mid');

        try{
            $query->execute(array(
                ':winner' => $winner,
                ':mid' => $mid));

        }catch(PDOException $e){
            die($e->getMessage());
        }
    }
    public function get_player_names($tid) {

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
    public function get_teams($tid) {

        $query = $this->db->prepare("SELECT * FROM `tourn_players` WHERE `tid` = ?");
        $query->bindValue(1, $tid);

        try{
            $query->execute();
            return $query->fetchAll();
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
    function create_match($home, $away, $tid) {
        $query 	= $this->db->prepare('INSERT INTO `tourn_matches` (home, away, tid) VALUES (:home, :away, :tid)');

        try{
            $query->execute(array(
                ':home' => $home,
                ':away' => $away,
                ':tid' => $tid));

        }catch(PDOException $e){
            die($e->getMessage());
        }
    }
    function has_steamid($playername) {
        $query = $this->db->prepare("SELECT `steamid` FROM `tourn_players` WHERE `player_name` = ?");
        $query->bindValue(1, $playername);

        try{
            $query->execute();
            $steamid = $query->fetchColumn();
            if (empty($steamid)) {
                return false;
            } else {
                return true;
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
    function closeTournament($tid) {
        $query 	= $this->db->prepare("UPDATE `tournaments` SET `status`= 'closed' WHERE `tid`= :tid");

        try{
            $query->execute(array(':tid' => $tid));

        }catch(PDOException $e){
            die($e->getMessage());
        }
    }
    function deletePlayer($tid, $playerName) {
        $query 	= $this->db->prepare("DELETE FROM `tourn_players`  WHERE `tid`= :tid AND `player_name` = :playerName");

        try{
            $query->execute(array(
                ':playerName' => $playerName,
                ':tid' => $tid));

        }catch(PDOException $e){
            die($e->getMessage());
        }
    }
    function isOpen($tid) {
    $query = $this->db->prepare("SELECT `status` FROM `tournaments` WHERE `tid` = ? AND `status` = 'open'");
    $query->bindValue(1, $tid);

    try{
        $query->execute();
        $steamid = $query->fetchColumn();
        if (empty($steamid)) {
            return false;
        } else {
            return true;
        }
    }catch(PDOException $e){
        die($e->getMessage());
    }
}
    function isReady($tid, $playerName) {
        $query = $this->db->prepare("SELECT `ready` FROM `tourn_players` WHERE `tid` = ? AND `player_name` = ?");
        $query->bindValue(1, $tid);
        $query->bindValue(2, $playerName);

        try{
            $query->execute();
            $ready = $query->fetchColumn();
            if (empty($ready)) {
                return false;
            } else {
                return true;
            }
        }catch(PDOException $e){
            die($e->getMessage());
        }
    }
    function readyUp($tid, $playerName) {
        $query 	= $this->db->prepare("UPDATE `tourn_players` SET `ready`= '1' WHERE `tid`= :tid AND `player_name`= :playerName");

        try{
            $query->execute(array(
                ':playerName' => $playerName,
                ':tid' => $tid));

        }catch(PDOException $e){
            die($e->getMessage());
        }
    }
    function addWin($playername) {
        $query 	= $this->db->prepare("UPDATE `tourn_players` SET `wins`= wins + 1 WHERE `player_name`= :playername");

        try{
            $query->execute(array(':playername' => $playername));

        }catch(PDOException $e){
            die($e->getMessage());
        }
    }
    function addLoss($playername) {
    $query 	= $this->db->prepare("UPDATE `tourn_players` SET `losses`= losses + 1 WHERE `player_name`= :playername");

    try{
        $query->execute(array(':playername' => $playername));

    }catch(PDOException $e){
        die($e->getMessage());
    }
}

} 
