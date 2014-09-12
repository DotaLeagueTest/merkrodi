<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php
    session_start();
    include("bd.php");
    if (isset($_GET['id']))
        $id = $_GET['id'];
    else
        exit("Вы не зашли на сайт");
    if (!preg_match("|^[\d]+$|", $id))
        exit("Неверный    формат запроса! Проверьте URL");
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
        exit("Вход на эту страницу разрешен только зарегистрированным пользователям!");
    $result = $connection->query("SELECT * FROM users WHERE id='$id'");
    $array = mysqli_fetch_array($result);
    if(empty($array['login']))
        exit("Пользователя не существует! Возможно он был удален.");
?>

    <title> <?php echo $array['login']; ?> </title>
</head>

<body>

    <h2>Потльзователь "<?php echo $array['login']; ?>"</h2>

    <?php

    echo <<<LOL
    <a href='page.php?id=$_SESSION[id]'>Моя страница</a>|<a href='index.php'>Главная страница</a>|<a href='all_users.php'>Список пользователей</a>|<a href='exit.php'>Выход</a><br><br>
LOL;
    if ($array['login'] == $login)
    {
        echo <<<LOL
        <form action = 'update_user.php' method = 'post'>
            Изменить логин:<br>
            <input name = 'login' type = 'text'>
            <input type='submit' name='submit' value='Изменить'>
        </form>
        <br>
        <form action = 'update_user.php' method = 'post'>
            Изменить пароль:<br>
            <input name = 'password' type = 'password'>
            <input type = 'submit' name = 'submit' value = 'Изменить'>
        </form>
        <br>
        <form action = 'update_user.php' method = 'post' enctype = 'multipart/form-data'>
            Ваш аватар:<br>
            <img alt = 'Аватар' src='$array[avatar]'><br>
            Изображение должно быть формата jpg, gif или png. Изменить аватар:<br>
            <input type = "FILE" name="FupLoad">
            <input type = 'submit' name = 'submit' value = 'Изменить'>
        </form>
        <br>
        <h2>Личные    сообщения:</h2>
LOL;
        $tmp = $connection->query("SELECT * FROM message WHERE addressee='{$login}' ORDER BY id DESC");
        $message = mysqli_fetch_array($tmp);
        if(!empty($message['id']))
        {
            do
            {
                $author = $message['author'];
                $result2 = $connection->query("SELECT avatar, id FROM users WHERE login = '{$author}'");
                $array2 = mysqli_fetch_array($result2);
                if (!empty($array2['avatar']))
                    $avatar = $array2['avatar'];
                else
                    $avatar = "avatars/net-avatara.jpg";
                echo <<<LOL
                    <table>
                    <tr>

                    <td><a href='page.php?id=$array2[id]'><img alt = 'Аватар' src = '$avatar'></a></td>
                    <td>Автор: <a href='page.php?id=$array2[id]'>$author</a><br>
                    Дата: $message[date]<br>
                    Сообщение: $message[text]<br>
                    <a href='drop_post.php?id=$message[id]'>Удалить</a>
                    </td>
                    </tr>
                    </table><br>
LOL;
            } while ($message = mysqli_fetch_array($tmp));
        }
        else
            echo ("Сообщений нет");
    }
    else
    {
        echo <<<LOL
        <img alt = 'Аватар' src = '$array[avatar]'><br>
        <form action = 'post.php' method = 'post'><br>
            <h2>Отправить ваше сообщение: </h2>

            <textarea cols='43' rows='4'    name='text'></textarea><br>
            <input type='hidden' name='addressee'    value='$array[login]'>
            <input type='hidden' name='id'    value='$array[id]'>
            <input type='submit' name='submit' value='Отправить'>
        </form>
LOL;
    }
    ?>

</body>
</html>
