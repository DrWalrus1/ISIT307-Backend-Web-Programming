<?php
define('DB_SERVER', 'localhost'); //TODO: MUST CHANGE BACK TO LOCALHOST WHEN FINISHED!!
define('DB_USERNAME', 'webLogin');
define('DB_PASSWORD', 'ThePasswordIsCarrot1');
define('DB_NAME', 'games');

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
include $_SERVER['DOCUMENT_ROOT'] . "/debugging.php";
?>