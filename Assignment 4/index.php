<?php
$games;

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
                $columns[1] => $line[1],
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
            <h4 name=\"price\">" . $game["Price"] . "</h4>
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

$games = readCSV();
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
        <h1>Game Store</h1>
    </div>
    <div style="text-align:center">
        <a href="/admin.php">Admin Page</a>
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