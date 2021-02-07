jQuery(function($) {
    let id = $('input[type=hidden][name=id]').val();

    function addMainComment (comment) {
        if (comment != '') {
            $.ajax({
                url:'api.php',
                type:'POST',
                dataType: 'json',
                data : {
                    command : 'addComment',
                    classid : id,
                    comment : comment,
                }
            }).done(function (data) {
                insertMainComment(data.main);
            })
        }
    }

    function getMainComment (commentid) {
        $.ajax({
            url : 'api.php',
            type : 'GET',
            data : {
                command : 'getMainComment',
                classid : id,
                commentid : commentid
            }
        }).done(function (data) {
            let mainComment = data.main;

            if (data.result == 'success') {
                if (mainComment.length != 0) {
                    insertMainComment(mainComment);
                }
            }
        }).fail(function (data) {
            fail();
        })
    }

    function insertMainComment (mainComment) {

        mainComment.forEach(function (element) {
            let time = $('<div></div>').addClass('time').text(element.time);
            let comment = $('<div></div>').addClass('mcomment').attr('data-commentid', element.commentid).text(element.comment);
            let details = $('<details></details>');
            let summary = $('<summary></summary>');
            let repul = $('<ul></ul>');
            
            summary.append(time).append(comment);
            details.append(summary).append(repul);
            $('div.comment').append(details);

            summary.on('click', function () {
                getReplyComment(element.commentid, repul);
                
            });

            let repText = $('<input>').attr('type', 'text');
            let repButton = $('<button></button>').text('送信').attr('data-commentid', element.commentid);
            repButton.on('click', function () {
                let comment = $(this).prev();
                let commentid = $(this).attr('data-commentid');
                
                if (comment.val() != '') {
                    addReplyComment(comment.val(), commentid, repul);
                    comment.val('');
                }

            });
            details.append(repText).append(repButton);
        });
    }

    function addReplyComment(comment, commentid, repul) {
        
        $.ajax({
            url : 'api.php',
            type : 'POST',
            dataType : 'json',
            data : {
                command : 'addReplyComment',
                classid : id,
                comment : comment, 
                commentid : commentid
            }
        }).done(function () {
            getReplyComment(commentid, repul)
        }).fail(function () {
            fail();
            return false;
        });
    }

    function getReplyComment(commentid, repul) {
        $.ajax({
            url : 'api.php',
            type : 'GET',
            data : {
                command : 'getReplyComment',
                commentid : commentid,
                classid : id
            }
        }).done(function (data) {
            let replyComment = data.replyComment;

            repul.html('');
            replyComment.forEach(function (element) {
                let li = $('<li></li>');
                let rettime = $('<div></div>').addClass('time').text(element.time);
                let retcomment = $('<div></div>').addClass('mcomment').text(element.comment);

                li.append(rettime).append(retcomment);
                repul.append(li);
            })
        }).fail(function (data) {
            fail();
            return false;
        });
    }

    function fail() {
        alert('通信に失敗しました');
    }


    // ページをロードしたときにされる処理
    $(document).ready(function() {
        let commentid = $('summary div.mcomment:last').attr('data-commentid');
        if (commentid == undefined) {
            commentid = 0;
        }

        getMainComment(commentid)
    });

    // 送信ボタンを押したときにされる処理
    $('button[name=submitComment]').on('click', function () {
        let comment = $(this).prev();
        addMainComment(comment.val());
        comment.val('');
    });

    // ログアウト処理
    $('.logout').on('click', function () {
        $.ajax({
            url : 'login_api.php',
            type : 'GET',
            data : {
                command : 'logout'
            }
        }).done(function (data) {
            if (data.result == 'success') {
                alert('ログアウトしました');
                $('button.logout').toggle();
                $('button.show_login_form').toggle();
            }
        })
    });

    // ログイン処理
    $('button.show_login_form').on('click', function () {
        $('.login').toggle();
        $('button.show_login_form').toggle();
    });
    
    $('.login button').on('click', function () {
        let nickname = $('.login input[name=nickname]');
        let password = $('.login input[name=pass]');

        if (nickname.val() == '' || password.val() == '') {
            if (nickname.val() == '') {
                let en = $('.errorNickname');
                nickname.css('background-color', 'red');
                en.css('color', 'red');
                en.text("ユーザ名が入力されていません");
            }

            if (password.val() == '') {
                let ep = $('.errorPassword');
                password.css('background-color', 'red');
                ep.css('color', 'red');
                ep.text('パスワードが入力されていません');
            }
        } else {
            $.ajax({
                url : './login_api.php',
                type : 'POST',
                dataType : 'json',
                timeout : 10000,
                data : {
                    command : 'login',
                    nickname : nickname.val(),
                    password : password.val(),
                }
            }).done(function (data) {
                if (data.result === 'success') {
                    $('.userName p').text(data.nickname+'さん');
                    $('.login').toggle();
                    $('button.logout').toggle();
                } else if (data.result === 'fail') {
                    if (data.stat === 'noUser') {
                        alert('そのようなユーザは存在しません');
                    } else if (data.stat === 'databaseError') {
                        alert('データベースエラー');
                    }
                }
            }).fail(function (data) {
                alert('通信に失敗しました');
            });
        }
    })
})