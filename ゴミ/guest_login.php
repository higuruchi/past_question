<?php

    session_start();
    $_SESSION['login'] = true;
    $_SESSION['nickname'] = 'guest';
    header('Location: ./select_class.php');

?>