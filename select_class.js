jQuery(function ($) {
    $('input[type=submit]').on('click', function(e){
        if ($('input[name=classid]').val() == '' && $('input[name=classname]').val() == '' && $('input[type=radio]').val() == '') {
            $('p[name=error]').text('授業IDもしくは授業名を入力してください').css('color', 'red');
            e.preventDefault();
        }
    });

    $('button.show_login_form').on('click', function () {
        $('.login').toggle();
        $('button.show_login_form').toggle();
    });
    
    $('button.logout').on('click', function() {
        $.ajax({
            url : 'login_api.php',
            type : 'GET',
            data : {
                command : 'logout'
            }
        }).done(function (data) {
            if (data.result == 'success') {
                alert('ログアウトしました');
                $('.userName p').text('guestさん');
                $('button.logout').toggle();
                $('button.show_login_form').toggle();
            }
        })
    })

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
});