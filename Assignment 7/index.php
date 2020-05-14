<?php
$games;
$columns;
$platforms;
$classifications;

require_once "dbconnect.php";
require_once "dbinteraction.php";

//TODO: change to read from database
function readCSV(){
    $games = array();
    $directory = fopen("games.csv", "r+") or die("Unable to open file.");

    $columns = explode(",", fgets($directory));
    $count = 0;
        while(!feof($directory)) {
            $line = explode(",", fgets($directory));
            $newGame = array(
                "id" => $count,
                $columns[0] => $line[0],
                $columns[1] => sprintf("%.2f", floatval($line[1])),
                $columns[2] => $line[2],
                $columns[3] => $line[3],
                trim($columns[4]) => trim($line[4]),
            );
            $games[] = $newGame;
            $count++;
        }
        return $games;
}

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

function getUniqueGenres($games) {
    $genres = array();
    foreach ($games as $key) {
        $found = false;
        for ($i = 0; $i < count($genres); $i++) {
            if ($genres[$i] == $key["genre"]) {
                $found = true;
            }
        }
        if (!$found) {
            $genres[] = $key["genre"];
        }
    }
    return $genres;
}

function createCard($game) { 
    $newCard =
    "<div class=\"cardCard\" id=\"" . $game["id"] . "\">
            <h3 name=\"title\" style=\"color:blue\">" . $game["title"] . "</h4>
            <h5 name=\"genre\">". $game["genre"] . "</h5>
            <h6 name=\"platform\">" . $game["platform"] . "</h4>
            <h6 name=\"classification\">" . $game["classification"] . "</h4>
            <h4 name=\"price\">$" . $game["price"] . "</h4>
            <br><br>
    </div>";
    return $newCard;
}

function createRow($game1 = NULL, $game2 = NULL, $game3 = NULL) {
    $newRow = 
    "<div class=\"row\">";
        if (!is_null($game1))
            $newRow = $newRow . createCard($game1);
        if (!is_null($game2))
            $newRow = $newRow . createCard($game2);
        if (!is_null($game3))
            $newRow = $newRow . createCard($game3);
    $newRow = $newRow . "</div>";
    return $newRow;
}

function LoadGames($games) {
    $rows = "";
    $completeRows = intval(count($games) / 3);
    $partialRows = count($games) % 3;
    $totalrows = $completeRows;
    if ($partialRows > 0) {
        $totalrows = $completeRows+1;
    }

    for ($i = 0,$x = 0; $i < $completeRows; $i++) {
    $rows = $rows . createRow($games[$x], $games[$x+1], $games[$x+2]);
        $x = $x + 3;
    }
    if ($partialRows == 1) {
        $rows = $rows . createRow($games[$x]);
    } else if ($partialRows == 2) {
        $rows = $rows . createRow($games[$x], $games[$x+1]);
    }
    return $rows;
}

function getPlatforms() {
    $platforms = array();
    if (!empty($_GET)) {
        if (!empty($_GET['platform'])) {
            foreach ($_GET['platform'] as $selected) {
                $platforms[] = $selected;
            }
        }
    }
    if (!empty($platforms)){
        return $platforms;
    }
}

function getClassification() {
    $classification = array();
    if (!empty($_GET)) {
        if (!empty($_GET['classification'])) {
            foreach ($_GET['classification'] as $selected) {
                $classification[] = $selected;
            }
        }
    }
    if (!empty($classification)){
        return $classification;
    }
}

function getTitle() {
    if (!empty($_GET)) {
        if (!empty($_GET['title'])) {
            return $_GET['title'];
        }
    }
}

function getMinPrice() {
    if (!empty($_GET)) {
        if (!empty($_GET['minPrice'])) {
            return $_GET['minPrice'];
        }
    }
}

function getMaxPrice() {
    if (!empty($_GET)) {
        if (!empty($_GET['maxPrice'])) {
            return $_GET['maxPrice'];
        }
    }
}

function FilterGames($games) {
    $title = getTitle();
    $minPrice = getMinPrice();
    $maxPrice = getMaxPrice();
    $platforms = getPlatforms();
    $classifications = getClassification();

    $filtered = array();

    foreach ($games as $selected) {
        //Title
        if (isset($title)) {
            if (!is_numeric(strpos($selected['title'], $title))) {
                continue;
            }
        }
        //Min Price
        if (isset($minPrice)) {
            if ($selected['price'] < $minPrice) {
                continue;
            }
        }
        
        //Max Price
        if (isset($maxPrice)) {
            if ($selected['price'] > $maxPrice) {
                continue;
            }
        }
        //Platform
        if (!empty($platforms)) {
            $found = false;
            foreach ($platforms as $platform) {
                if ($platform === $selected["platform"]) {
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                continue;
            }
        }
        
        //Classification
        if (isset($classifications)) {
            $found = false;
            foreach ($classifications as $classification) {
                if ($classification === $selected["classification"]) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                continue;
            }
        }
        
        $filtered[] = $selected;
    }
    return $filtered;
}
$columns = dbGetColumns($conn);
$platforms = dbGetPlatforms($conn);
$classifications = dbGetClassification($conn);
$games = loadGamesFromDB($conn);
$genres = getUniqueGenres($games);

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
    <div id="searchArea" class="searchArea">
            <form id="searchForm">
                <label for="titleInput">Game Title:</label>
                <input id="titleInput" type="text" name="title" placeholder="Game Title"/>
                <br>
                <label for="minPrice">Minimum:</label>
                <input id="minPrice" class="priceFields" type="number" name="minPrice" min="0" placeholder="0"/>
                <br>
                <label for="maxPrice">Maximum:</label>
                <input id="maxPrice" class="priceFields" type="number" name="maxPrice" max="100" placeholder="100"/>
                <br>
                <label>Platform:</label>
                <br>
                <div>
                    <!-- TODO: dynamically add checkboxes -->
                    <!-- TODO: Add counters for current number being shown in viewArea -->
                    <div id="PlayStation">
                        <input type="checkbox" id="PlayStationCheckbox" name="platform[]" value="PlayStation"><label for="PlayStationCheckbox">PlayStation</label><br>
                        <div id="PlayStationSelection" style="padding-left:0.75em">
                            <input type="checkbox" id="PS4" name="platform[]" value="PlayStation 4"><label for="PS4">Playstation 4</label><br>
                            <input type="checkbox" id="PS3" name="platform[]" value="PlayStation 3"><label for="PS3">Playstation 3</label><br>
                            <input type="checkbox" id="Vita" name="platform[]" value="PlayStation Vita"><label for="Vita">Playstation Vita</label><br>
                        </div>
                    </div>
                    <div id="Xbox">
                        <input type="checkbox" id="XboxCheckbox" name="platform[]" value="Xbox"><label for="XboxCheckbox">Xbox</label><br>
                        <div id="boxSelection" style="padding-left:0.75em">
                            <input type="checkbox" id="Xbox360" name="platform[]" value="Xbox 360"><label for="Xbox360">Xbox 360</label><br>
                            <input type="checkbox" id="XboxOne" name="platform[]" value="Xbox One"><label for="XboxOne">Xbox One</label><br>
                        </div>
                    </div>
                    <div id="nintendo">
                        <input type="checkbox" id="NintendoCheckbox" name="platform[]" value="Nintendo"><label for="NintendoCheckbox">Nintendo</label><br>
                        <div id="nintendoSelection" style="padding-left:0.75em">
                            <input type="checkbox" id="Switch" name="platform[]" value="Nintendo Switch"><label for="Switch">Nintendo Switch</label><br>
                            <input type="checkbox" id="Wii" name="platform[]" value="Nintendo Wii"><label for="Wii">Nintendo Wii</label><br>
                            <input type="checkbox" id="WiiU" name="platform[]" value="Nintendo Wii U"><label for="WiiU">Nintendo Wii U</label><br>
                            <input type="checkbox" id="3DS" name="platform[]" value="Nintendo 3DS"><label for="3DS">Nintendo 3DS</label><br>
                            <input type="checkbox" id="DS" name="platform[]" value="Nintendo DS"><label for="DS">Nintendo DS</label><br>
                        </div>
                    </div>
                    <input type="checkbox" id="PC" name="platform[]" value="PC"><label for="PC">PC</label><br>
                </div>
                <label>Classification:</label>
                <br>
                <div style="display:flex">
                    <div class="column">
                        <input type="checkbox" id="G" name="classification[]" value="G"><label for="G">G</label><br>
                        <input type="checkbox" id="PG" name="classification[]" value="PG"><label for="PG">PG</label><br>
                        <input type="checkbox" id="M" name="classification[]" value="M"><label for="M">M</label><br>  
                    </div>
                    <div class="column">
                        <input type="checkbox" id="MA" name="classification[]" value="MA"><label for="MA">MA</label><br>
                        <input type="checkbox" id="R" name="classification[]" value="R"><label for="R">R</label><br>
                    </div>
                </div>
                <input class="button" type="submit" value="Search"/>
                <button type="button" class="button" onclick="clearForm(this.form.id);">Clear</button>
            </form>
        </div>
    <div style="text-align:center">
        <div id="viewArea" class="viewArea">
            <?php echo LoadGames(FilterGames($games))?>
            <br>
            <br>
        </div>
    </div>
    <footer>
        <script src="script.js"></script>
    </footer>
</body>
</html>