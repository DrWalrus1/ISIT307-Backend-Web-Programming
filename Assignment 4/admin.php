<?php
$title;
$price;
$genre;
$platform;
$classification;
function checkInputs() {
    if (!empty($_GET["title"])) {
        global $title;
        $title = $_GET["title"];
    }
    if (!empty($_GET["price"])) {
        global $price;
        $price = $_GET["price"];
    }
    if (!empty($_GET["genre"])) {
        global $genre;
        $genre = $_GET["genre"];
    }
    if (!empty($_GET["platform"])) {
        global $platform;
        $platform = $_GET["platform"];
    }
    if (!empty($_GET["classification"])) {
        global $classification;
        $classification = $_GET["classification"];
    }

    if (isset($title, $price, $genre, $platform, $classification)) {
        $gameArray = array($title, $price, $genre, $platform, $classification);
        
        AddGame($gameArray);
        header("Location: /");
    }
}

function submitLog(){
    echo $_SERVER['REMOTE_ADDR'];
}
//submitLog();

function AddGame($game) {
    $directory = fopen("games.csv", "a+") or die("Unable to open file!");
    fwrite($directory, "\n");
    fwrite($directory, implode(",", $game));
    fclose($directory);
    
}
checkInputs();

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
        <p style="display:inline-block">Admin Page</p>
    </div>
    <div style="text-align:center">
        <form method="get" id="gameForm">
            <label for="gameTitle">Game Title</label><br>
            <input class="clearable" type="text" id="gameTitle" name="title" value="<?= (isset($title)) ? $title: ''?>"/>
            <br>
            <?php
                if (!empty($_GET) && !isset($title)) {
                    echo "<span style=\"color: red\">Please enter a game title." . $title . "</span>";
                }
            ?>
            <br>
            <label for="price">Price</label><br>
            <input class="clearable" type="text" id="price" name="price" value="<?= (isset($price)) ? $price: ""?>"/>
            <br>
            <?php
                if (!empty($_GET) && !isset($price)) {
                    echo "<span style=\"color: red\">Please enter a valid price.</span>";
                }
            ?>
            <br>
            <label for="genre">Genre</label><br>
            <input class="clearable" type="text" id="genre" name="genre" value="<?= (isset($genre)) ? $genre: ""?>"/>
            <br>
            <?php
                if (!empty($_GET) && !isset($genre)) {
                    echo "<span style=\"color: red\">Please enter a genre.</span>";
                }
            ?>
            <br>
            <label for="platform">Platform</label><br>
            <input class="clearable" type="text" id="platform" name="platform" value="<?= (isset($platform)) ? $platform: ""?>"/>
            <br>
            <?php
                if (!empty($_GET) && !isset($platform)) {
                    echo "<span style=\"color: red\">Please enter a valid platform.</span>";
                }
            ?>
            <br>
            <label for="classification">Classification</label><br>
            <input class="clearable" type="text" id="classification" name="classification" value="<?= (isset($classification)) ? $classification: ""?>"/>
            <br>
            <?php
                if (!empty($_GET) && !isset($platform)) {
                    echo "<span style=\"color: red\">Please enter a valid classification.</span>";
                }
            ?>
            <br>
            <span>
                <input type="reset" value="Clear" class="button" onclick="clearForm()"/>
                <input type="submit" value="Add Game" class="button"/>
            </span>
            <br>
            <?php
                if (!empty($_GET) && isset($title, $price)) {
                    echo "<span style=\"color: green\">All fields valid.</span>";
                }
            ?>
        </form>
    </div>
    <footer>
        <script src="script.js"></script>
    </footer>
</body>
</html>