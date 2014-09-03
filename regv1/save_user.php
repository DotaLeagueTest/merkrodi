<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<?php

    if (isset($_POST['login']))
        $login = $_POST['login'];

    if (isset($_POST['password']))
        $password = $_POST['password'];

    if (empty($login) or empty($password))
        exit("Вы ввели не полную информацию, вернитесь назад и заполните все поля!");

//Чистим логин и пароль от хлама, для избежания инъекций
    $login = stripslashes($login);
    $login = htmlspecialchars($login);
    $password = stripslashes($password);
    $password = htmlspecialchars($password);

//удаляем лишние пробелы
    $login = trim($login);
    $password = trim($password);

// подключаемся к базе
    include("bd.php");

// проверка на существование пользователя с таким же логином
    $result = $connection->query("SELECT id FROM users WHERE login='{$login}'");
    $array = mysqli_fetch_array($result);
    if (!empty($array['id']))
        exit ("Извините, введённый вами логин уже зарегистрирован. Введите другой логин.");

// сохраняем данные
    $result_2 = $connection->query("INSERT INTO users (login,password) VALUES('{$login}','{$password}')");
    if ($result_2 == 'true')
        echo "Вы успешно зарегистрированы! Теперь вы можете зайти на сайт.<br><a href='index.php'>Главная страница</a>";
    else
        echo "Ошибка! Вы не зарегистрированы. ERROR(" . $connection->errno . ") " . $connection->error;
?>
</html>
