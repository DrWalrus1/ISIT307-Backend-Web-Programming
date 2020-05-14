<?php
$title;
$price;
$genre;
$platform;
$classification;
$acceptedFileTypes = array(".csv",".txt");
$file;
$goodFile;

require_once "dbconnect.php";
require_once "dbinteraction.php";


function checkInputs() {
    if (!empty($_GET["formID"])) {
        if ($_GET["formID"] == 1) {
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
        } else if ($_GET["formID"] == 2) {
            // TODO: Move to own function
            if (!empty($_GET["fileUpload"])) {
                $fileinfo = pathinfo($_GET["fileUpload"]);
                global $file, $acceptedFileTypes;
                foreach ($acceptedFileTypes as $key) {
                    if ("." . $fileinfo['extension'] == $key) {
                        $file = $_GET["fileUpload"];
                        return;
                    }
                }
            }
        }
    }
}

function submitLog() {
    echo $_SERVER['REMOTE_ADDR'];
}
//submitLog();
function getAcceptedFileTypes(){
    global $acceptedFileTypes;
    return implode(",", $acceptedFileTypes);
}

function AddGame($game) {
    $directory = fopen("games.csv", "a+") or die("Unable to open file!");
    fwrite($directory, "\n");
    fwrite($directory, implode(",", $game));
    fclose($directory);
    
}

function refValues($arr){
    if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+
    {
        $refs = array();
        foreach($arr as $key => $value)
            $refs[$key] = &$arr[$key];
        return $refs;
    }
    return $arr;
}

function bulkUpload($file, mysqli $conn) {
    $games = array();
    $directory = fopen($file, "r") or die("Unable to open file.");
    fgets($directory);
    // $columns = dbGetColumns($conn);
    $query = "INSERT INTO games (title, price, genre, platform, classification) VALUES "; //TODO have columns in query prepared
    $params = "";
    $valueTemp = "(?,?,?,?,?), "; 
    $paramsTemp = "sdiii";

    while(!feof($directory)) {
        $line = explode(",", fgets($directory));
        
        array_push($games, $line[0],
            sprintf("%.2f", floatval($line[1])),
            getGenreID($line[2]),
            getPlatformID($line[3]),
            getClassificationID(trim($line[4]))
        );
        $query .= $valueTemp; 
        $params .= $paramsTemp;
    }
    $query = rtrim($query,", ");
    $query .= ";";
    if ($stmt = $conn->prepare($query)) {
        array_unshift($games, $params);
        call_user_func_array(array($stmt, 'bind_param'), refValues($games));
        $stmt->execute();
    }
}

checkInputs();
if (isset($file)) {
    bulkUpload($file, $conn);
}
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
    <div style="text-align:center;margin-top: 6em;">
        <h2>Individual Upload</h2>
        <form method="get" id="gameForm">
            <input type="hidden" name="formID" value=1>
            <label for="gameTitle">Game Title</label><br>
            <input type="text" id="gameTitle" name="title" value="<?= (isset($title)) ? $title: ''?>"/>
            <br>
            <?php
                if (!empty($_GET) && !isset($title) && $_GET["formID"] == 1) {
                    echo "<span style=\"color: red\">Please enter a game title.</span>";
                }
            ?>
            <br>
            <label for="price">Price</label><br>
            <input type="text" id="price" name="price" value="<?= (isset($price)) ? $price: ""?>"/>
            <br>
            <?php
                if (!empty($_GET) && !isset($price) && $_GET["formID"] == 1) {
                    echo "<span style=\"color: red\">Please enter a valid price.</span>";
                }
            ?>
            <br>
            <label for="genre">Genre</label><br>
            <input type="text" id="genre" name="genre" value="<?= (isset($genre)) ? $genre: ""?>"/>
            <br>
            <?php
                if (!empty($_GET) && !isset($genre) && $_GET["formID"] == 1) {
                    echo "<span style=\"color: red\">Please enter a genre.</span>";
                }
            ?>
            <br>
            <label for="platform">Platform</label><br>
            <input type="text" id="platform" name="platform" value="<?= (isset($platform)) ? $platform: ""?>"/>
            <br>
            <?php
                if (!empty($_GET) && !isset($platform) && $_GET["formID"] == 1) {
                    echo "<span style=\"color: red\">Please enter a valid platform.</span>";
                }
            ?>
            <br>
            <label for="classification">Classification</label><br>
            <input type="text" id="classification" name="classification" value="<?= (isset($classification)) ? $classification: ""?>"/>
            <br>
            <?php
                if (!empty($_GET) && !isset($platform) && $_GET["formID"] == 1) {
                    echo "<span style=\"color: red\">Please enter a valid classification.</span>";
                }
            ?>
            <br>
            <span>
                <input type="reset" value="Clear" class="button" onclick="clearForm(this.form.id)"/>
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
    <!-- TODO: Add update game section -->
    <hr>
    <div style="text-align:center">
        <h2>Bulk Upload</h2>
        <form id="bulkForm" method="get">
            <input type="hidden" name="formID" value=2>
            <label for="fileInput">Upload File:</label><br>
            <input type="file" name="fileUpload" id="fileInput" accept="<?= getAcceptedFileTypes() ?>" style="margin-left: 3em;"><br>
            <?php
                if (!empty($_GET) && $_GET["formID"] == 2 && isset($goodFile) && $goodFile == false) {
                        echo "<span style=\"color: red\">Please upload a valid file type (" . getAcceptedFileTypes() . ").</span><br>";
                }
            ?>
            <input type="reset" value="Clear" class="button" onclick="clearForm(this.form.id)"/>
            <input type="submit" value="Upload" class="button"/><br><br>
            <textarea rows="8" cols="50" readonly>
            </textarea> <!-- TODO: FILL WITH BULK UPLOAD RESULTS -->
        </form>
    </div>
    <footer>
        <script src="script.js"></script>
    </footer>
</body>
</html>