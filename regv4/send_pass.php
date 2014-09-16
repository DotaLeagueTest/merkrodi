<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Забыли пароль?</title>


<?php
    if(isset($_POST['login']))
        $login = $_POST['login'];
    if(isset($_POST['email']))
        $email = $_POST['email'];

    if (empty($login) or empty($email))
    {
        echo <<<LOL
        <body>
            <h2>Забыли пароль?</h2>
            <form action="#" method="post">
            <p>
                Введите Ваш логин: <input type="text" name="login">
            </p>
            <p>
                Введите Ваш E-mail: <input type="text" name="email">
            </p
            <p>
                <input type="submit" name="submit" value="Отправить">
            </p>
            </form>
        </body>
LOL;
    }
    else
    {
        include "bd.php";
        $result = $connection->query("SELECT id FROM users WHERE login='{$login}' AND email='{$email}' AND activation='1'");
        $array = mysqli_fetch_array($result);
        if (empty($array['id']) or $array['id'] == '')
            exit ("Пользователя с таким e-mail адресом не обнаружено. <a href='index.php'>Главная страница</a>");
        $new_password = uniqid(mt_rand(), true);
        $new_password = substr($new_password, mt_rand(0, 15), 6);

        $password = md5($new_password);
        $password = strrev($password);
        $password = md5($password);
        $password = $password."lol";

        $connection->query("UPDATE users SET password='{$password}' WHERE login='{$login}'");
        $message = '<html>
                        <head>
                            <title>Восстановление пароля</title>
                        </head>
                        <body>
                            Здравствуйте '.$login.'!<br>
                            Пароль для доступа к системе,<br>
                            после входа желательно его сменить.<br>
                            Пароль: '.$new_password.'<br>
                            С уважением,<br>
                            Администрация amidaniram.ru
                        </body>
                    </html>';
        mail($email, "Восстановление пароля", $message, "Content-type: text/html; charset=UTF-8\r\n\r\n");
        echo    "<meta http-equiv='Refresh' content='5; URL=index.php'></head><body>На Ваш e-mail отправлено письмо с паролем. Вы будете перемещены через 5 сек. Если не хотите ждать, то <a href='index.php'>нажмите сюда.</a></body></html>";
    }
?>
