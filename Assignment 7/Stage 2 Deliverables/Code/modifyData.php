<?php
require_once "dbinteraction.php";

if (!empty($_POST)) {
    if ($_POST["type"] == "update") {
        $array = checkArray($_POST["data"]);
        if (array_keys($array)[0] != "title") {
            // has errors
            print_r($array);
        } else {
            // all clear
            $array["id"] = $_POST["rowID"];
            updateRow($array);
        }
    } else if ($_POST["type"] == "delete") {
        deleteRow($_POST["rowID"]);
    }
} elseif (!empty($_GET)) { //DEBUGING
    if ($_GET["type"] == "update") {
        $array = checkArray($_GET["data"]);
        if (array_keys($array)[0] != "title") {
            // has errors
            print_r($array);
        } else {
            // all clear
            $array["id"] = $_GET["rowID"];
            updateRow($array);
        }
    } else if ($_GET["type"] == "delete") {
        deleteRow($_GET["rowID"]);
    }
}


function checkArray($array) {
    $errorlog = array();
    $error = false;
    if (strlen($array["title"]) < 1) {
        $errorlog[] = "Game Title cannot be empty.";
        $error = true;
    }
    if (!is_numeric($array["price"])) {
        $errorlog[] = "Price must be a number.";
        $error = true;
    } else {
        $array["price"] = sprintf("%.2f", floatval($array["price"]));
    }
    $genreID = getGenreID($array["genre"]);
    if (is_NULL($genreID)) {
        $errorlog[] = "Invalid Genre.";
        $error = true;
    } else {
        $array["genre"] = $genreID;
    }
    $platID = getPlatformID($array["platform"]);
    if (is_NULL($platID)) {
        $errorlog[] = "Invalid Platform.";
        $error = true;
    } else {
        $array["platform"] = $platID;
    }
    $classID = getClassificationID($array["classification"]);
    if (is_NULL($classID)) {
        $errorlog[] = "Invalid Classification.";
        $error = true;
    } else {
        $array["classification"] = $classID;
    }
    if ($error)
        return $errorlog;
    else
        return $array;
}

function updateRow($array) {
    global $conn;
    $query = "UPDATE games SET title = ?, price = ?, genre = ?, platform = ?, classification = ? WHERE gID = ?";
    if ($stmt = $conn->prepare($query)) {
        $result = $stmt->bind_param("sdiiii", $array["title"], $array["price"], $array["genre"], $array["platform"], $array["classification"], $array["id"]);
        if ( false===$result ) {
            die('bind_param() failed');
        }
        $result = $stmt->execute();
        if ( false===$result ) {
            die('execute() failed: '.$stmt->error);
        }
    }
}

function deleteRow($rowID) {
    global $conn;
    $query = "DELETE FROM games WHERE gID = ?";
    if ($stmt = $conn->prepare($query)) {
        $result = $stmt->bind_param("i", $rowID);
        if ( false===$result ) {
            die('bind_param() failed');
        }
        $result = $stmt->execute();
        if ( false===$result ) {
            die('execute() failed: '.$stmt->error);
        }

    }
}

?>