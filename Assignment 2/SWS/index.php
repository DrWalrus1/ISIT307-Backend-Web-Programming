<?php
    $name;
    $email;
    $phone;
    $price;
    $plate;
    $kilometres;
    $totalowners;
    $repairs;
    $categories;
    $sellers = [];
    function ValidateEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    if (!empty($_GET)) {
        if ($_GET["formID"] == 1) {
            //GET DATA
            if (isset($_GET["name"])) {
                $name = $_GET["name"];
            }
            if (isset($_GET["email"])) {
                if (ValidateEmail($_GET["email"])) {
                    $email = $_GET["email"];
                }
            }
            if (isset($_GET["phone"])) {
                $phone = $_GET["phone"];
            }
            if (isset($_GET["price"])) {
                $price = $_GET["price"];
            }

            if (isset($_GET["plate"])) {
                $plate = $_GET["plate"];
            }
            if (isset($_GET["kilometres"])) {
                $kilometres = $_GET["kilometres"];
            }
            if (isset($_GET["totalowners"])) {
                $totalowners = $_GET["totalowners"];
            }
            if (isset($_GET["repairs"])) {
                $repairs = $_GET["repairs"];
            }
            //WRITE DATA
            $seller = array($name, $email, $phone, $price, $plate, $kilometres, $totalowners, $repairs);
            if (!empty($seller)){
                WriteSeller($seller);
            }
        }
    }

    function ReadSellers() {
        $sellers = array();
        $directory = fopen("directory.txt", "r+") or die("Unable to open file!");
        //read categories
        $categories = explode(",", fgets($directory));
        $count = 0;
        while(!feof($directory)) {
            $line = explode(",", fgets($directory));
            $newSeller = array( 
                "id" => $count,
                $categories[0] => $line[0],
                $categories[1] => $line[1],
                $categories[2] => $line[2],
                $categories[3] => $line[3],
                $categories[4] => $line[4],
                $categories[5] => $line[5],
                $categories[6] => $line[6],
                trim($categories[7]) => $line[7]);
                $sellers[] = $newSeller;
                $count++;
        }
        fclose($directory);
        return $sellers;
    }

    function WriteSeller($seller) {
        $directory = fopen("directory.txt", "a+") or die("Unable to open file!");
        fwrite($directory, "\n");
        fwrite($directory, implode(",", $seller));
        fclose($directory);
    }

    function createCard($seller) {
        $newCard =
        "<div class=\"cardCard\" id=\"" . $seller["id"] . "\" onclick=\"showDetails(this.id)\">
            <h3 id=\"platePlace\" style=\"color:blue\">" . $seller["plate"] . "</h4>
            <h5 id=\"kmPlace\">". number_format($seller["kilometres"]) . "km</h5>
            <h4 id=\"pricePlace\">$" . sprintf('%01.2f', $seller["price"]) . "</h4>
            <div id=\"details" . $seller["id"] . "\" style=\"display:none\" id=\"details1\">
                <p>
                    Total Owners: " . $seller["totalowners"] . "<br>
                    Recent Repairs: " . $seller["repairs"] . "<br>
                </p>
            </div>
            <button onclick=\"buttonClick(event);ExpressInterest('". $seller["plate"] . "')\" style=\"cursor:pointer\">Express interest</button>
            <br><br>
        </div>";
        return $newCard;
    }

    function createRow($seller1 = NULL, $seller2 = NULL, $seller3 = NULL) {
        $newRow = 
        "<div class=\"row\">";
            if (!is_null($seller1))
                $newRow = $newRow . createCard($seller1);
            if (!is_null($seller2))
                $newRow = $newRow . createCard($seller2);
            if (!is_null($seller3))
                $newRow = $newRow . createCard($seller3);
        $newRow = $newRow . "</div>";
        return $newRow;
    }

    function LoadSellers($sellers) {
        $rows = "";
        $completeRows = intval(count($sellers) / 3);
        $partialRows = count($sellers) % 3;
        $totalrows = $completeRows;
        if ($partialRows > 0) {
            $totalrows = $completeRows+1;
        }

        for ($i = 0,$x = 0; $i < $completeRows; $i++) {
        $rows = $rows . createRow($sellers[$x], $sellers[$x+1], $sellers[$x+2]);
            $x = $x + 3;
        }
        if ($partialRows == 1) {
            $rows = $rows . createRow($sellers[$x]);
        } else if ($partialRows == 2) {
            $rows = $rows . createRow($sellers[$x], $sellers[$x+1]);
        }
        return $rows;
    }

    $sellers = ReadSellers();
    
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Sell Car</title>
</head>
<body>
    <h1 style="text-align:center">Sell</h1>
    <hr>
    <div id="selldiv">
        <form method="get">
            <input type="hidden" name="formID" value="1">
            <label for="name">Name: </label>
            <input type="text" id="name" name="name" value="<?php echo (isset($name)) ? $name: ""?>">
            <br>
            <label for="email">Email Address: </label>
            <input required type="text" id="email" name="email" value=<?php echo (isset($email)) ? $email: ""?>>
            <?php
                if (!empty($_GET) && !isset($email) && $_GET["formID"] == 1) {
                    echo "<span style=\"color: red\">Please enter a valid email address.</span>";
                }
            ?>
            <br>
            <label for="phone">Phone Number: </label>
            <input type="text" id="phone" name="phone" value="<?php echo (isset($phone)) ? $phone: ""?>">
            <br>
            <label for="price">Price: </label>
            <input type="number" id="price" name="price" value=<?php echo (isset($price)) ? $price: ""?>>
            <br>
            <label for="plate">Plate: </label>
            <input required type="text" id="plate" name="plate" value="<?php echo (isset($plate)) ? $plate: ""?>">
            <br>
            <label for="kilometres">Kilometres: </label>
            <input required type="number" id="kilometres" name="kilometres" value=<?php echo (isset($kilometres)) ? $kilometres: ""?>>
            <br>
            <label for="totalowners">Total Owners: </label>
            <input type="text" id="totalowners" name="totalowners" value="<?php echo (isset($totalowners)) ? $totalowners: ""?>">
            <br>
            <label for="repairs">Recent Repairs: </label>
            <input type="area" id="repairs" name="repairs" value="<?php echo (isset($repairs)) ? $repairs: ""?>">
            <br><br>
            <input type="submit" value="Sell Car" style="cursor:pointer">
        </form>
    </div>
    <h1 style="text-align:center">Buy</h1>
    <hr>
    <div id="buydiv">
        <div id="searchArea" style="text-align:center">
            <span>
                <form method="get" action="/ExpressInterest.php" id="buyForm">
                    <input type="hidden" name="formID" value="2">
                    <label for="searchbar">Search Plate Number: </label>
                    <input type="text" id="searchbar" name="SearchBar">
                    <input type="submit" id="searchSubmit" value="Select Car">
                </form>
            </span>
        </div>
        <br>
        <div id="viewArea">
            <?php echo LoadSellers($sellers)?>
            <br>
            <br>
        </div>
    </div>
</body>
<footer>
    <script src="script.js"></script>
</footer>
</html>