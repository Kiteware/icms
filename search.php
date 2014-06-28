<?php 
require 'core/init.php';

if (isset($_POST['submit'])) {

	if(empty($_POST['stock']) || empty($_POST['amount'])){

		$errors[] = 'Please fill in both fields.';

	}else{
        
        if ($users->user_exists($_POST['username']) === true) {
            $errors[] = 'That username already exists';
        }
        if(!ctype_alnum($_POST['username'])){
            $errors[] = 'Please enter a username with only alphabets and numbers';	
        }
        if (strlen($_POST['password']) <6){
            $errors[] = 'Your password must be atleast 6 characters';
        } else if (strlen($_POST['password']) >18){
            $errors[] = 'Your password cannot be more than 18 characters long';
        }
        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
            $errors[] = 'Please enter a valid email address';
        }else if ($users->email_exists($_POST['email']) === true) {
            $errors[] = 'That email already exists.';
        }
	}

	if(empty($errors) === true){
		
		$username 	= htmlentities($_POST['username']);
		$password 	= $_POST['password'];
		$email 		= htmlentities($_POST['email']);

		$users->register($username, $password, $email);
		header('Location: register.php?success');
		exit();
	}
}

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css/style.css" >
	<title>Search Form</title>
</head>
<body>	
	<div id="container">
		<?php include 'includes/menu.php'; ?>
		<section>
			<h1>Search</h1>
			
		   <form id="showSearchForm" class="" onsubmit="return validateSearch()" action="./results.php" method="post">
				<div>
					<label for="stockName">Stock Name (required):</label> 
					<input type="text" Name="stockName" id="stockName" size="30" maxlength="50" />
				</div>
				
				<div>
					<label for="stockDate">Retrieve Data since</label> 
					<input type="text" Name="stockDate" id="stockDate" size="30" maxlength="50" />
				</div>
				<div>
				<label for="searchChannel">Channel:</label> <select name="searchChannel" id="searchChannel">
							<option value="">Choose ...</option>
							<option value="itv">ITV</option>
							<option value="bbc">BBC</option>
						</select>
				</div>
				<div>
				<input name="your_name" value="your_value" type="checkbox">
				</div>
				<div>
					<span id="searchDatabaseMsg"></span>
				</div>
			<div>
					<input type="reset"/>
					<input type="submit" value="Search"/>
				</div>
		</form>
		</section>
	</div>
</body>
<script>
/*********************/
/* Search Validation */
/*********************/
function validateSearch()
{
	var showNameElement = document.getElementById("stockName");
	var showTypeElement = document.getElementById("stockDate");
	var searchMsgElement = document.getElementById("searchDatabaseMsg");
	
	if ((! showNameElement) || (! showTypeElement)) {
		return false;
	}
	
	var showName = showNameElement.value.trim();
	var showType = showTypeElement.options[showTypeElement.selectedIndex].value;
	
	if ((showName == "") && (showType == "")) {
		searchMsgElement.innerHTML = ("You must specify some search value.");
		searchMsgElement.style.borderWidth = "1px";
		return false;
	}

    searchMsgElement.innerHTML = "";
	searchMsgElement.style.borderWidth = "0";
	return true;
}</script>
</html>
