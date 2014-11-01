<?php
/**
 * Tournament Addon
 * User: Dillon
 * Date: 10/30/2014
 * Time: 3:17 PM
 */
if(count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
}
require 'addons/tournament/core/tournament.php';
require 'includes/steamauth/steamauth.php';
$tournament_class 	= new Tournament($db);

if(!$tournament_class->check_steamid($user['username']) && !isset($_SESSION['steamid']))
{
    echo("Please sign in through steam");
    steamlogin(); //login button
} elseif(!$tournament_class->check_steamid($user['username']) && isset($_SESSION['steamid'])) {
    $tournament_class->add_steamid($user['username'], $steamprofile['steamid'] );
}
else {
    include ('includes/steamauth/userInfo.php');
}
//Display chosen Tournament
if(isset($_GET['tournament'])) {
    $tournament_name = $_GET['tournament'];
    $info = $tournament_class->get_info($tournament_name);
    foreach ($info as $tournament_info) {
        echo($tournament_info['name'] . ' - ' .
            $tournament_info['status'] . ' - ' .
            $tournament_info['prize'] . ' - ' .
            $tournament_info['size'] . '
                    			- <a href="index.php?page=tournaments&tournament='.$tournament_info['tid'].'&action=join">Join</a>
                    			<br /><br />');
    }
    $matches = $tournament_class->get_matches($tournament_name);
    if ($matches != null) {
        foreach ($matches as $match_info) {
            echo($match_info['home'] . ' vs. ' .
                $match_info['away'] . ' - winner:' .
                $match_info['winner']
            );

        }
    }
    if(isset($_GET['action']) && $_GET['action'] == "join") {
        $tournament_class->join_tourn($_GET['tournament'], $user['username']);
    }
}
else {
    // List all Available tournaments
    $tournaments		=$tournament_class->get_tournaments();
    $tournament_count 	= count($tournaments);
    if ($tournament_count > 0) {
        foreach ($tournaments as $aTournament){
            echo ($aTournament['name'].' - '.
                $aTournament['status'].' - '.
                $aTournament['prize'].' - '.
                $aTournament['size'].'
                - <a href="index.php?page=tournaments&tournament='.$aTournament['tid'].'">View</a>
                    			<br /><br />');
        }
    } else {
        echo "no tournaments found";
    }
}
logoutbutton();
    // Show bracket
    //  echo $info[bracket];

    // Show next opponent

    // report your score
