<?php
    session_start();
    if(empty($_SESSION['login']) or empty($_SESSION['password']))
        exit("Доступ на эту    страницу разрешен только зарегистрированным пользователям.<br><a href='index.php'>Главная страница</a>");
    unset($_SESSION['login']);
    unset($_SESSION['password']);
    unset($_SESSION['id']);
    setcookie("auto", "", time()+9999999); //очищаем автоматический вход
    exit("<html><head><meta http-equiv='Refresh' content='0; URL=index.php'></head></html>");
?>