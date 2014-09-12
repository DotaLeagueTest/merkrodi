<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

    <head>
        <title>Регистрация</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <script type="text/javascript" src="ProgressBar/jquery-1.6.2.min.js"></script>
        <script type="text/javascript" src="ProgressBar/jquery-ui-1.8.14.custom.min.js"></script>
        <script type="text/javascript" src="ProgressBar/jquery.form.js"></script>
        <link href="ProgressBar/jquery-ui-1.8.14.custom.css" rel="stylesheet" type="text/css" />
        <style type="text/css">
            .ui-progressbar-value
            {
                background-image: url(ProgressBar/images/pbar-ani.gif);
                padding-left:10px;
                font-weight:normal;
            }
            #upload_form
            {
                display:block;
            }
            #progress
            {
                display: none;
            }
            #progress #bar
            {
                height: 22px;
                width: 300px;
            }
        </style>

        <script type="text/javascript">
            var t;
            progress = function()
            {
                $.ajax
                ({
                    url: 'upload.php',
                    dataType: 'json',
                    success: function(data)
                    {
                        if(data.percent)
                        {
                            $("#bar").progressbar({value: Math.ceil(data.percent)});
                            $('.ui-progressbar-value').text(data.percent+'%');
                        }
                    }
                });
            }
            $(document).ready(function() {
                $('#form').ajaxForm({
                    type: 'POST',
                    success: function()
                    {
                        clearTimeout(t);
                        $('#progress').html('<b>Файл был загружен!</b>');
                    },
                    beforeSubmit: function()
                    {
                        $('#upload_form').hide();
                        $('#progress').show();
                        t = setInterval("progress()", 10);
                    }
                });
                $('#cancel-form').ajaxForm({
                    success: function()
                    {
                        clearTimeout(t);
                        $('#progress').html('<b>Загрузка была отменена!</b>');
                    }
                });
            });
        </script>
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
                        <label>Выберите аватар. Изображение должно быть формата jpg:<br></label>
                        <input type="file" name="picture">
                        <!-- Прогресс бар -->
                        <div id="bar"></div><br />
                    </p>
                    <p>
                        <?php
                            require_once('recaptchalib.php');
                            $PublicKey = "6Ld-BvoSAAAAAJEh9wnd5aob8hDzl4N1-B2D4P2B";
                            echo recaptcha_get_html($PublicKey);
                        ?>
                    </p>
                    <p>
                        <input type="submit" name="submit" value="Зарегестрироваться">
                    </p>
                </form>
    </body>

</html>
