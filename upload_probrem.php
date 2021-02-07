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
    if ($_SESSION['login'] == false && $_SESSION['nickname'] == 'guest') {
        print '<p>ログインができていません<p>';
        print '<p>ログインをしてください</p>';
        print '<a href="./index.html">ログイン画面</a>';
        exit();
    }

    require_once('./common.php');
    $classid = h($_GET['classid']);
    
    ?>
    <h1>過去問データベース</h1>
    <h>アップロードするファイルを選択してください</h>
</body>
</html>