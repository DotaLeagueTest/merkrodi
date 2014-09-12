<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <?php

    session_start();
    include "bd.php";
    if (!empty($_SESSION['login']) and !empty($_SESSION['password']))
    {
        $login = $_SESSION['login'];
        $password = $_SESSION['password'];
        $result = $connection->query("SELECT id FROM users WHERE login='{$login}' AND password='{$password}'");
        $array = mysqli_fetch_array($result);
        if(empty($array['id']))
            exit ("Вход на эту страницу разрешен только зарегистрированным пользователям!");
    }
    else
        exit("Вход на эту страницу разрешен только зарегистрированным пользователям!");
    if (isset($_GET['id']))
        $id_message = $_GET['id'];
    $result = $connection->query("SELECT addressee FROM message WHERE id='{$id_message}'");
    $array = mysqli_fetch_array($result);
    if ($login == $array['addressee'])
    {
        $result = $connection->query("DELETE FROM message WHERE id = '{$id_message}' LIMIT 1");
        if ($result == 'true')
            echo "<meta http-equiv='Refresh' content='5; URL=page.php?id=".$_SESSION['id']."'></head><body>Ваше сообщение удалено! Вы будете перемещены через 5 сек. Если не хотите ждать, то <a href='page.php?id=".$_SESSION['id']."'>нажмите сюда.</a></body></html>";
        else
            echo "<meta http-equiv='Refresh' content='5; URL=page.php?id=".$_SESSION['id']."'></head><body>Ошибка! Ваше сообщение не удалено. Вы будете перемещены через 5 сек. Если не хотите ждать, то <a href='page.php?id=".$_SESSION['id']."'>нажмите сюда.</a></body></html>";
    }
    else
        exit ("Вы пытаетесь удалить сообщение, отправленное не вам!");
    ?>