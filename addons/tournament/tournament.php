<?php
/**
 * Tournament Addon
 * User: Dillon
 * Date: 10/30/2014
 * Time: 3:17 PM
 */
//Checks if page is included so that it is not directly accesible
if(count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
}
require 'addons/tournament/core/tournament.php';
require 'addons/tournament/core/steamauth/steamauth.php';

$tournament_class 	= new Tournament($db);

?>
<div class="wrapper">
    <section class="content">
        <article>
            <center>
                <h1>Tournaments</h1>
                Steps to Enter a tournament.
                <ul>
                    <li>Login/Register  <a href="index.php?page=login">here</a></li>
                    <li>View the tournament you want to enter</li>
                    <li>Sign into steam using the button below</li>
                    <li>Click the 'join' button</li>
                </ul>
            </center>

<?php

// If a steam session exists
if(!isset($_SESSION['steamid']))
{
    steamlogin(); // steam login button

}
else {
    include ('addons/tournament/core/steamauth/userInfo.php');
}
//Display chosen Tournament by tournament ID (tid)
if(isset($_GET['tid'])) {
    //List Tournaments Table
    ?>
    <table class="table-fill">
    <thead>
    <tr>
        <th class="text-left">Name</th>
        <th class="text-left">Status</th>
        <th class="text-left">Prize</th>
        <th class="text-left">Size</th>
        <th class="text-left">Action</th>
    </tr>
    </thead>
    <tbody class="table-hover">
    <?php
    $tournament_id = $_GET['tid'];
    $info = $tournament_class->get_info($tournament_id);
    foreach ($info as $tournament_info) {
        echo('<tr><td>'.$tournament_info['name'] . ' </td>
            <td>' . $tournament_info['status'] . '</td>
            <td>' . $tournament_info['prize'] . ' </td>
            <td>' . $tournament_info['size'] . ' </td><td> ');
            //User must have signed in through steam to join a tournament
            if (!isset($user['username'])) {
                echo('Please <a href="index.php?page=login">login</a> to join ');
            }
            elseif(!isset($_SESSION['steamid'])) {
                echo('Sign in through steam to join ');
            } else {
                echo('<a href="index.php?page=tournaments&tid='.$tournament_info['tid'].'&action=join">Join</a>');
            }
             echo('</td></tr>');
    }
    // Team List Table
    ?>
    </tbody>
    </table>
    <table class="table-fill">
        <thead>
        <tr>
            <th class="text-left">Player Name</th>
            <th class="text-left">Wins</th>
            <th class="text-left">Losses</th>
            <th class="text-left">Ready</th>
            <th class="text-left">SteamID</th>
        </tr>
        </thead>
        <tbody class="table-hover">
        <?php
        $teams = $tournament_class->get_teams($tournament_id);
        if ($teams != null) {
            foreach ($teams as $team_info) {
                echo('<tr><td>'. $team_info['player_name'] . ' </td>
             <td>' . $team_info['wins'] . ' </td>
             <td>' . $team_info['losses'] . ' </td>
             <td>' . $team_info['ready'] . ' </td>
             <td>' . $team_info['steamid'] . '</td></tr>'
                );
            }
        }
        //List Matches Table
        ?>
        </tbody>
    </table>
    <table class="table-fill">
    <thead>
    <tr>
        <th class="text-left">Home</th>
        <th class="text-left">Away</th>
        <th class="text-left">Winner</th>
    </tr>
    </thead>
    <tbody class="table-hover">
    <?php
    $matches = $tournament_class->get_matches($tournament_id);
    if ($matches != null) {
        foreach ($matches as $match_info) {
            echo('<tr><td>'. $match_info['home'] . ' </td>
             <td>' . $match_info['away'] . ' </td>
             <td>' . $match_info['winner'] . '</td></tr>'
            );
        }
    }
    ?>
    </tbody>
    </table>

    <?php
    // If the join button was pressed
    if(isset($_GET['action']) && $_GET['action'] == "join") {
        $tournament_class->join_tourn($_GET['tid'], $user['username']);
        if (!$tournament_class->has_steamid($user['username'])) {
            $tournament_class->add_steamid($user['username'], $steamprofile['steamid'] );

        }
    }
}
else {
?>
<table class="table-fill">
<thead>
<tr>
    <th class="text-left">Name</th>
    <th class="text-left">Status</th>
    <th class="text-left">Prize</th>
    <th class="text-left">Size</th>
    <th class="text-left">Action</th>
</tr>
</thead>
<tbody class="table-hover">
<?php
    // List all Available tournaments
    $tournaments		=$tournament_class->get_tournaments();
    $tournament_count 	= count($tournaments);

    if ($tournament_count > 0) {
        foreach ($tournaments as $aTournament){
            echo ('<tr><td>'.$aTournament['name'].' </td>
            <td>'. $aTournament['status'].' </td>
            <td>'. $aTournament['prize'].'</td>
            <td>'. $aTournament['size'].' </td>
            <td><a href="index.php?page=tournaments&tid='.$aTournament['tid'].'">View</a>
                    			</td></tr>');
        }
    } else {
        echo "no tournaments found";
    }
    ?>
</tbody>
</table>
<?php
}
?>
        </article>
        </section>
        </div>

