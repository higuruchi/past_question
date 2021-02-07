<?php
    require_once('./common.php');

    $stuid = $_POST['nickname'];
    $pass = $_POST['pass'];

    $nickname = h($stuid);
    $pass = h($pass);

    $pass = md5($pass);

    try {

        $dsn = 'mysql:dbname=past_questions;host=localhost;charset=utf8';
        $user = 'root';
        $password = 'Fumiya_0324';
        $dbh = new PDO($dsn,$user,$password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        
        $sql = 'SELECT stuid FROM user WHERE nickname=? AND password=?';
        $stmt = $dbh->prepare($sql);
        $data[] = $nickname;
        $data[] = $pass;
        $stmt->execute($data);

        $dbh = null;
    } catch (Exception $e) {
        print $e;
        print 'データベースエラー';
    }

    
    $result = $stmt->fetch();
    
    if ($result == false) {
        print 'そのようなユーザは存在しません<br/>';
        print '<a href="./index.html">ログイン画面に戻る</a>';
        exit();
    }
    
    $stuid = $result['stuid'];
    session_start();
    $_SESSION['login'] = true;
    $_SESSION['stuid'] = $stuid;
    $_SESSION['nickname'] = $nickname;
    header('Location: ./select_class.php');
    
?>