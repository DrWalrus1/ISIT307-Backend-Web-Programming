<?php
    $title;
    $price;
    if (!empty($_GET)) {

        if (!empty($_GET["title"])) {
            $title = trim($_GET["title"]);
        }
        if (!empty($_GET["price"])) {
            if (is_numeric($_GET["price"])) {
                $price = intval($_GET["price"]);
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Assignment 3</title>
</head>
<body>
    <div class="header">
        <h1>Game Store</h1>
    </div>
    <div style="text-align:center">
        <form method="get">
            <label for="gameTitle">Game Title</label><br>
            <input type="text" id="gameTitle" name="title" value="<?php echo (isset($title)) ? $title: ""?>">
            <br>
            <?php
                if (!empty($_GET) && !isset($title)) {
                    echo "<span style=\"color: red\">Please enter a valid Game Title.</span>";
                }
            ?>
            <br>
            <label for="price">Price</label><br>
            <input type="text" id="price" name="price" value="<?php echo (isset($price)) ? $price: ""?>">
            <br>
            <?php
                if (!empty($_GET) && !isset($price)) {
                    echo "<span style=\"color: red\">Please enter a valid price.</span>";
                }
            ?>
            <br>
            <span>
                <input type="reset" value="Clear" class="button">
                <input type="submit" value="Submit" class="button">
            </span>
            <br>
            <?php
                if (!empty($_GET) && isset($title, $price)) {
                    echo "<span style=\"color: green\">All fields valid.</span>";
                }
            ?>
        </form>
    </div>
</body>
</html>