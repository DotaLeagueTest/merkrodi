<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<?php
    session_start();
    include "bd.php";
    if(!empty($_SESSION['login']) and !empty($_SESSION['password']))
    {
        $login = $_SESSION['login'];
        $password = $_SESSION['password'];
        $result = $connection->query("SELECT id FROM users WHERE login='{$login}' AND password='{$password}'");
        $array = mysqli_fetch_array($result);
        if(empty($array['id']))
            exit("Вход на эту страницу разрешен только зарегистрированным пользователям!");
    }
    else
        exit("Вход на эту страницу разрешен только зарегистрированным пользователям!");
    if (isset($_POST['id']))
        $id = $_POST['id'];
    if (isset($_POST['text']))
        $text = $_POST['text'];
    if (isset($_POST['addressee']))
        $addressee = $_POST['addressee'];
    $author = $_SESSION['login'];
    $date = date("Y-m-d");
    if (empty($author) or empty($text) or empty($addressee) or empty($date))
        exit("Вы ввели не всю    информацию, вернитесь назад и заполните все поля");
    $text = stripslashes($text);
    $text = htmlspecialchars($text);
    $result = $connection->query("INSERT INTO message (author, addressee, date, text) VALUES    ('$author','$addressee','$date','$text')");
    echo "<meta http-equiv = 'Refresh' content='5; URL=page.php?id=".$_SESSION['id']."'></head><body>Ваше сообщение передано! Вы будете перемещены через 5 сек. Если не хотите ждать, то <a href='page.php?id=".$_SESSION['id']."'>нажмите    сюда.</a></body></html>";
?>