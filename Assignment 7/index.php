<?php
require_once "dbinteraction.php";
require_once "createFilter.php";
$games;
$columns;
$platforms;
$classifications;

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
            <h3 name=\"title\" style=\"color:blue;padding:5%;\">" . $game["title"] . "</h4>
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
            foreach ($_GET['platform'] as $key) {
                // TODO: add check
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

function FilterGames($games) {
    $title = getTitle();
    $minPrice = getMinPrice();
    $maxPrice = getMaxPrice();
    $platforms = getPlatforms();
    $classifications = getClassification();
    print_r($classifications);
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
$columns = dbGetColumns($conn);
$platforms = dbGetPlatforms($conn);
$classifications = dbGetClassification($conn);
$games = loadGamesFromDB();
$genres = getUniqueGenres($games);
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
    $string = "<div id=\"searchArea\" class=\"searchArea\"><form id=\"form1\" style=\"margin-left: 0.5em\">" .
    createTextInput("Game Title", "titleInput", "title", "Game Title") . "<hr>" .
    createNumberInput("Minimum", "minPrice", "minPrice", 0, NULL, 0) . // TODO: change to checkbox of price range 
    createNumberInput("Maximum", "maxPrice", "maxPrice", NULL, 100, 100) . "<hr>";
    if (!empty($_GET) && isset($_GET["platform"]))
        $string .= createGroupedCheckboxes(getManufacturers($conn), "platform", "platform[]", $_GET["platform"]);
    else
        $string .= createGroupedCheckboxes(getManufacturers($conn), "platform", "platform[]");
    
    $string .= "<hr>";
    $string .= createLabel("Classification", "Classification") . "<br>";
    
    $classification = dbGetClassification();
    foreach ($classification as $key) {
        // TODO: Sticky form
        $string .= createCheckboxInput($key["initial"], $key["initial"], "classification[]", false);
    }
    $string .= "<br><hr>";
    $string .= '<input class="button" type="submit" value="Search" style="width: -webkit-fill-available;margin-right: 0.5em;height: 2em;"/><br>
    <div style="text-align:center">
        <button type="button" class="button" onclick="clearForm(this.form.id);">Clear</button>
    </div>';
    $string .= "</form></div>";
    echo $string;
    ?>
    
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