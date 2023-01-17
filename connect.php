<?php

    $connect = mysqli_connect('l3.lab3', 'root', '', 'chat_ajax');

    if (!$connect) {
        die('Error connect to DataBase');
    }

?>