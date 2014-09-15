<?php
    include "bd.php";
    $result = $connection->query("SELECT avatar FROM users WHERE activation = '0' AND UNIX_TIMESTAMP() - UNIX_TIMESTAMP(date) > 3600");
    if (mysqli_stmt_num_rows($result) > 0)
    {

    }
