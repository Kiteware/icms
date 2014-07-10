<!doctype html>
<html lang="en">
<?php 
require 'core/init.php';
require_once('includes/template.php');

$general->logged_in_protect();

include($this->getCurrentTemplatePath()."head.php"); 
include($this->getCurrentTemplatePath()."header.php"); 
 
?>

<div id="topbar"><div class="right">Hello</div></div>
<header>
		<a href="index.php" class="logo"><?php $this->widgetOutput('logoPosition');?></a>
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
    <h1>Welcome</h1>
    <p><?php $this->widgetOutput('sidebarPosition');?></p>
    </div>
  </div>
</section>

<section id="contentAlt">
  <div class="inner">
    <div class="center">
    <h1></h1>
		<p><?php $this->widgetOutput('mainPosition');?></p>
    </div>
  </div>
</section>
</body>
<?php include($this->getCurrentTemplatePath()."footer.php"); ?>
