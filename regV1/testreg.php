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

    $result = $connection->query("SELECT * FROM users WHERE login='{$login}'");
    $array = mysqli_fetch_array($result);

    if (empty($array['id']))
        exit ("Извините, введённый вами login или пароль неверный.");
    else
        if ($array['password'] == $password)
        {
            $_SESSION['login'] = $array['login'];
            $_SESSION['id'] = $array['id'];
            echo "Вы успешно вошли на сайт!<br><a href='index.php'>Главная страница</a>";
        }
        else
            exit ("Извините, введённый вами login или пароль неверный.");

?>

</html>
