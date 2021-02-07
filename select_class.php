<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>過去問データベース</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="select_class.js"></script>
    <link rel="stylesheet" type="text/css" href="select_class.css">
</head>
<body>
    <?php
        require_once('./common.php');

        session_start();
        session_regenerate_id();
        if (isset($_SESSION['login']) == false) {
            $_SESSION['login'] = true;
            $_SESSION['nickname'] = 'guest';
            $_SESSION['stuid'] = '00X000';
            $_SESSION['lastId'] = 0;
        }
        try {
            $dbh = connectDB('mysql:dbname=past_questions;host=localhost;charset=utf8', 'root', 'Fumiya_0324');
            $ret = $dbh->query('SELECT * FROM class');
        } catch(Exception $e) {
            print $e;
            print 'データベースエラー';
        }

    ?>

    <header>
        <div>
            <h1>過去問データベース</h1>
            <form method="post" action="select_past_probrem.php">
                <input type="text" name="classname">
                <input type="submit" value="検索">
            </form>
        </div>
        <div class="userName"><p><?php print $_SESSION['nickname']; ?>さん</p></div>
    </header>

    <dev class="wrapper">
        <aside>
            <!-- 検索欄に変化があるごとにここにリストを表示するのもありかも,難しそうだけ度 -->
            <!-- クラス検索APIの作成の必要があり -->
        </aside>
        
        <dev class="main">
            
            <ul>
                <?php foreach ($ret as $rec) { ?>
                    <li><a href="select_past_probrem.php?classid=<?php print $rec['classid']; ?>"><?php print $rec['classname']; ?></a></li>
                <?php } ?>
            </ul>
        

            <div class="add_button"><a href="./add_class.php">授業を追加する</a></div>
            <div class="add_button"><a href="./add_user.html">ユーザを追加する</a></div>
        </dev>
        
        <left>
            <div class="login">
                <p>ユーザ名を入力してください</p>
                <p class="errorNickname"></p>
                <input type="text" name="nickname" placeholder="ユーザ名"><br/>
                <p>パスワードを入力してください</p>
                <p class="errorPassword"></p>
                <input type="password" name="pass" placeholder="パスワード"><br/>
                <button>ログイン</button>
            </div>
            <button class="show_login_form" <?php if($_SESSION['nickname'] != 'guest'){?> style="display:none" <?php } ?>>ログイン</button>
            <button class="logout" <?php if($_SESSION['nickname'] == 'guest'){?> style="display:none" <?php } ?>>ログアウト</button> 
        </left>
    </dev>

    <footer></footer>

</body>
</html>