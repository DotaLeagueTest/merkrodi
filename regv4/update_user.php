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
        if(empty($array['id']))
            exit ("Вход на эту страницу разрешен только зарегистрированным пользователям!");
    }
    else
        exit("Вход на эту страницу разрешен только зарегистрированным пользователям!");
    $old_login = $_SESSION['login'];
    $id = $_SESSION['id'];
    $ava =    "avatars/net-avatars.jpg";
//Изменение логина
    if(isset($_POST['login']))
    {
        $login = $_POST['login'];
        $login = stripslashes($login);
        $login =    htmlspecialchars($login);
        $login = trim($login);
        if ($login == '')
            exit("Вы не ввели логин");
        if (strlen($login) < 3 or strlen($login) > 15)
            exit("Логин должен состоять не менее чем из 3 символов и не более чем из 15.");
        $result = $connection->query("SELECT id FROM users WHERE login='{$login}'");
        $array = mysqli_fetch_array($result);
        if (!empty($array['id']))
            exit("Извините, введённый вами логин уже зарегистрирован. Введите другой логин.");
        $result = $connection->query("UPDATE users SET login='{$login}' WHERE login='{$old_login}'");
        if($result == 'true')
        {
            $connection->query("UPDATE message SET author='{$login}' WHERE author='{$old_login}'");
            $_SESSION['login'] = $login;
            if    (isset($_COOKIE['login']))
                setcookie("login", $login, time()+9999999);
            echo "<meta http-equiv = 'Refresh' content='5; URL=page.php?id=".$_SESSION['id']."'></head><body>Ваш логин изменен! Вы будете перемещены через 5 сек. Если не хотите ждать, то <a href='page.php?id=".$_SESSION['id']."'>нажмите    сюда.</a></body></html>";
        }
    }
//Изменение пароля
    else if (isset($_POST['password']))
    {
        $password = $_POST['password'];
        $password = stripslashes($password);
        $password = htmlspecialchars($password);
        $password = trim($password);
        if ($password == '')
            exit("Вы не ввели пароль");
        if (strlen($password) < 3 or strlen($password) > 15)
            exit("Пароль должен    состоять не менее чем из 3 символов и не более чем из 15.");
        $password = md5($password);
        $password = strrev($password);
        $password = md5($password);
        $password = $password."lol";
        $result = $connection->query("UPDATE users SET password='{$password}' WHERE login='{$old_login}'");
        if ($result == 'true')
        {
            $_SESSION['password'] = $password;
            if (isset($_COOKIE['password']))
                setcookie("password", $_POST['password'], time()+9999999);
            echo "<meta http-equiv = 'Refresh' content='5; URL=page.php?id=".$_SESSION['id']."'></head><body>Ваш пароль изменен! Вы будете перемещены через 5 сек. Если не хотите ждать, то <a href='page.php?id=".$_SESSION['id']."'>нажмите сюда.</a></body></html>";
        }
    }
//Изменение аватара
    else if(!is_uploaded_file($_FILES['FupLoad']["tmp_name"]))
        exit("Файл не выбран! Выберите файл и повторите попытку.");
    else if ($_FILES['FupLoad']["error"] == 1)
        exit ("Ваш аватар превышает максимально допустимый размер 2MB");
    else
    {
        $path_to_90_directory    = 'avatars/';
        if(preg_match('/[.](jpg)|(JPG)|(jpeg)|(JPEG)$/', $_FILES['FupLoad']['name']))
        {
            $src = ImageCreateFromJPEG($_FILES['FupLoad']["tmp_name"]);
            $w_src = imagesx($src);
            $h_src = imagesy($src);
            if ($w_src > $h_src)
            {
                $koe = $w_src/90;
                $new_h_src = ceil($h_src/$koe);
                $dst = ImageCreateTrueColor (90, $new_h_src);
                ImageCopyResampled ($dst, $src, 0, 0, 0, 0, 90, $new_h_src, $w_src, $h_src);
            }
            else
            {
                $koe = $h_src/90;
                $new_w_src = ceil($w_src/$koe);
                $dst = ImageCreateTrueColor ($new_w_src, 90);
                ImageCopyResampled ($dst, $src, 0, 0, 0, 0, $new_w_src, 90, $w_src, $h_src);
            }
            $date = time();
            imagejpeg($dst, $path_to_90_directory.$date.".jpg");
            $avatar = $path_to_90_directory.$date.".jpg";
            imagedestroy($src);
        }
        else
            exit("Аватар должен быть в формате <strong>JPG</strong>");
        $result_2 = $connection->query("SELECT avatar FROM users WHERE login='{$old_login}'");
        $array = mysqli_fetch_array($result_2);
        $result = $connection->query("UPDATE users SET avatar='{$avatar}' WHERE login='{$old_login}'");
        if ($result == 'true')
        {
            if($array['avatar'] != $ava)
                unlink($array['avatar']);
            echo "<meta http-equiv='Refresh' content='5; URL=page.php?id=".$_SESSION['id']."'></head><body>Ваша аватарка изменена! Вы будете перемещены через 5 сек. Если не хотите ждать, то <a href='page.php?id=".$_SESSION['id']."'>нажмите сюда.</a></body></html>";
        }
    }
    ?>
