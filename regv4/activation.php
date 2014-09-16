<?php
    include "bd.php";
    $result = $connection->query("SELECT avatar FROM users WHERE activation = '0' AND UNIX_TIMESTAMP() - UNIX_TIMESTAMP(date) > 3600");
    if (mysqli_stmt_num_rows($result) > 0)
    {
        $array = mysqli_fetch_array($result);
        do
        {
            if(strcasecmp($array['avatar'], "avatars/net-avatara.jpg") != 0)
                unlink ($array['avatar']);
        }while($array = mysqli_fetch_array($result));
        $connection->query("DELETE FROM users WHERE activation='0' AND UNIX_TIMESTAMP() - UNIX_TIMESTAMP(date) > 3600");
    }
    if(isset($_GET['code']))
        $code = $_GET['code'];
    else
        exit("Вы зашли на страницу без кода подтверждения!");
    if(isset($_GET['login']))
        $login = $_GET['login'];
    else
        exit("Вы зашли на страницу без логина!");
    $result = $connection->query("SELECT id FROM users WHERE login='{$login}'");
    $array = mysqli_fetch_array($result);
    $activation = md5($array['id']).md5($login);
    if(strcasecmp($activation, $code) == 0)
    {
        $connection->query("UPDATE users SET activation='1' WHERE login='{$login}'");
        echo "Ваш Е-мейл подтвержден! Теперь вы можете зайти на сайт под своим логином! <a href='index.php'>Главная страница</a>";
    }
    else
        echo "Ошибка! Ваш Е-мейл не подтвержден! <a href='index.php'>Главная страница</a>";
?>
