<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/31/2014
 * Time: 12:09 AM
 */
if(count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
}
require 'addons/tournament/core/tournament.php';
require 'includes/steamauth/steamauth.php';
$tournaments 	= new Tournament($db);

if(isset($_POST['new_tournament'])){
    $name = $_POST['name'];
    $bracket = $_POST['bracket'];
    $size = $_POST['size'];
    $prize = $_POST['prize'];
    $status = $_POST['status'];

    //Check to make sure fields are filled in
    if(empty($name) OR empty ($bracket) OR empty ($size) OR empty ($status)){
        echo ('Make sure you filled out all the fields!');
    }
    else{

        $tournaments->new_tourn($name, $bracket, $size, $prize, $status);
    }
} else if(isset($_POST['schedule_matches'])){
    $tid = $_POST['tid'];
    echo "scheduling";
    //Check to make sure fields are filled in
    if(empty($tid)){
        echo ('Make sure you filled out all the fields!');
    }
    else{
        $teams = $tournaments->get_teams($tid);
        $schedule = $tournaments->scheduler($teams);
        foreach($schedule AS $round => $games){
            echo "Round: ".($round+1)."<BR>";
            foreach($games AS $play){
                echo $play["Home"]." - ".$play["Away"]."<BR>";
                $tournaments->create_match($play["Home"], $play["Away"]);
            }
            echo "<BR>";
        }
    }
}
?>


<form action="" method="post" name="post">
    Create Tournament
    <p>
        <label for="name">Tournament Name</label>
        <input type="text" name="name" />
        <label for="bracket">Type of Bracket</label>
        <input type="text" name="bracket" />
        <label for="size">Maximum amount of participents</label>
        <input type="text" name="size" />
        <label for="prize">Prize</label>
        <input type="text" name="prize" />
        <label for="status">Status</label>
        <input type="text" name="status" />
        <input type="hidden" name="new_tournament" value="yes">
    </p>
    <input name="submit" type="submit" value="submit"/>
</form>

<form action="" method="post" name="post">
    Schedule Matches
    <p>
        <label for="name">Tournament ID</label>
        <input type="text" name="tid" />
        <input type="hidden" name="schedule_matches" value="yes">
    </p>
    <input name="submit" type="submit" value="submit"/>
</form>