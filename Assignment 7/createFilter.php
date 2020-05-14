<?php

require_once "dbconnect.php";
require_once "dbinteraction.php";

// TODO: remove duplicate function
function loadGamesFromDB(mysqli $conn){
    $games = array();
    $query = "SELECT * FROM games";
    if ($result = $conn->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $newGame = array(
                "id" => $row["gID"],
                "title" => $row["title"],
                "price" => $row["price"],
                "genre" => getGenreNameByID($row["genre"]),
                "platform" => getPlatformNameByID($row["platform"]),
                "classification" => getClassificationInitialByID($row["classification"])
            );
            $games[] = $newGame;
        }
    }
    return $games;
}

function createFilter() {

}

// TODO: add aggregate counter of currently showing games matching this checkbox
function createLabel ($inputID, $displayName) {
    return "<label for=\"$inputID\">$displayName</label>";
}

function createTextInput($displayName, $inputID, $inputName, $placeholder = NULL) {
    $string =
    createLabel($inputID, $displayName) .
        "\n<input id=\"$inputID\" type=\"text\" name=\"$inputName\"";
    if (!is_null($placeholder)) {
        $string .= " placeholder=\"$placeholder\"";
    }
    $string .= "/>\n<br>\n";
    return $string;
}

function createNumberInput($displayName, $inputID, $inputName, $minNum = NULL, $maxNum = NULL, $placeholder = NULL) {
    $string =
    createLabel($inputID, $displayName) . 
        "\n<input id =\"$inputID\" type=\"number\" name=\"$inputName\"";
    if (!is_null($placeholder)) {
        $string .= " placeholder=\"$placeholder\"";
    }
    if(!is_null($minNum)) {
        $string .= " min=\"$minNum\"";
    }
    if(!is_null($maxNum)) {
        $string .= " max=\"$maxNum\"";
    }
    $string .= "/>\n<br>\n";
    return $string;
}

//Create individual checkbox with label
//TODO: add 2d array functionality
function createCheckboxInput($displayName, $inputID, $inputName, $isChecked) {
    $string =
    "<input type=\"checkbox\" id=\"$inputID\" name=\"$inputName\" value=\"$displayName\"";
    if ($isChecked) {
        $string .= " checked";
    }
    $string .= "\>" .
    createLabel($inputID, $displayName) .
    "<br>\n";
    return $string;
}

//Create grouped checkboxes e.g. manufacturer
function createGroupedCheckboxes(array $groupNames, $inputName) {
    // TODO: Add sticky form functionality
    $platforms = dbGetPlatforms();
    $string = "";
    foreach ($groupNames as $groupName) {
        $string .= 
        "<div id=\"$groupName\">\n" .
            "<input type=\"checkbox\" id=\"" . $groupName . "Checkbox\" name=\"platform[]\" value=\"$groupName\">" .
            "<label for=\"" . $groupName . "Checkbox\">$groupName</label><br>" .
            "<div id=\"" . $groupName . "Selection\" style=\"padding-left:0.75em\">";
        foreach ($platforms as $platform) {
            if ($platform["name"] != $platform["manufacturer"]) {
                if ($platform["manufacturer"] == $groupName) {
                    $string .= createCheckboxInput($platform["name"], $platform["initial"], $inputName, false);
                }
            }
        }
        $string .= "</div></div>";
    }
    return $string;
}

function getManufacturers(mysqli $conn) {
    $manufacturers = array();
    $query = "SELECT DISTINCT manufacturer FROM platforms";
    if ($result = $conn->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $manufacturers[] = $row["manufacturer"];
        }
    }
    return $manufacturers;
}

echo createTextInput("Game Title", "titleInput", "title", "Game Title");
echo createNumberInput("Minimum", "minPrice", "minPrice", 0, NULL, 0);
echo createGroupedCheckboxes(getManufacturers($conn), "platform[]");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Game Store</title>
</head>
<body>
    <?php include 'header.html'?>
    <footer>
        <script src="script.js"></script>
    </footer>
</body>
</html>