<?php
require_once "dbconnect.php";

if ($_POST["type"] == "update") {
    echo "Update row: " . $_POST["rowID"];
    print_r($_POST["data"]);
    // Check row
} else if ($_POST["type"] == "delete") {
    echo "Delete row: " . $_POST["rowID"];
}


function checkArray() {

}
?>