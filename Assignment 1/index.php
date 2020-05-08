<?php
    $loanAmount;
    $rate;
    $period;

    function CalculateMortgage ($loanAmount, $rate, $paymentsNumber) {
        $rate /= 12;
        $paymentsNumber *= 12;
        return round(-($loanAmount * $rate / (1-(1/(1+$rate)^$paymentsNumber))), 2);
    }

    if(isset($_GET["loanAmount"], $_GET["rate"], $_GET["period"])) {
        $loanAmount = $_GET["loanAmount"];
        $rate = $_GET["rate"];
        $period = $_GET["period"];
    }
?>
<html>
<head>
    <title>Finance</title>
</head>
<body>
    <img src="2f0.png" style="height:400px;width:600px">
    <img src="downstonks.jpg" style="height:400px;width:600px">
    <br>
    <br>
    <button onclick="window.location='https://www.youtube.com/watch?v=G1IbRujko-A'">Youtube link</button>
    <br>
    <form method="get">
        <label for="loanAmount">Loan Amount:</label><br>
        <input required type="number" id="loanAmount" name="loanAmount" value=<?php echo (isset($loanAmount)) ? $loanAmount: ""?>><br>
        <label for="rate">Interest Rate:</label><br>
        <input required type="number" id="rate" name="rate" value=<?php echo (isset($rate)) ? $rate: ""?>><br>
        <label for="period">Number of years:</label><br>
        <input required type="number" id="period" name="period" value=<?php echo (isset($period)) ? $period: ""?>><br><br>
        <input type="submit" value="Calculate">
        <input type="submit" formaction="/page2.php" value="Submit to another page">
    </form>
    <br><br>
    <?php
        if (!empty($_GET)) {
            echo "<h1 id=\"resultLabel\">Your monthly repayments are: $" . CalculateMortgage($loanAmount, $rate, $period) . "</h1>";
        }
    ?>
    
</body>
</html>