<?php
    header('Content-Type: application/json; charset=UTF-8');
    session_start();
    session_regenerate_id();
    if ($_SESSION['login'] === false) {
        $retarr = [
            'check' => 'ok',
        ];
        echo json_encode($retarr);
        exit();
    }

    require_once('./common.php');

    function getMainComment (int $classid, int $commentid) {
        // 差分コメントの取得--------------------------

        try {

            $_SESSION['lastId'] = $commentid;
            $lastIdMain = $_SESSION['lastId'];

            $dbh = connectDB('mysql:dbname=past_questions;host=localhost;charset=utf8', 'root', 'Fumiya_0324');
            $sql = 'SELECT * FROM comment WHERE reply=-1 AND classid=? AND commentid>?';
            $stmt= $dbh->prepare($sql);
            $data[] = $classid;
            $data[] = $lastIdMain;
            $stmt->execute($data);
            unset($data);
            $dbh = null;
            
            $commentMainSum = $stmt->fetchAll();
            $tmp = end($commentMainSum)['commentid'];
            
            if ($tmp) {
                $_SESSION['lastId'] = $tmp;
            }
            
            reset($commentMainSum);

            $dbh = null;

            $retarr = [
                'lastId' => $_SESSION['lastId'],
                'result' => 'success',
                'main' => $commentMainSum,
            ];

            return $retarr;
        } catch (Exception $e) {
            $retarr = [
                'result' => 'fail',
                'stat' => 'databaseError'
            ];
            return $retarr;
        }
    }

    function replyComment(int $classid, int $commentid) {
        try {
            $dbh = connectDB('mysql:dbname=past_questions;host=localhost;charset=utf8', 'root', 'Fumiya_0324');
            $sql = 'SELECT * FROM comment WHERE reply=? AND classid=?';
            $stmt = $dbh->prepare($sql);
            $data[] = $commentid;
            $data[] = $classid;
            $stmt->execute($data);
            unset($data);
            $replyComment = $stmt->fetchAll();

            $retarr = [
                'result' => 'success',
                'replyComment' => $replyComment,
            ];
            return $retarr;
        } catch (Exception $e) {
            $retarr = [
                'result' => 'fail',
                'stat' => 'databaseError'
            ];
            return $retarr;
        }
    }

    function addComment (int $classid, string $stuid, string $comment, int $commentid) {
        // コメントの挿入----------------------

        try {
            $dbh = connectDB('mysql:dbname=past_questions;host=localhost;charset=utf8', 'root', 'Fumiya_0324');
            if ($commentid == -1) {
                $sql = 'INSERT INTO comment (classid, stuid, comment) VALUES (?, ?, ?)';
                $stmt = $dbh->prepare($sql);
                $data[] = $classid;
                $data[] = $stuid;
                $data[] = $comment;
                $stmt->execute($data);
                unset($data);
            } else {
                $sql = 'INSERT INTO comment (classid, stuid, comment, reply) VALUES (?, ?, ?, ?)';
                $stmt = $dbh->prepare($sql);
                $data[] = $classid;
                $data[] = $stuid;
                $data[] = $comment;
                $data[] = $commentid;
                $stmt->execute($data);
                unset($data);
            }
            $dbh = null;
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // function deleteComment(){}

    function addClass(int $classid, string $className) {
        try {
            $dbh = connectDB('mysql:dbname=past_questions;host=localhost;charset=utf8', 'root', 'Fumiya_0324');
            $sql = 'SELECT * FROM class WHERE classid=? OR classname=?';
            $stmt = $dbh->prepare($sql);
            $data[] = (int)$classid;
            $data[] = $className;
            $stmt->execute($data);
            unset($data);
            $dbh = null;

            if ($stmt->fetch() == true) {
                // すでに登録されている
                $retarr = [
                    'result' => 'fail',
                    'stat' => 'alreadyRegistered',
                ];
                return $retarr;
            } else {
                $dbh = connectDB('mysql:dbname=past_questions;host=localhost;charset=utf8', 'root', 'Fumiya_0324');
                $sql = 'INSERT INTO class (classid, classname) VALUES (?,?)';
                $stmt = $dbh->prepare($sql);
                $data[] = $classid;
                $data[] = $className;
                $stmt->execute($data);
                $dbh = null;
                
                $retarr = [
                    'result' => 'success'
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
    
    
    // WebAPI処理部分--------------------------
  
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if ($_POST['command'] == 'addComment') {
            $classid = h($_POST['classid']);
            $stuid = $_SESSION['stuid'];
            $comment = h($_POST['comment']);

            if (addComment($classid, $stuid, $comment, -1) == false) {
                $retarr = [
                    'result' => 'fail',
                    'stat' => 'failAddComment'
                ];
                echo json_encode($retarr);
                exit();
            }
            $retarr = getMainComment($classid, $_SESSION['lastId']);

            echo json_encode($retarr);
            exit();
        } else if ($_POST['command'] == 'addClass') {
            $className = h($_POST['className']);
            $classid = h($_POST['classid']);

            echo json_encode(addClass($classid, $className));
            exit();
        } else if ($_POST['command'] == 'addReplyComment') {
            $classid = h($_POST['classid']);
            $comment = h($_POST['comment']);
            $commentid = h($_POST['commentid']);
            $stuid = $_SESSION['stuid'];

            if (addComment($classid, $stuid, $comment, $commentid) == false) {
                $retarr = [
                    'result' => 'fail',
                    'stat' => 'failAddComment'
                ];
                echo json_encode($retarr);
                exit();
            }
            $retarr = replyComment($classid, $commentid);
            echo json_encode($retarr);
            exit();
        }
    } else if ($_SERVER['REQUEST_METHOD'] == 'GET') {

        if ($_GET['command'] == 'getMainComment') {
            $classid = h($_GET['classid']);
            $commentid = h($_GET['commentid']);
            echo json_encode(getMainComment($classid, $commentid));
            exit();
        } else if ($_GET['command'] == 'getReplyComment') {
            $classid = h($_GET['classid']);
            $commentid = h($_GET['commentid']);

            echo json_encode(replyComment($classid, $commentid));
            exit();
        }
    }
    // ---------------------------------------
?>