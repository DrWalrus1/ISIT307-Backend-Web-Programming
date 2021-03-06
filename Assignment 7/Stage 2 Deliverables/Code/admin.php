<?php
require_once "dbinteraction.php";

$games = loadGamesFromDB();
$title;
$price;
$genre;
$platform;
$classification;
$acceptedFileTypes = array(".csv",".txt");
$file;
$goodFile;

function checkInputs() {
    if (!empty($_GET["formID"])) {
        switch ($_GET["formID"]) {
            case 1:
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
                    $gameArray = array($title, $price, getGenreID($genre), getPlatformID($platform), getClassificationID($classification));
                    AddGame($gameArray);
                    header("Location: /admin.php");
                }
                break;
            case 3:
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
                break;
            default:
                header("Location: /admin");
            break;
        }
    }
}

function getAcceptedFileTypes(){
    global $acceptedFileTypes;
    return implode(",", $acceptedFileTypes);
}

function AddGame($game) {
    global $conn;
    $query = "INSERT INTO games (title, price, genre, platform, classification) VALUES (?,?,?,?,?);";
    if ($stmt = $conn->prepare($query)) { 
        $stmt->bind_param("sdiii", $game[0], $game[1], $game[2], $game[3], $game[4]);
        $result = $stmt->execute();
        echo $result;
    }
    
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

// FOR LATER IMPROVEMENT: https://dev.mysql.com/doc/refman/5.7/en/load-data.html
function bulkUpload($file, mysqli $conn) {
    $games = array();
    $directory = fopen($file, "r") or die("Unable to open file.");
    fgets($directory);
    // $columns = dbGetColumns($conn);
    $query = "INSERT INTO games (title, price, genre, platform, classification) VALUES ";
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

function createTableRow($game) {
    return 
    '<tr id=' . $game["id"] . '>' .
        "<td class=\"col1\"><input type=\"checkbox\" onchange=\"checkRow(this.parentElement.parentElement.id, this.checked);\"></td>" .
        '<td class=\"col2\"><div contenteditable name="title">' . $game["title"] . '</div></td>' .
        '<td class="col3" onclick="this.children[1].focus();">' .
            '<p style="margin:0;display:inline-block">$</p>' .
            '<div contenteditable name="price" style="display:inline-block;text-align:left;">' . $game["price"] . '</div>' .
        '</td>' .
        '<td class="col3"><div contenteditable name="genre">' . $game["genre"] . '</div></td>' .
        '<td class="col4"><div contenteditable name="platform">' . $game["platform"] . '</div></td>' .
        '<td class="col5"><div contenteditable name="classification">' . $game["classification"] . '</div></td>' .
        '<td class="col6"><button class="button" onclick="updateRow(this.parentElement.parentElement.id);">Update</button></td>' .
        '<td class="col6"><button class="button" onclick="deleteRow(this.parentElement.parentElement.id);">Delete</button></td>' .
    '</tr>';
}

function createTbody($games) {
    $string = "<tbody>";
    foreach ($games as $selected) {
        $string .= createTableRow($selected);
    }
    $string .= "</tbody>";
    return $string;
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
    <link rel="shortcut icon" href="">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="table.css">
    <title>Game Store</title>
</head>
<body>
    <?php include 'header.html'?>
    <div style="text-align:center;margin-top: 6em;">
        <div>
            <h2>Individual Upload</h2>
            <form method="get" id="gameForm">
                <input type="hidden" name="formID" value=1>
                <div style="display:inline-block">
                    <label for="gameTitle">Game Title: </label>
                    <input type="text" id="gameTitle" name="title" value="<?= (isset($title)) ? $title: ''?>"/>
                    <?php
                        if (!empty($_GET) && !isset($title) && $_GET["formID"] == 1) {
                            echo "<span style=\"color: red\">Please enter a game title.</span>";
                        }
                    ?>
                </div>
                <div style="display:inline-block">
                    <label for="price">Price: </label>
                    <input type="text" id="price" name="price" value="<?= (isset($price)) ? $price: ""?>"/>
                    <?php
                        if (!empty($_GET) && !isset($price) && $_GET["formID"] == 1) {
                            echo "<span style=\"color: red\">Please enter a valid price.</span>";
                        }
                    ?>
                </div>
                <!-- TODO: Change Genre, Platform and Classification to use list of distinct categories -->
                <!-- TODO: Add way to add new Genre, Platform and Classifications... -->
                <div style="display:inline-block">
                    <label for="genre">Genre: </label>
                    <select name="genre">
                        <option value="" selected disabled style="display:none"></option>
                    <?php
                        $platforms = dbGetGenre();
                        $string = "";
                        foreach ($platforms as $key) {
                            $string .= '<option value="' . $key["name"] . '">' . $key["name"] . '</option>';
                        }
                        echo $string;
                    ?>
                    </select>
                    <?php
                        if (!empty($_GET) && !isset($genre) && $_GET["formID"] == 1) {
                            echo "<span style=\"color: red\">Please enter a genre.</span>";
                        }
                    ?>
                </div>
                <div style="display:inline-block">
                    <label for="platform">Platform: </label>
                    <select name="platform">
                        <option value="" selected disabled style="display:none"></option>
                    <?php
                        $platforms = dbGetPlatforms();
                        $string = "";
                        foreach ($platforms as $key) {
                            $string .= '<option value="' . $key["name"] . '">' . $key["name"] . '</option>';
                        }
                        echo $string;
                    ?>
                    </select>
                    <?php
                        if (!empty($_GET) && !isset($platform) && $_GET["formID"] == 1) {
                            echo "<span style=\"color: red\">Please enter a valid platform.</span>";
                        }
                    ?>
                </div>
                <div style="display:inline-block">
                    <label for="classification">Classification: </label>
                    <select name="classification">
                        <option value="" selected disabled style="display:none"></option>
                    <?php
                        $platforms = dbGetClassification();
                        $string = "";
                        foreach ($platforms as $key) {
                            $string .= '<option value="' . $key["initial"] . '">' . $key["initial"] . '</option>';
                        }
                        echo $string;
                    ?>
                    </select>
                    <?php
                        if (!empty($_GET) && !isset($platform) && $_GET["formID"] == 1) {
                            echo "<span style=\"color: red\">Please enter a valid classification.</span>";
                        }
                    ?>
                </div>
                <br><br>
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
        <hr>
        <div style="text-align:center">
            <h2>Change Game Entry</h2>
            <label for="updateSearch">Search: </label><input type="text" id="updateSearch"/>
            <br><br>
            <div style="display:inline-block">
                <table>
                    <thead>
                        <tr>
                            <th scope="col" class="col1"></th>
                            <th scope="col" class="col2">Title</th>
                            <th scope="col" class="col3">Price</th>
                            <th scope="col" class="col3">Genre</th>
                            <th scope="col" class="col4">Platform</th>
                            <th scope="col" class="col5">Classification</th>
                            <th scope="col" class="col6"></th>
                            <th scope="col" class="col6"></th>
                        </tr>
                    </thead>
                    <?php echo createTbody($games)?>
                </table>
            </div>
            <br><br>
            <button class="button all" onclick="UpdateAll();" disabled>Update Selected</button> <!-- TODO: Have Javascript execute post/get on mass -->
            <button class="button all" onclick="DeleteAll();" disabled>Delete Selected</button> <!-- TODO: Have Javascript execute post/get on mass-->
        </div>
        <hr>
        <div style="text-align:center">
            <h2>Bulk Upload</h2>
            <form id="bulkForm" method="get">
                <input type="hidden" name="formID" value=3>
                <label for="fileInput">Upload File:</label><br>
                <input type="file" name="fileUpload" id="fileInput" accept="<?= getAcceptedFileTypes() ?>" style="margin-left: 3em;"><br>
                <?php
                    if (!empty($_GET) && $_GET["formID"] == 2 && isset($goodFile) && $goodFile == false) {
                            echo "<span style=\"color: red\">Please upload a valid file type (" . getAcceptedFileTypes() . ").</span><br>";
                    }
                ?>
                <div style="margin-top: 0.5em">
                    <input type="reset" value="Clear" class="button" onclick="clearForm(this.form.id)"/>
                    <input type="submit" value="Upload" class="button"/>
                </div>
            </form>
        </div>
        <hr>
        <h2>Change Log</h2> <!-- TODO: Finish Change Log -->
        <textarea rows="8" cols="50" readonly>
        </textarea>
        <br>
        <button style="cursor:pointer;" onclick="exportLog();">Export</button>
    </div>
    <footer>
        <script src="script.js"></script>
    </footer>
</body>
</html>