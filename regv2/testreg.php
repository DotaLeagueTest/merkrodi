<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<?php
    session_start();
    if (isset($_POST['login']))
        $login = $_POST['login'];
    if (isset($_POST['password']))
        $password = $_POST['password'];

    if (empty($login) or empty($password))
        exit ("Вы ввели не всю информацию, вернитесь назад и заполните все поля!");

    $login = stripslashes($login);
    $login = htmlspecialchars($login);
    $password = stripslashes($password);
    $password = htmlspecialchars($password);

    $login = trim($login);
    $password = trim($password);

    include ("bd.php");

//Получение IP
    $ip = getenv("HTTP_X_FORWARDED_FOR");
    if (empty($ip) || $ip == 'unknown')
    {
        $ip = getenv("REMOTE_ADDR");
    }
    $connection->query("DELETE FROM ip_users WHERE UNIX_TIMESTAMP() - UNIX_TIMESTAMP(date) > 900");
    $result = $connection->query("SELECT col FROM ip_users WHERE ip='{$ip}'");
    $array = mysqli_fetch_array($result);
    if ($array['col'] > 2)
        exit("Вы набрали логин или пароль неверно 3 раз. Подождите 15 минут до следующей попытки.");

    $password = md5($password);
    $password = strrev($password);
    $password = md5($password);
    $password = $password."lol";

    $result = $connection->query("SELECT * FROM users WHERE login='{$login}' AND password='{$password}'");
    $array = mysqli_fetch_array($result);

    if (empty($array['id']))
    {
        $select = $connection->query("SELECT ip FROM ip_users WHERE ip='{$ip}'");
        $tmp = mysqli_fetch_array($select);
        echo "ip: ".$ip;
        echo "tmp[ip]: ".$tmp['ip']."<br>";
        if ($ip == $tmp['ip'])
        {
            $result_2 = $connection->query("SELECT col FROM ip_users WHERE ip='{$ip}'");
            $array_2 = mysqli_fetch_array($result_2);
            $col = 1 + $array_2['col'];//ERROR
            $connection->query("UPDATE ip_users SET col='{$col}', date=NOW() WHERE ip='{$ip}'");
        }
        else
            $connection->query("INSERT INTO ip_users (ip, date, col) VALUES ('{$ip}', NOW(), '1')");
        exit ("Извините, введённый вами логин или пароль неверный.");
    }
    else
    {
        $_SESSION['password'] = $array['password'];//error
        $_SESSION['login'] = $array['login'];
        $_SESSION['id'] = $array['id'];

        if (isset($_POST['save']) && $_POST['save'] == 1)
        {
            setcookie("login", $_POST["login"], time()+9999999);
            setcookie("password", $_POST["password"], time()+9999999);
        }
    }
    //echo "<html><head><meta http-equiv='Refresh' content='0; URL=index.php'></head></html>";
?>

</html>
