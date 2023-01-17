<?php

    session_start();
    include("connect.php");

    $fromUser = $_POST["fromUser"];
    $toChat = $_POST["toChat"];
    $message = $_POST["message"];

    $output = "";

    $sql = "INSERT INTO messages (`FromUser`, `ToChat`, `Message`) VALUES('$fromUser','$toChat','$message')";

    if ($connect -> query($sql)) {
        $output .= "";
    }
    else {
        $output .= "Error. Try again!";
    }
    echo $output;

?>