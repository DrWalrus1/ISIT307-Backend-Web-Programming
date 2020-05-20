<?php
if ($_POST["type"] == "update") {
    echo "Update row: " . $_POST["rowID"];
} else if ($_POST["type"] == "delete") {
    echo "Delete row: " . $_POST["rowID"];
}

?>