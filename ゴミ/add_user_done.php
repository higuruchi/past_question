<?php
    require_once('./common.php');

    $stuid = $_POST['stuid'];
    $nickname = $_POST['nickname'];
    $pass = $_POST['pass'];

    $stuid = h($stuid);
    $nickname = h($nickname);
    $pass = h($pass);

    try {

        $dbh = connectDB('mysql:dbname=past_questions;host=localhost;charset=utf8', 'root', 'Fumiya_0324');
        $sql = 'INSERT INTO user (stuid, nickname, password) VALUES (?, ?, ?)';
        $stmt = $dbh->prepare($sql);
        $data[] = $stuid;
        $data[] = $nickname;
        $data[] = $pass;
        $stmt->execute($data);

        $dbh = null;
    } catch (Exception $e) {
        print $e;
        print 'データベースエラー';
    }

    session_start();
    session_regenerate_id();
    $_SESSION['login'] = true;
    $_SESSION['stuid'] = $stuid;
    $_SESSION['nickname'] = $nickname;
    header('Location:./select_class.php');
?>