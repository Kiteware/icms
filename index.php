<?php
	ini_set('display_errors', 'On');
	error_reporting(E_ALL);
		
	require_once('includes/template.php');
	$tmpl=new Template();
	$tmpl->setWidget('logoPosition','logo');
	$tmpl->setWidget('sidebarPosition','welcome',array('hello_to'=>'ICMS'));
	$tmpl->setWidget('mainPosition','blog');
	$tmpl->show();
?>
