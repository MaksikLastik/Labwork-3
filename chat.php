<?php

    require_once 'connect.php';
    session_start();
    
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>My Chatbox</title>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
        <link rel = "stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body>
        <div class="container pt-4">
            <?php 
                $curUser = $_SESSION["curUser"];
                $user = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `users` WHERE `Id` = '$curUser' "));
                $chatId = $_SESSION["chatId"];
                $chat = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `chatrooms` WHERE `Id` = '$chatId' "));
            ?>
            <div class="text-center">
                <div class="col-md-6">
                    <h2 class="text-center">Вы в чате - <?= $chat["Chat"]; ?></h2>
                    <p>
                        <?php
                            $msgs = mysqli_query($connect, "SELECT * FROM `chatrooms`");
                                while($msg = mysqli_fetch_assoc($msgs)) {
                                    echo '<li><a href="?toChat='.$msg['Id'].'&curUser='.$curUser.'">'.$msg['Chat'].'</a></li>';
                                }
                        ?>
                    </p>
                    <form action='index.php?userName=<?=$curUser?>' method='POST'>
                        <button type="submit" class="btn btn-outline-secondary" type="button">Назад</button>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="container-fluid">
                    <?php 
                        if (isset($_GET['curUser'])) {
                            $curUser = $_GET['curUser'];
                        } else {
                            $curUser = $_SESSION["curUser"];
                        }

                        $user = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `users` WHERE `Id` = '$curUser' "));
                        
                        if (isset($_GET['toChat'])){
                            $chatId = $_GET['toChat'];
                        } else {
                            $chatId = $_SESSION["chatId"];
                        }
                        $chat = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `chatrooms` WHERE `Id` = '$chatId' "));

                    ?>
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4>
                                    <?php
                                        if (isset($_GET["toChat"])) {
                                            $toChat=$_GET["toChat"];
                                            $chatName = mysqli_query($connect, "SELECT * FROM `chatrooms` WHERE `Id` = '$toChat' ");
                                            $cName = mysqli_fetch_assoc($chatName);
                                            echo '<input type="text" value='.$toChat.' id="toChat" hidden/>';
                                            echo $cName["Chat"];  
                                        }
                                        else {
                                            $chatName = mysqli_query($connect, "SELECT * FROM `chatrooms`");
                                            $cName = mysqli_fetch_assoc($chatName);
                                            $_SESSION['toChat'] = $cName['Id'];
                                            $toChat = $cName['Id'];
                                            echo '<input type="text" value='.$toChat.' id="toChat" hidden/>';
                                            echo $cName['Chat'];
                                        }
                                    ?>
                                </h4>
                            </div>
                            <div class="modal-body" id="msgBody" style="height:400px; overflow-y: scroll; overflow-x: hidden;">
                                <?php
                                    if (isset($_GET["toChat"]))
                                        $chats = mysqli_query($connect, "SELECT * FROM messages WHERE ToChat = '".$_GET["toChat"]."'")
                                        or die("Failed to query database".mysql_error());
                                        
                                    else
                                        $chats = mysqli_query($connect, "SELECT * FROM messages WHERE ToChat = '".$_SESSION["toChat"]."'")
                                        or die("Failed to query database".mysql_error());
                                        
                                        while($chat = mysqli_fetch_assoc($chats)) {
                                            if ($chat["FromUser"] == $curUser) {
                                                echo "<div style='text-align:right;'>
                                                    <p style='background-color:lightblue; word-wrap:break-word; display:inline-block; padding:5px; border-radius:10px; max width:70%;'>".$chat["Message"]."
                                                    </p>
                                                </div>";
                                            } else {
                                                echo "<div style='text-align:left;'>
                                                    <p style='background-color:lightblue; word-wrap:break-word; display:inline-block; padding:5px; border-radius:10px; max width:70%;'>".$chat["Message"]."
                                                    </p>
                                                </div>";
                                            }
                                        }
                                        
                                ?>
                            </div>
                            <div class="modal-footer">
                                <textarea id="message" class="form-control" style="height:70px;"></textarea>
                                <button id="send" class="btn btn-primary" style="height:70%;">Отправить</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script type="text/javascript">
        document.getElementById("send").addEventListener('click', function() {         
            let msg = $("#message").val();
            $.ajax({
                url: "insertMessage.php",
                method: "POST",
                data: {
                    fromUser:$("#fromUser").val(),
                    toChat:$("#toChat").val(),
                    "message": msg
                },
                dataType: "text",
                success: function(data) {
                    $("#message").val("");
                }
            });
        });
        setInterval(function(){
            $.ajax({
                url: "realTimeChat.php",
                method: "POST",
                data:{
                    fromUser: $("#fromUser").val(),
                    toChat: $("#toChat").val(),
                    /* "message": msg */
                },
                dataType: "text",
                success: function(data) {
                    $("#msgBody").html(data);
                }
            });
        }, 700);
    </script>
</html>