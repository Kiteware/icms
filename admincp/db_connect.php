<?php
//Connect to sql or return error
 
function connect()
{
$dbUsername = 'sqluser';
$dbPassword = 'sqlpassword';
 
try {
$conn = new PDO('mysql:host=localhost;dbname=cms', $dbUsername, $dbPassword);
// echo ('Connection was a success!');
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
return $conn;
}
 
catch(PDOException $e) {
echo 'Error: ' . $e->getMessage();
}
}
 
?>
