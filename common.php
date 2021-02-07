<?php

    function h($str) {
        $str = htmlspecialchars($str , ENT_QUOTES, 'UTF-8');
        return $str;
    }
    
    function connectDB(string $dsn, string $user, string $password) {
        $dbh = new PDO($dsn,$user,$password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        return $dbh;
    }
?>