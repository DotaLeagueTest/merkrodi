<!DOCTYPE html>
<?php
    session_start();
    include ("bd.php");
    if (isset($_COOKIE['auto']) and isset($_COOKIE['login']) and isset($_COOKIE['password']))
    {
        if ($_COOKIE['auto'] == 'yes')
        {
            $_SESSION['password'] = md5(strrev(md5($_COOKIE['password'])))."lol";
            $_SESSION['login']=$_COOKIE['login'];
            $_SESSION['id']=$_COOKIE['id'];
        }
    }
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
echo <<<LOL
                <form action="testreg.php" method="post">
                    <p>
                        <label>Ваш логин:<br></label>
                        <input name="login" type="text" size="15" maxlength="15"
LOL;
                    if (isset($_COOKIE['login']))
                        echo 'value="'.$_COOKIE['login'].'">';
                    else
                        echo '>';
echo <<<LOL
                    </p>
                    <p>
                        <label>Ваш пароль:<br></label>
                        <input    name="password" type="password" size="15"    maxlength="15"
LOL;
                    if (isset($_COOKIE['password']))
                        echo 'value="'.$_COOKIE['password'].'">';
                    else
                        echo '>';
echo <<<LOL
                    </p>
                    <p>
                        <input name="save" type="checkbox" value='1'> Запомнить меня.
                    </p>
                    <p>
                        <input name="autovhod" type="checkbox" value='1'> Автоматический вход.
                    </p>
                    <p>
                        <input type="submit" name="submit" value="Войти">
                        <br>
                        <a href="reg.php">Зарегистрироваться</a>
                        <br>
                        <a href="send_pass.php">Забыли пароль?</a>
                    </p>
                </form>
                <br>
                Вы вошли на сайт, как гость<br><a href='#'>Эта ссылка  доступна только зарегистрированным пользователям</a>
LOL;
            }
            else
            {
echo <<<LOL
                | <a href='page.php?id=$_SESSION[id]'>Моя страница</a> | <a href='index.php'>Главная страница</a> | <a href='all_users.php'>Список пользователей</a> | <a href='exit.php'>Выход</a><br><br>
                Вы вошли на сайт, как $_SESSION[login]<br>
                <a href='http://kazan.mvideo.ru/'>Эта ссылка доступна только зарегистрированным пользователям</a><br>
                Ваш    аватар:<br>
                <img alt='$_SESSION[login]' src='$array[avatar]'>
LOL;
            }
        ?>
    </body>

</html>