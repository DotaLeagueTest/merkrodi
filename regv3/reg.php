<html>

    <head>
        <title>Регистрация</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>

    <body>
        <h2>Регистрация</h2>
        <form action="save_user.php" method="post" enctype="multipart/form-data">
            <p>
                <label>Ваш логин:<br></label>
                <input name="login" type="text" size="15" maxlength="15">
            </p>
            <p>
                <label>Ваш пароль:<br></label>
                <input name="password" type="password" size="15" maxlength="15">
            </p>
            <p>
                <label>Выберите аватар. Изображение должно быть формата jpg, gif или png:<br></label>
                <input type="file" name="picture">
            </p>
            <P>
                <?php
                    require_once('recaptchalib.php');
                    $PublicKey = "6Ld-BvoSAAAAAJEh9wnd5aob8hDzl4N1-B2D4P2B";
                    echo recaptcha_get_html($PublicKey);
                ?>
            </p>
                <input type="submit" name="submit" value="Зарегестрироваться"
            </p>
        </form>
    </body>

</html>
