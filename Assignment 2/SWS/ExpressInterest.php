<?php
    if (isset($_GET['SearchBar'], $_GET['price'], $_GET['name'], $_GET["number"])) {
        $buyerdoc = fopen("buyer.txt",  "a+") or die("Unable to open file!");
        fwrite($buyerdoc, $_GET['SearchBar'] . "," . $_GET['price'] . "," . $_GET['name'] . "," . $_GET['number'] . "\n");
        fclose($buyerdoc);
        echo "<script>alert('Thank you for expressing your interest.');window.location.href='/';</script>";
    }
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Express Interest</title>
</head>
<body>
    <div style="text-align:center">
        <h1>Express Interest</h1>
        <h2><?php echo $_GET["SearchBar"]?></h2>
        <form id="buy">
            <input type="hidden" name="SearchBar" value="<?php echo $_GET['SearchBar']?>">
            <label for="proposal">Proposed price</label>
            <br>
            <input type="number" id="proposal" name="price">
            <br><br>
            <label for="name">Name</label>
            <br>
            <input type="text" id="name" name="name">
            <br><br>
            <label for="contact">Contact Number</label>
            <br>
            <input type="text" name="number">
            <br><br>
            <input type="submit" value="Express Interest">
        </form>        
    </div>
</body>
</html>