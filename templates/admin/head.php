<!doctype html>
<html lang="en">
<head>
	<meta name="description" content="An Intelligent Content Management System">
	<meta http-equiv="content-type" content="text/html;charset=UTF-8">
	<link type='text/css' rel='stylesheet' href='/templates/admin/css/style.css' />
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="http://malsup.github.com/jquery.form.js"></script>
	<link rel="stylesheet" href="/includes/editor/css/main.css" type="text/css">
	<link rel="stylesheet" href="/includes/editor/css/ionicons.css" type="text/css">
    <link rel="stylesheet" href="/templates/admin/css/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/templates/admin/css/pen.css" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <script type="text/javascript" language="javascript" src="/templates/admin/js/main.js"></script>
	<title><?php echo $settings->production->site->name." - Admin Panel" ?></title>
    <style type="text/css">

        @media all and (max-width:1024px){ body, pre a{width:60%;} }
        small{color:#999;}
        #toolbar{margin-bottom:1em;position:fixed;left:20px;margin-top:5px;}
        #toolbar [class^="icon-"]:before, #toolbar [class*=" icon-"]:before{font-family:'pen'}
        #mode{color:#1abf89;;cursor:pointer;}
        #mode.disabled{color:#666;}
        #mode:before{content: '\e813';}
        #hinted{color:#1abf89;cursor:pointer;}
        #hinted.disabled{color:#666;}
        #hinted:before{content: '\e816';}

        #fork{position:fixed;right:0;top:0;}

        /*
        When the webpage is printed
        this media query hides extra elements,
        and makes the text content fit the page.
        */
        @media print {
            #fork, #toolbar {
                display: none;
            }
            body {
                width: 94%;
                padding-top: 1em;
                font-size: 12px;
            }
            html {
                border-top: 0;
            }
        }
    </style>
</head>
