<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>過去問データベース</title>
</head>
<body>
    <?php
        
    require_once('./common.php');
    
    $stuid = $_POST['stuid'];
    $nickname = $_POST['nickname'];
    $pass = $_POST['pass'];

    $stuid = h($stuid);
    $nickname = h($nickname);
    $pass = h($pass);
    
    if (preg_match('/[0-9]{2}[A-Z][0-9]{3}/', $stuid) == false) {
        print '学籍番号が入力されていません<br/>';
    } else {
        print '学籍番号：'.$stuid;
        print '<br/>';
    }
    
    if ($nickname == '') {
        print 'ユーザ名が入力されていません<br/>';
    } else {
        print 'ユーザ名：'.$nickname;
        print '<br/>';
    }
    
    if ($pass == '') {
        print 'パスワードが入力されていません';
    } else {
        $pass = md5($pass);
    }
    
    if (preg_match('/[0-9]{2}[A-Z][0-9]{3}/', $stuid) == false || $nickname == '' || $pass == '') {
        print '<input type="button" onclick="history.back()" value="戻る">';
    } else {
        print '<form method="post" action="add_user_done.php">';
        print '<input type="hidden" name="stuid" value="'.$stuid.'">';
        print '<input type="hidden" name="nickname" value="'.$nickname.'">';
        print '<input type="hidden" name="pass" value="'.$pass.'">';
        print '<input type="submit" value="登録">';
        print '</form>';
    }
    
    ?>
    <input type="button" value="戻る" onclick="history.back()">
</body>
</html>
    