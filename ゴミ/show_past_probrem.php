<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="select_past_probrem.js"></script>
    <title>過去問データベース</title>
</head>
<body>
    <?php
    session_start();
        session_regenerate_id();
        if ($_SESSION['login'] == false) {
            print '<p>ログインができていません<p>';
            print '<p>ログインをしてください</p>';
            print '<a href="./index.html"></a>';
            exit();
        }

        require_once('./common.php');


        $year = $_GET['year'];
        $classid = $_GET['classid'];

        try {

            $dsn = 'mysql:dbname=past_questions;host=localhost;charset=utf8';
            $user = 'root';
            $password = 'Fumiya_0324';
            $dbh = new PDO($dsn,$user,$password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            
            $sql = 'SELECT directory FROM pastprobrem WHERE classid=? AND year=?';
            $stmt = $dbh->prepare($sql);
            $data[] = $classid;
            $data[] = $year;
            $stmt->execute($data);
    
            $dbh = null;
        } catch (Exception $e) {
            print $e;
            print 'データベースエラー';
        }

        $directory = $stmt->fetch();

        var_dump($directory);
    
    ?>
    
</body>
</html>