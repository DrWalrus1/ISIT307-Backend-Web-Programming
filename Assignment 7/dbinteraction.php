<?php

require_once "dbconnect.php";

// A challenge I rose too
function dbGetColumns(mysqli $conn){
    $columns = array();
    $query = "SELECT COLUMN_NAME
    FROM information_schema.COLUMNS
    WHERE table_name = 'games'
      AND COLUMN_KEY != 'PRI'
    ORDER BY ordinal_position;";
    if ($result = $conn->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $columns[] = $row['COLUMN_NAME'];
        }
        return $columns;
    }
}

function dbGetPlatforms() {
    global $conn;
    $platforms = array();
    $query = "SELECT * FROM platforms;";
    if ($result = $conn->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $newPlatform = array(
                "id" => $row["pID"],
                "initial" => $row["initial"],
                "name" => $row["pName"],
                "manufacturer" => $row["manufacturer"],
                "description" => (isset($row["description"]) ? $row["description"] : "") //see if isset is actually necessary
            );
            $platforms[] = $newPlatform;
        }
        return $platforms;
    }
}

function dbGetClassification() {
    global $conn;
    $classifications = array();
    $query = "SELECT * FROM classification;";
    if ($result = $conn->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $newClassification = array(
                "id" => $row["cID"],
                "initial" => $row["initial"],
                "description" => (isset($row["description"]) ? $row["description"] : "") //see if isset is actually necessary
            );
            $classifications[] = $newClassification;
        }
        return $classifications;
    }
}

function dbGetGenre(mysqli $conn) {
    $genres = array();
    $query = "SELECT * FROM genres;";
    if ($result = $conn->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $newGenre = array(
                "id" => $row["gID"],
                "name" => $row["gName"],
                "description" => (isset($row["description"]) ? $row["description"] : "") //see if isset is actually necessary
            );
            $genres[] = $newGenre;
        }
        return $genres;
    }
}

function getPlatformID($pName) {
    global $conn;
    $platforms = dbGetPlatforms($conn);
    foreach ($platforms as $key) {
        if ($key["name"] == $pName) {
            return $key["id"];
        }
    }
    // TODO Add contingency to add new platform when not found
}

function getClassificationID($cName) {
    global $conn;
    $classification = dbGetClassification($conn);
    foreach ($classification as $key) {
        if ($key["initial"] == $cName) {
            return $key["id"];
        }
    }
    // TODO Add contingency to add new classification when not found
}

function getGenreID($gName) {
    global $conn;
    $genre = dbGetGenre($conn);
    foreach ($genre as $key) {
        if ($key["name"] == $gName) {
            return $key["id"];
        }
    }
    // TODO Add contingency to add new genre when not found
}

//functions that convert id's to names/initials
function getPlatformNameByID($pID) {
    global $conn;
    $query = "SELECT pName from platforms WHERE pID = $pID";
    if ($result = $conn->query($query)) {
        while ($row = $result -> fetch_row()) {
            return $row[0];
        }
    }
}

function getClassificationInitialByID($cID) {
    global $conn;
    $query = "SELECT initial from classification WHERE cID = $cID";
    if ($result = $conn->query($query)) {
        while ($row = $result -> fetch_row()) {
            return $row[0];
        }
    }
}

function getGenreNameByID($gID) {
    global $conn;
    $query = "SELECT gName from genres WHERE gID = $gID";
    if ($result = $conn->query($query)) {
        while ($row = $result -> fetch_row()) {
            return $row[0];
        }
    }
}
?>