<?php
    session_start();
    include "connect.php";


    if (isset($_GET['userName'])){
        $curUser = $_GET['userName'];
    }
    else {
        $curUser = $_SESSION['userName'];
    }

    if (isset($_GET["curUser"])) {
        $curUser = $_GET["curUser"];
        mysqli_query($connect, "DELETE FROM `users` WHERE `users`.`Id` = '$curUser'");
        session_unset();
        header("location: signin.php");
    }

    if (isset($_GET["chatId"])) {
        $_SESSION["chatId"] = $_GET["chatId"];
        $_SESSION['curUser'] = $curUser;
        header("location: chat.php");
    }

?>


<!DOCTYPE html>
<html>
    <head>
        <title></title>
    </head>
    <body>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Выберите чат</h4>
                </div>
                <div class="modal-body">
                    <ol>
                        <?php
                            $chats = mysqli_query($connect, "SELECT * FROM chatrooms")
                            or die("Failed to query database".mysql_error());
                            while ($chat = mysqli_fetch_assoc($chats)) {
                                echo '<li>
                                    <a style="text-decoration: none" href="index.php?chatId='.$chat['Id'].'">'.$chat['Chat'].'</a>
                                </li>';
                            }
                        ?>
                        <br>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <form action='addChat.php' method='POST'>
                                    <button type="submit" class="btn btn-outline-secondary" type="button">Новый чат</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <form action="index.php?curUser='<?= $curUser ?>'" method='POST'>
                                <button type="submit" class="btn btn-outline-secondary" type="button">Смена пользователя</button>
                            </form>
                        </div>
                    </ol>
                </div>
            </div>
        </div>
    </body>
</html>