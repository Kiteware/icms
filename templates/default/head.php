<!doctype html>
<html lang="en">
<head>
	<meta name="description" content="An Intelligent Content Management System">
	<meta http-equiv="content-type" content="text/html;charset=UTF-8">	
	<link type='text/css' rel='stylesheet' href='templates/default/css/style.css' /> 
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<title><?php 
     if (isset($_GET['page'])) {
                $page        = $_GET['page'];
                if (substr($page, -4) == ".php") {
                    $page = substr($page, 0, -4);
                    $page = preg_replace("/[^A-Za-z0-9 ]/", " ", $page);
                }
                echo $settings->production->site->name." - ".ucwords($page);         
    } else {
        echo $settings->production->site->name;
    }            
    ?></title>
</head>
