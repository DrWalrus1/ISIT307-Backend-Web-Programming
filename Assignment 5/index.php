<?php
$games;
$filteredgames;

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
                trim($columns[4]) => $line[4],
            );
            $games[] = $newGame;
            $count++;
        }
        return $games;
}

function createCard($game) {
    $newCard =
    "<div class=\"cardCard\" id=\"" . $game["id"] . "\">
            <h3 name=\"title\" style=\"color:blue\">" . $game["Title"] . "</h4>
            <h5 name=\"genre\">". $game["Genre"] . "</h5>
            <h6 name=\"platform\">" . $game["Platform"] . "</h4>
            <h6 name=\"classification\">" . $game["Classification"] . "</h4>
            <h4 name=\"price\">$" . $game["Price"] . "</h4>
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
    if (isset($_GET['submit'])) {
        if (!empty($_GET['platform'])) {
            foreach ($_GET['platform'] as $selected) {
                $platforms[] = $selected;
            }
        }
    }
    return $platforms;
}

function getClassification() {
    $classification = array();
    if (isset($_GET['submit'])) {
        if (!empty($_GET['classification'])) {
            foreach ($_GET['classification'] as $selected) {
                $classification[] = $selected;
            }
        }
    }
    return $classification;
}

function getTitle() {
    if (isset($_GET['submit'])) {
        if (!empty($_GET['title'])) {
            return $_GET['title'];
        }
    }
}

function getMinPrice() {
    if (isset($_GET['submit'])) {
        if (!empty($_GET['min'])) {
            return $_GET['min'];
        }
    }
}

function getMaxPrice() {
    if (isset($_GET['submit'])) {
        if (!empty($_GET['max'])) {
            return $_GET['max'];
        }
    }
}

$games = readCSV();
$filteredgames = $games;
echo "<script>let array = " . json_encode($games) . ";</script>";

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
    <div class="header">
        <h1 style="display:inline-block">Game Store</h1>
        <a href="/admin.php" style="float: right;margin-right: 2em;padding-top: 1.75em;">Admin Page</a>
    </div>
    <div style="text-align:center">
        <div id="searchArea">
            <form id="searchForm">
                <label for="titleInput">Game Title:</label>
                <input id="titleInput" type="text" name="title" placeholder="Game Title"/>
                <br>
                <label for="minPrice">Minimum:</label>
                <input id="minPrice" class="priceFields" type="number" min="0" placeholder="0"/>
                <br>
                <label for="maxPrice">Maximum:</label>
                <input id="maxPrice" class="priceFields" type="number" max="100" placeholder="60"/>
                <br>
                <label>Platform:</label>
                <br>
                <div class="row">
                    <div class="column">
                        <input type="checkbox" id="PS4" name="platform[]" value="PS4"><label for="PS4">Playstation 4</label><br>
                        <input type="checkbox" id="PS3" name="platform[]" value="PS3"><label for="PS3">Playstation 3</label><br>
                        <input type="checkbox" id="Vita" name="platform[]" value="Vita"><label for="Vita">Playstation Vita</label><br>  
                        <input type="checkbox" id="Xbox360" name="platform[]" value="Xbox360"><label for="Xbox360">Xbox 360</label><br>
                        <input type="checkbox" id="XboxOne" name="platform[]" value="XboxOne"><label for="XboxOne">Xbox One</label><br>
                        <input type="checkbox" id="Switch" name="platform[]" value="Switch"><label for="Switch">Nintendo Switch</label><br>
                    </div>
                    <div class="column">
                        <input type="checkbox" id="Wii" name="platform[]" value="Wii"><label for="Wii">Nintendo Wii</label><br>
                        <input type="checkbox" id="WiiU" name="platform[]" value="Wii U"><label for="WiiU">Nintendo Wii U</label><br>
                        <input type="checkbox" id="3DS" name="platform[]" value="3DS"><label for="3DS">Nintendo 3DS</label><br>
                        <input type="checkbox" id="DS" name="platform[]" value="DS"><label for="DS">Nintendo DS</label><br>
                        <input type="checkbox" id="PC" name="platform[]" value="PC"><label for="PC">PC</label><br>
                    </div>
                </div>
                <label>Classification:</label>
                <br>
                <div class="row">
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
                <br>
                <br>
                <input class="button" type="submit" name="submit" value="Search"/>
                <button type="button" class="button" onclick="clearForm(this.form.id);">Clear</button>
            </form>

        </div>
        <hr>
        <div id="viewArea">
            <?php echo LoadGames($games)?>
            <br>
            <br>
        </div>
    </div>
    <footer>
        <script src="script.js"></script>
    </footer>
</body>
</html>