<?php 
require 'core/init.php';
$general->logged_in_protect();

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css/style.css" >
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<title>Welcome to ICMS</title>
</head>
<div id="topbar">Top Bar -</div>
<header>
		<a href="index.php" class="logo">ICMS</a>
		
		<nav>
	    	<?php include 'includes/menu.php'; ?>
    	</nav>
	</header>
<body>
<section id="image">
  <div class="inner">
    <div class="center">
    <h1><strong>Intelligent</strong> Content Management System</h1>
    <p>In Alpha</p>
    </div>
  </div>
</section>

<section class="content">
  <div class="inner">
    <div class="center">
    <h1>First Post</h1>
    <p>Content</p>
    <h2>Lorem Ipsum</h2>
    <p>More content</p>
    </div>
  </div>
</section>

<section id="contentAlt">
  <div class="inner">
    <div class="center">
    <h1>50/50</h1>
    <p>In this betting game, you have a 50% chance of doubling however much you bet.
    Simply choose how much you want to bet and press the "bet" button.
    <br />
    In reality, this plays against the human condition because risk takers, and even the 
    risk neutral will play this game. Only those that are completely risk averse will
    steer away. 
    <br />
		  <div id="results_table"></div>
	<br />
	<div id="message_txt"></div>
    <form id="userInput" name="userInput" method="post">
        <input id="input_txt" name="limitedtextfield" type="number" maxlength="3">
        <button id="bet_button" type="button" onclick="guessNumber()">Bet</button>
    </form>
</div></p>
    </div>
  </div>
</section>
 <script src="./js/game.js"></script>
</body>
<footer>
Copyright 2014 <em>NiXX</em>
</footer>
</html>
