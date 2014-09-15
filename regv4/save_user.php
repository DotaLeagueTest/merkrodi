<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<?php

    if (isset($_POST['login']))
        $login = $_POST['login'];

    if (isset($_POST['password']))
        $password = $_POST['password'];

    if (isset($_POST['email']))
        $email = $_POST['email'];

    if (empty($login) or empty($password) or empty($email))
        exit("Вы ввели не полную информацию, вернитесь назад и заполните все поля!");

    $check_mail = stripslashes($email);
    $check_mail = htmlspecialchars($email);
    if (strcasecmp($check_mail, $email) != 0)
        exit ("Неверно введен е-mail!");


    require_once('recaptchalib.php');
    $PrivateKey = "6Ld-BvoSAAAAAHjg1i2vNk9DAVSIFrK3kJsQf8VF";
    $resp = recaptcha_check_answer ($PrivateKey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
    if (!$resp->is_valid)
        exit("Неверно введен код с картинки. Вернитесь назад и попробуйте снова.");

//Чистим логин и пароль от хлама, для избежания инъекций
    $login = stripslashes($login);
    $login = htmlspecialchars($login);
    $password = stripslashes($password);
    $password = htmlspecialchars($password);

//удаляем лишние пробелы
    $login = trim($login);
    $password = trim($password);

//добавляем проверку на длину логина и пароля
    if (strlen($login) < 3 or strlen($login) > 15)
        exit ("Логин должен состоять не менее чем из 3 символов и не более чем из 15.");
    if (strlen($password) < 3 or strlen($password) > 30)
        exit ("Пароль должен состоять не менее чем из 3 символов и не более чем из 30.");

    if ($_FILES['picture']["error"] == 1)
        exit ("Ваш аватар превышает максимально допустимый размер 2MB");
    if(!is_uploaded_file($_FILES['picture']["tmp_name"]))//Проверяем, загружено ли изображение
        $avatar = "avatars/net-avatars.jpg";//Присваем стандартное значение
    else //загружаем изображение пользователя
    {
        $path_to_90_directory    = 'avatars/';
        if(preg_match('/[.](jpg)|(JPG)|(jpeg)|(JPEG)$/', $_FILES['picture']['name']))
        {
            $src = ImageCreateFromJPEG($_FILES['picture']["tmp_name"]);
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
            exit ("Аватар должен быть в формате <strong>JPG,GIF или PNG</strong>");
    }

//Шифрую пароль
    $password = md5($password);
    $password = strrev($password);
    $password = md5($password);
    $password = $password."lol";

// подключаемся к базе
    include("bd.php");

// проверка на существование пользователя с таким же логином
    $result = $connection->query("SELECT id FROM users WHERE login='{$login}'");
    $array = mysqli_fetch_array($result);
    if (!empty($array['id']))
        exit ("Извините, введённый вами логин уже зарегистрирован. Введите другой логин.");

// сохраняем данные
    $result_2 = $connection->query("INSERT INTO users (login,password,avatar,email,date) VALUES('{$login}','{$password}','{$avatar}','{$check_mail}',NOW())");
    if ($result_2 == 'true')
    {
        $result = $connection->query("SELECT id FROM users WHERE login='{$login}'");
        $array = mysqli_fetch_array($result);
        $activation = md5($array['id']).md5($login);//код активации аккаунта.
        $subject = "Подтверждение регистрации";//тема сообщения
        $message = "Здравствуйте! Спасибо за регистрацию на amidaniram.ru\n
            Ваш логин: ".$login."\n
            Перейдите по ссылке, чтобы активировать ваш аккаунт:\n
            http://amidaniram.ru/merkrodi/regv3/activation.php?login=".$login."&code=".$activation."\n
            С уважением,\n
            Администрация amidaniram.ru";//содержание сообщение
        mail($email, $subject, $message, "Content-type:text/plane; charset=UTF-8");//отправляем сообщение
        echo "Вам на E-mail выслано письмо с cсылкой, для подтверждения регистрации. Внимание! Ссылка действительна 1 час. <a href='index.php'>Главная страница</a>";
    }
    else
        echo "Ошибка! Вы не зарегистрированы.<br><a href='index.php'>Главная страница</a>";
?>
</html>
