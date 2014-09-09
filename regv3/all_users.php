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
    if (empty($array['id']))
        exit("Вход на эту страницу разрешен только зарегистрированным пользователям!");
}
else
    exit("Вход на эту    страницу разрешен только зарегистрированным пользователям!");
?>

    <title>Список пользователей</title>
</head>

<body>
    <h2>Список    пользователей</h2>

    <?php
    echo <<<LOL
    | <a href='page.php?id=$_SESSION[id]'>Моя страница</a> | <a href='index.php'>Главная страница</a> | <a href='all_users.php'>Список пользователей</a> | <a href='exit.php'>Выход</a><br>
LOL;
    $result = $connection->query("SELECT login, id FROM users ORDER BY login");
    $array = mysqli_fetch_array($result);
    do
    {
        echo <<<LOL
            <a href='page.php?id=$array[id]'>$array[login]</a><br>
LOL;
    } while ($array = mysqli_fetch_array($result));
    ?>

</body>
</html>