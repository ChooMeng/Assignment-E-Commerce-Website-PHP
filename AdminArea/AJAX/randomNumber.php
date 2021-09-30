<?php
    session_start();

    //Generate random letter by using ascii number
    function randomLetter(){
	$letter = chr(rand(65,90));
	return $letter;
    }
    // Generate random number by using ascii number
    function randomNumber() {
        $letter = chr(rand(48,57));
        return $letter;
    }
    $orderNo = "";
    for ($i = 0; $i < 12; $i++) {
        $num = rand() % (2) + 1;
        if ($num == 1) {
            $orderNo = $orderNo.randomLetter();
        }
        else {
            $orderNo = $orderNo.randomNumber();
        }
    }
    echo $orderNo;