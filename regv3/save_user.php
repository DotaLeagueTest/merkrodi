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

    if(!is_uploaded_file($_FILES['picture']["tmp_name"]))
        $avatar = "avatars/net-avatars.jpg";
    else //загружаем изображение пользователя
    {
        $path_to_90_directory    = 'avatars/';
        if(preg_match('/[.](jpg)|(JPG)|(jpeg)|(JPEG)|(gif)|(GIF)|(png)|(PNG)$/', $_FILES['picture']['name']))
        {
            $filename = $_FILES['picture']['name'];
            $source = $_FILES['picture']['tmp_name'];
            $target =    $path_to_90_directory . $filename;
            move_uploaded_file($source,    $target);//загрузка оригинала в папку
            if(preg_match('/[.](GIF)|(gif)$/',    $filename))
                $im = imagecreatefromgif($path_to_90_directory.$filename);
            if(preg_match('/[.](PNG)|(png)$/',    $filename))
                $im = imagecreatefrompng($path_to_90_directory.$filename);
            if(preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)$/', $filename))
                $im = imagecreatefromjpeg($path_to_90_directory.$filename);

//СОЗДАНИЕ КВАДРАТНОГО ИЗОБРАЖЕНИЯ И ЕГО ПОСЛЕДУЮЩЕЕ СЖАТИЕ
            $w = 90;  //квадрат 90x90. Можно поставить и другой размер.
            $w_src = imagesx($im); //вычисляем ширину
            $h_src = imagesy($im); //вычисляем высоту изображения
            $dest = imagecreatetruecolor($w,$w); //результирующее изображение
            if ($w_src > $h_src)//обрезка изображения
            imagecopyresampled($dest, $im, 0, 0, round((max($w_src,$h_src)-min($w_src,$h_src))/2), 0, $w, $w, min($w_src,$h_src), min($w_src,$h_src));
            if    ($w_src < $h_src)//обрезка изображения
            imagecopyresampled($dest, $im, 0, 0, 0, 0, $w, $w, min($w_src,$h_src), min($w_src,$h_src));
            if    ($w_src == $h_src)//обрезка изображения
            imagecopyresampled($dest, $im, 0, 0, 0, 0, $w, $w, $w_src, $w_src);
            $date = time();//Имя результирующего файла будет текущее время
            imagejpeg($dest, $path_to_90_directory.$date.".jpg");
            $avatar = $path_to_90_directory.$date.".jpg";//заносим в переменную путь до аватара.
            //удаляем оригинал загруженного    изображения
            $delfull = $path_to_90_directory.$filename;
            unlink($delfull);
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
    $result_2 = $connection->query("INSERT INTO users (login,password,avatar) VALUES('{$login}','{$password}','{$avatar}')");
    if ($result_2 == 'true')
        echo "Вы успешно зарегистрированы! Теперь вы можете зайти на сайт.<br><a href='index.php'>Главная страница</a>";
    else
        echo "Ошибка! Вы не зарегистрированы.<br><a href='index.php'>Главная страница</a>";
?>
</html>
