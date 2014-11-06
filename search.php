<?php
if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
    }
if (isset($_POST['submit'])) {

    if (empty($_POST['query'])) {

        $errors[] = 'Please fill in all fields.';

    } else {

        if ($users->user_exists($_POST['username']) === true) {
            $errors[] = 'That username already exists';
        }
        if (!ctype_alnum($_POST['username'])) {
            $errors[] = 'Please enter a username with only alphabets and numbers';
        }
        if (strlen($_POST['password']) <6) {
            $errors[] = 'Your password must be atleast 6 characters';
        } elseif (strlen($_POST['password']) >18) {
            $errors[] = 'Your password cannot be more than 18 characters long';
        }
        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
            $errors[] = 'Please enter a valid email address';
        } elseif ($users->email_exists($_POST['email']) === true) {
            $errors[] = 'That email already exists.';
        }
    }

    if (empty($errors) === true) {

        $username    = htmlentities($_POST['username']);
        $password    = $_POST['password'];
        $email        = htmlentities($_POST['email']);

        $users->register($username, $password, $email);
        header('Location: register.php?success');
        exit();
    }
}

?>
<body>
	<div id="container">
		<section>
			<h1>Search</h1>

		   <form id="showSearchForm" class="" onsubmit="return validateSearch()" action="./results.php" method="post">
				<div>
					<label for="query">Query (required):</label>
					<input type="text" Name="query" id="query" size="30" maxlength="50" />
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
	var showNameElement = document.getElementById("query");
	var searchMsgElement = document.getElementById("searchDatabaseMsg");

	if ((! showNameElement)) {
		return false;
	}

	var showName = showNameElement.value.trim();

	if ((showName == "")) {
		searchMsgElement.innerHTML = ("You must specify some search value.");
		searchMsgElement.style.borderWidth = "1px";
		return false;
	}
    searchMsgElement.innerHTML = "";
	searchMsgElement.style.borderWidth = "0";
	return true;
}</script>
