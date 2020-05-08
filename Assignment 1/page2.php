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
        echo "Your monthly repayments are: $" . CalculateMortgage($loanAmount, $rate, $period);
    }
?>