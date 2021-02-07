<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>過去問データベース</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="add_class.js"></script>
</head>
<body>
    <?php

        require_once('./common.php');

        session_start();
        session_regenerate_id();
        if ($_SESSION['login'] == false || $_SESSION['nickname'] == 'guest') {
            print '<p>ログインができていません<p>';
            print '<p>ログインをしてください</p>';
            print '<a href="./index.html"></a>';
            exit();
        } else {
            print '<p>'.$_SESSION['nickname'].'さん</p>';
        }

    ?>

    <p>追加する授業名を入力してください(必須)</p>
    <p class="error"></p>
    <input type="text" placeholder="授業名" name="className">
    <p>授業コードを入力してください(必須)</p>
    <input type="text" placeholder="授業コード" name="classid">
    <button>追加</button>
</body>
</html>