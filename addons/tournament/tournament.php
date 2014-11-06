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

$username = "";
if (isset($user['username'])) $username = $user['username'];


?>
<div class="wrapper">
    <section class="content">
        <article>
            <center>
                <h1>Tournaments</h1>
                Steps to Enter a tournament.
                <ul>
                    <li>Login/Register  <a href="index.php?page=login">here</a></li>
                    <li>Select the tournament you want to enter</li>
                    <li>Sign into steam using the button that appears below</li>
                    <li>Click the 'join' button</li>
                </ul>
            </center>

<?php

// If a steam session exists
if(!isset($_SESSION['steamid']) && !empty($username) && !$tournament_class->has_steamid($username))
{
    steamlogin( $settings->production->site->url); // steam login button

}
elseif (isset($_SESSION['steamid']) && !empty($username)) {
    include ('addons/tournament/core/steamauth/userInfo.php');
    //echo "<form action=\"index.php?page=tournament\" method=\"post\"><input name=\"logout\" value=\"Logout from Steam\" type=\"submit\" /></form>"; //logout button
    if(isset($_POST['logout'])) {
        header('Location: ../index.php'); // Change this to where you want logged out users to be redirected to.
        session_start();
        unset($_SESSION['steamid']);
    }
}
if (isset($_GET['mid'])) {
    if (isset($_POST['home']) & isset($_POST['away'])) {
        $homeScore = $_POST['home'];
        $awayScore = $_POST['away'];
        if($homeScore > $awayScore) {
            $winner = $_POST['homePlayer'];
            $tournament_class->addWin($_POST['homePlayer']);
            $tournament_class->addLoss($_POST['awayPlayer']);
        } else {
            $winner = $_POST['awayPlayer'];
            $tournament_class->addWin($_POST['awayPlayer']);
            $tournament_class->addLoss($_POST['homePlayer']);
        }
        $tournament_class->insertMatchScore($_POST['mid'], $_POST['home'], $_POST['away'], $winner);
    } else {
        $match_info = $tournament_class->getMatchInfo($_GET['mid']);
        echo('<form action="" method="post" name="post">');
        echo('<label for="home"> ' . $match_info['home'] . '</label>
      <input type="text" name="home" />
      <input type="hidden" name="homePlayer" value="' . $match_info['home'] . '" />');

        echo('<label for="away"> ' . $match_info['away'] . '</label>
      <input type="text" name="away" />
      <input type="hidden" name="awayPlayer" value="' . $match_info['away'] . '" />');
        echo('<input type="submit" name="SubmitScores" value="Submit Scores" />
      <input type="hidden" name="mid" value="' . $_GET['mid'] . '" />
      </form>');
    }
}
//Display chosen Tournament by tournament ID (tid)
if(isset($_GET['tid'])) {
    $tournament_id = $_GET['tid'];

    if(!empty($username) && !$tournament_class->isReady($tournament_id, $username) && $tournament_class->has_steamid($username)) {
        if (isset($_POST['ready']))
            $tournament_class->readyUp($tournament_id, $username);
        ?>
        <form action="" method="post"><input name="ready" type="submit" value="Ready up!"  style="max-width:50px; padding: 5px;"  /></form>
    <?php
    }
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
        $info = $tournament_class->get_info($tournament_id);
        foreach ($info as $tournament_info) {
            echo('<tr><td>' . $tournament_info['name'] . ' </td>
            <td>' . $tournament_info['status'] . '</td>
            <td>' . $tournament_info['prize'] . ' </td>
            <td>' . $tournament_info['size'] . ' </td><td> ');
            //User must have signed in through steam to join a tournament
            if (!isset($username)) {
                echo('Please <a href="index.php?page=login">login</a> to join ');
            } elseif ($tournament_class->has_steamid($username)) {
                echo('Joined!');
            } elseif (empty($username)) {
                echo(' <a href="index.php?page=login&from=tournament">Sign in</a> to join! ');
            } elseif (!isset($_SESSION['steamid'])) {
                echo('Sign in through steam to join ');
            } else {
                echo('<a href="index.php?page=tournament&tid=' . $tournament_info['tid'] . '&action=join">Join</a>');
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
                echo('<tr><td>' . $team_info['player_name'] . ' </td>
             <td>' . $team_info['wins'] . ' </td>
             <td>' . $team_info['losses'] . ' </td>');
                if ($team_info['ready'] == 0) {
                    echo ('<td> Not Ready </td>');
                } else {
                    echo ('<td> Ready </td>');
                }

                echo('<td>' . $team_info['steamid'] . '</td></tr>');
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
            echo('<tr><td>' . $match_info['home'] . ' </td>
             <td>' . $match_info['away'] . ' </td>');
            if (!empty($match_info['winner'])) {
                echo('<td>' . $match_info['winner'] . '</td></tr>');
            } else {
                if ($match_info['home'] == $username | $match_info['away'] ==  $username) {
                    echo('<td> <a href="index.php?page=tournament&mid=' . $match_info['mid'] . '">Submit Score</a></td></tr>');
                }
            }
        }
    }
        // If the join button was pressed
        if (isset($_GET['action']) && $_GET['action'] == "join" && !empty($username)) {
            if($tournament_class->isOpen($tournament_id)) {
                $tournament_class->join_tourn($tournament_id , $username);
                if (!$tournament_class->has_steamid($username)) {
                    $tournament_class->add_steamid($username, $steamprofile['steamid']);
                }
                header("Location: index.php?page=tournament&tid=".$tournament_id);
            }
        }
    ?>
    </tbody>
    </table>

<?php
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
            echo ('<tr><td><a href="index.php?page=tournament&tid='.$aTournament['tid'].'">'.$aTournament['name'].' </a></td>
            <td>'. $aTournament['status'].' </td>
            <td>'. $aTournament['prize'].'</td>
            <td>'. $aTournament['size'].' </td>
            <td><a href="index.php?page=tournament&tid='.$aTournament['tid'].'">View</a>
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

