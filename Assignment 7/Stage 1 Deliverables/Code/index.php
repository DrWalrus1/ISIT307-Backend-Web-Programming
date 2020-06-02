<?php
require_once "dbinteraction.php";
require_once "createFilter.php";
$games;
$columns;
$genres;
$platforms;
$classifications;
$filterCount = array();


function createCard($game) { 
    $newCard =
    "<div class=\"cardCard\" id=\"" . $game["id"] . "\">
            <h3 name=\"title\" style=\"color:blue;padding:5%;\">" . $game["title"] . "</h4>
            <h5 name=\"genre\">". $game["genre"] . "</h5>
            <h6 name=\"platform\">" . $game["platform"] . "</h4>
            <h6 name=\"classification\">" . $game["classification"] . "</h4>
            <h4 name=\"price\">$" . $game["price"] . "</h4>
            <br><br>
    </div>";
    addToFilterCount($game["genre"]);
    addToFilterCount($game["platform"]);
    addToFilterCount($game["classification"]);
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

function getGenres() {
    $genres = array();
    if (!empty($_GET)) {
        if (!empty($_GET['genre'])) {
            foreach ($_GET['genre'] as $key) {
                $genres[] = $key;
            }
        }
    }
    if (!empty($genres)){
        return $genres;
    }
}

function getPlatforms() {
    $platforms = array();
    if (!empty($_GET)) {
        if (!empty($_GET['platform'])) {
            foreach ($_GET['platform'] as $key) {
                $platforms[] = $key;
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
        print_r($classification);
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

function getPriceRange() {
    if (!empty($_GET)) {
        if (!empty($_GET['pRange'])) {
            $range["val1"] = explode("-", $_GET['pRange'])[0];
            $range["val2"] = explode("-", $_GET['pRange'])[1];
            if (intval($range["val1"]) < intval($range["val2"])) {
                return array("min"=> intval($range["val1"]), "max" => intval($range["val2"]));
            } else {
                return array("min"=> intval($range["val2"]), "max" => intval($range["val1"]));
            }
        }
    }
}

function addToFilterCount($key) {
    global $filterCount;
    if (array_key_exists($key, $filterCount)) {
        $filterCount[$key]++;
    } else {
        $filterCount[$key] = 1;
    }
}

function setGroupFilterCount() {
    global $filterCount;
    $manufacturers = getPlatformNamesByManufacturers();
    foreach ($manufacturers as $key => $value) {
        $count = 0;
        for ($i = 0; $i < count($value); $i++) {
            if (array_key_exists($value[$i], $filterCount)) {
                $count += $filterCount[$value[$i]];
            }
        }
        $filterCount[$key] = $count;
    }
}

function FilterGames($games) {
    $title = getTitle();
    $minPrice = getPriceRange()["min"];
    $maxPrice = getPriceRange()["max"];
    $genres = getGenres();
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
                // TODO: add to filter count later 
                continue;
            }
        }
        
        //Max Price
        if (isset($maxPrice)) {
            if ($selected['price'] > $maxPrice) {
                continue;
            }
        }
        //Genre
        if (!empty($genres)) {
            $found = false;
            foreach ($genres as $key) {
                if ($key == $selected["genre"]) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                continue;
            }
        }

        //Platform
        if (!empty($platforms)) {
            $found = false;
            foreach ($platforms as $platform) {
                foreach ($platform as $key) {
                    if ($key === $selected["platform"]) {
                        $found = true;
                        break;
                    }
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
$games = loadGamesFromDB();
$genres = getGenres();
$classifications = getClassification();
$cards = LoadGames(FilterGames($games));
setGroupFilterCount();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Game Store</title>
</head>
<body>
    <?php include 'header.html';
    $string = "<div id=\"searchArea\" class=\"searchArea\"><form id=\"form1\" style=\"margin-left: 0.5em; padding-bottom: 7em\">" .
    createTextInput("Game Title", "titleInput", "title", "Game Title") . "<hr>" .
    createLabel("Price", "Price Range") . "<br>" .
    createNumberRange("$0-$20"/**/, "0-20", "pRange", "0-20", false) .
    createNumberRange("$21-$40"/**/, "21-40", "pRange", "21-40", false) .
    createNumberRange("$41-$60"/**/, "41-60", "pRange", "41-60", false) .
    createNumberRange("$61-$80"/**/, "61-80", "pRange", "61-80", false) .
    createNumberRange("$81-$100"/**/, "81-100", "pRange", "81-100", false);
    //Platform
    $string .= createLabel("Platform", "Platform") . "<br>";
    if (!empty($_GET) && isset($_GET["platform"]))
        $string .= createGroupedCheckboxes(getManufacturers($conn), "platform", "platform[]", $filterCount, $_GET["platform"]);
    else
        $string .= createGroupedCheckboxes(getManufacturers($conn), "platform", "platform[]", $filterCount);
    
    $string .= "<hr>";
    //Genres
    $string .= createLabel("Genre", "Genre") . "<br>";
    $dbgenres = dbGetGenre();
    
    foreach ($dbgenres as $key) {
        $count = "(0)";
        $found = false;
        if (isset($genres)) {
            for ($i = 0; $i < count($genres); $i++) {
                if ($genres[$i] == $key["name"]) {
                    $found = true;
                    break;
                }
            }
        }
        if (array_key_exists($key["name"], $filterCount)) {
            $count = "(" . $filterCount[$key["name"]] . ")";
        }
        $string .= createCheckboxInput($key["name"] . " " . $count, $key["name"], $key["name"], "genre[]", $found);
    }
    $string .= "<hr>";
    //Classification
    $string .= createLabel("Classification", "Classification") . "<br>";
    $dbclassification = dbGetClassification();
    foreach ($dbclassification as $key) {
        $count = "(0)";
        $found = false;
        if (isset($classifications)) {
            for ($i = 0; $i < count($classifications); $i++) {
                if ($classifications[$i] == $key["initial"]) {
                    $found = true;
                    break;
                }
            }
        }
        if (array_key_exists($key["initial"], $filterCount)) {
            $count = "(" . $filterCount[$key["initial"]] . ")";
        }
        $string .= createCheckboxInput($key["initial"] . " " . $count, $key["initial"], $key["initial"], "classification[]", $found);
    }
    $string .= "<hr>";
    $string .= '<input class="button" type="submit" value="Search" style="width: -webkit-fill-available;margin-right: 0.5em;height: 2em;"/><br>
    <div style="text-align:center">
        <button type="button" class="button" onclick="clearForm(this.form.id);">Clear</button>
    </div>';
    $string .= "</form></div>";
    echo $string;
    ?>
    
    <div style="text-align:center">
        <div id="viewArea" class="viewArea">
            <?php echo $cards;?>
            <br>
            <br>
        </div>
    </div>
    <footer>
        <script src="script.js"></script>
    </footer>
</body>
</html>
