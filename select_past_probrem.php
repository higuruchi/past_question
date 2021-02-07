<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>過去問データベース</title>
    <link rel="stylesheet" type="text/css" href="select_past_probrem.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="select_past_probrem.js"></script>
</head>
<body>

    <?php


    session_start();
    session_regenerate_id();
    if ($_SESSION['login'] == false) {
        print '<p>ログインができていません<p>';
        print '<p>ログインをしてください</p>';
        print '<a href="./select_class">ログイン画面</a>';
        exit();
    }

    require_once('./common.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $classid = h($_POST['classid']);
        $classname = h($_POST['classname']);
    } else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $classid = h($_GET['classid']);
        $classname = h($_GET['classname']);
    }

    // ここ微妙かな------------------
    if ($classid == false && $classname == false) {
        header('Location: ./select_class.php');
    }
    // --------------------------

    try {

        $dbh = connectDB('mysql:dbname=past_questions;host=localhost;charset=utf8', 'root', 'Fumiya_0324');
        
        if ($classid == '') {

            $sql = 'SELECT * FROM class WHERE classname=?';
            $stmt = $dbh->prepare($sql);
            $data[] = $classname;
            $stmt->execute($data);
            $result_class = $stmt->fetchAll();
            $classid = $result_class[0]['classid'];

        } else if ($classname == '') {

            $sql = 'SELECT * FROM class WHERE classid=?';
            $stmt = $dbh->prepare($sql);
            $data[] = $classid;
            $stmt->execute($data);
            $result_class = $stmt->fetchAll();
            $classname = $result_class[0]['classname'];

        } else {
            $sql = 'SELECT * FROM class WHERE classid=?';
            $stmt = $dbh->prepare($sql);
            $data[] = $classid;
            $stmt->execute($data);
            $result_class = $stmt->fetchAll();
        }
        unset($data);
        $dbh = null;
    } catch (Exception $e) {
        print $e;
        print 'データベースエラー';
    }

    ?>
    <header>
        <div class="hmenu">
            <h1 class="hmenu">過去問データベース</h1>
            <h2><?php print $classname; ?></h2>
        </div>

        <?php

        if ($result_class == false) {
            print '<p>そのような授業は存在しません</p>';
            exit();
        } 

        try {

            $dbh = connectDB('mysql:dbname=past_questions;host=localhost;charset=utf8', 'root', 'Fumiya_0324');
            $sql = 'SELECT * FROM class NATURAL JOIN pastprobrem WHERE classname=? AND classid=?';
            $stmt = $dbh->prepare($sql);
            $data[] = $classname;
            $data[] = (int)$classid;
            $stmt->execute($data);
            $result_past_probrem = $stmt->fetchAll();
            unset($data);

        } catch (Exception $e) {
            print $e;
            print 'データベースエラー';
            exit();
        }
        
        ?>
        <ul class="hmenu">
            <li><a class="upload" href="./upload_probrem.php?classid=<?php print $classid; ?>">アップロード</a></li>
            <li><a href="./select_class.php">クラス選択に戻る</a></li></li>
            <input type="hidden" name="id" value="<?php print $classid; ?>">
        </ul>
    </header>
    <div class="wrapper">
        <aside>
        <?php if ($result_past_probrem != false) { ?>
            <table>
                <thead>
                    <tr>
                        <th>年度</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    foreach ($result_past_probrem as $rec) {
                        print '<tr>';
                        print '<td>'.$rec['year'].'</td>';
                        print '<td><a href="'.$rec['directory'].'" download="'.$rec['directory'].'">ダウンロード</a></td>';
                        print '</tr>';
                    }
                    ?>
                <tbody>
            </table>
        <?php } else { ?>
            <div>この授業の過去問は登録されていません</div>
        <?php } ?>
        </aside>

        <!-- ここいかにコメント機能の実装 -->

        <div class="main">
            <div class="comment">
            </div>
            <div class="submit">
                <input type="text" placeholder="コメント" name="comentText">
                <button name="submitComment" class="button">送信</button>
            </div>
        </div>
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
    </div>
        
    <footer></footer>
</body>
</html>