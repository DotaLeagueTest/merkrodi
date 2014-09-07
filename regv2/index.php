<?php
    session_start();
    include ("bd.php");
    if(!empty($_SESSION['login']) and !empty($_SESSION['password']))
    {
        $login = $_SESSION['login'];
        $password    = $_SESSION['password'];
        $result = $connection->query("SELECT id, avatar FROM users WHERE login='{$login}' AND password='{$password}'");
        $array = mysqli_fetch_array($result);
    }
?>

<html>

    <head>
        <title>Главная страница</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>

    <body>
        <h2>Главная страница</h2>

        <?php

            if (!isset($array['avatar']) or $array['avatar'] == '')
            {
echo <<<END
                <form action="testreg.php" method="post">
                    <p>
                        <label>Ваш логин:<br></label>
                        <input name="login" type="text" size="15" maxlength="15"
END;
                    if (isset($_COOKIE['login']))
                        echo 'value="'.$_COOKIE['login'].'">';
echo <<<WHERE
                    </p>
                    <p>
                        <label>Ваш пароль:<br></label>
                        <input    name="password" type="password" size="15"    maxlength="15"
WHERE;
                    if (isset($_COOKIE['password']))
                        echo 'value="'.$_COOKIE['password'].'">';
echo <<<WHERE
                    </p>
                    <p>
                        <input name="save" type="checkbox"    value='1'> Запомнить меня.
                    </p>
                    <p>
                        <input type="submit" name="submit" value="Войти">
                        <br>
                        <a href="reg.php">Зарегистрироваться</a>
                    </p>
                </form>
                <br>
                Вы вошли на сайт, как гость<br><a href='#'>Эта ссылка  доступна только зарегистрированным пользователям</a>
WHERE;
            }
            else
            {
echo <<<END
                Вы вошли на сайт, как $_SESSION[login] (<a href='exit.php'>выход</a>)<br>
                <a href='http://kazan.mvideo.ru/'>Эта ссылка доступна только зарегистрированным пользователям</a><br>
                Ваш    аватар:<br>
                <img alt='$_SESSION[login]' src='$array[avatar]'>
END;
            }
        ?>
    </body>

</html>