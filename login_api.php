<?php

session_start();
require_once('./common.php');
header('Content-Type: application/json; charset=UTF-8');

function addUser(string $stuid, string $nickname, string $pass, string $pass2)
{
    $eflg = false;

    if (preg_match('/[0-9]{2}[A-Z][0-9]{3}/', $stuid) == false) {
        $eflg = true;
    }
    if ($nickname == '') {
        $eflg = true;
    }
    if ($pass == '' || $pass2 == '' || $pass != $pass2) {
        $eflg = true;
    }

    if ($eflg == true) {
        $retarr = [
            'result' => 'fail',
            'stat' => 'inputFailure'
        ];
        return $retarr;
    } else {
        try {
            $dbh = connectDB('mysql:dbname=past_questions;host=localhost;charset=utf8', 'root', 'Fumiya_0324');
            $sql = 'SELECT stuid FROM user WHERE stuid=?';
            $stmt = $dbh->prepare($sql);
            $data[] = $stuid;
            $stmt->execute($data);
            unset($data);

            if ($stmt->fetch() == false) {

                $sql = 'INSERT INTO user (stuid, nickname, password) VALUES (?, ?, ?)';
                $stmt = $dbh->prepare($sql);
                $data[] = $stuid;
                $data[] = $nickname;
                $data[] = $pass;
                $stmt->execute($data);
                unset($data);
                $dbh = null;
    
                $retarr = [
                    'result' => 'success',
                    'nickname' => $nickname
                ];
                return $retarr;
            } else {
                $retarr = [
                    'result' => 'fail',
                    'stat' => 'alreadyRegistered'
                ];
                return $retarr;
            }

        } catch (Exception $e) {
            $retarr = [
                'result' => 'fail',
                'stat' => 'databaseError'
            ];
            return $retarr;
        }
    }
}

function login(string $nickname, string $password)
{

    try {
        $dbh = connectDB('mysql:dbname=past_questions;host=localhost;charset=utf8', 'root', 'Fumiya_0324');
        $sql = 'SELECT * FROM user WHERE nickname=? AND password=?';
        $stmt = $dbh->prepare($sql);
        $data[] = $nickname;
        $data[] = $password;
        $stmt->execute($data);
        unset($data);
        $dbh = null;
        
        $result = $stmt->fetch();

        if ($result) {
            $stuid = $result['stuid'];
            $nickname = $result['nickname'];

            $_SESSION['stuid'] = $stuid;
            $_SESSION['nickname'] = $nickname;

            $retarr = [
                'result' => 'success',
                'stuid' => $stuid,
                'nickname' => $nickname
            ];
            return $retarr;
        } else {
            $retarr = [
                'result' => 'fail',
                'stat' => 'noUser'
            ];
            return $retarr;
        }
    } catch (Exception $e) {
        $retarr = [
            'result' => 'fail',
            'stat' => 'databaseError'
        ];
        return $retarr;
    }

}

function logout ()
{
    $_SESSION['nickname'] = 'guest';
    $_SESSION['stuid'] = '00X000';

    $retarr = [
        'result' => 'success'
    ];
    return $retarr;
}

// ------------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($_POST['command'] == 'login') {

        $nickname = h($_POST['nickname']);
        $password = md5(h($_POST['password']));

        echo json_encode(login($nickname, $password));
        exit();
    } else if ($_POST['command'] == 'addUser') {

        $stuid = h($_POST['stuid']);
        $nickname = h($_POST['nickname']);
        $pass = md5(h($_POST['pass']));
        $pass2 = md5(h($_POST['pass2']));

        $retarr = addUser($stuid, $nickname, $pass, $pass2);
        if ($retarr['result'] == 'success') {
            session_regenerate_id();
            $_SESSION['login'] = true;
            $_SESSION['stuid'] = $stuid;
            $_SESSION['nickname'] = $nickname;
        }
        echo json_encode($retarr);
        exit();
    }
} else if($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($_GET['command'] == 'logout') {
        echo json_encode(logout());
        exit();
    }
}
// ------------------------------------------------------

?>