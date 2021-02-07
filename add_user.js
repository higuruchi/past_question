jQuery(function ($) {
    $('input[type=button]').on('click', function () {
        let eflg = false;
        let stuid =  $('input[name=stuid]');
        let nickname = $('input[name=nickname]');
        let pass = $('input[name=pass]');
        let pass2 = $('input[name=pass2]');

        // let pattern = '/[0-9]{2}[A-Z][0-9]{3}/';
        if (stuid.val() == '') {
            eflg = true;
            stuid.css('background-color', 'red');
        }

        if (nickname.val() == '') {
            eflg = true;
            nickname.css('background-color', 'red');
        }

        if (pass.val() == '' || pass2.val() == '' || pass.val() != pass2.val()) {
            eflg = true;
            if (pass.val() == '') {
                pass.css('background-color', 'red');
            }
            if (pass2.val() == '') {
                pass2.css('background-color', 'red');
            }
            if (pass.val() != pass2.val()) {
                pass.css('background-color', 'red');
                pass2.css('background-color', 'red');
            }
        }

        if (eflg === true) {
            $('.error').text('入力値が正しく入力されていません').css('color', 'red');
        } else {
            $.ajax({
                url : './login_api.php',
                type : 'POST',
                dataType : 'json',
                data : {
                    command : 'addUser',
                    stuid : stuid.val(),
                    nickname : nickname.val(),
                    pass : pass.val(),
                    pass2 :pass2.val()
                }
            }).done(function (data) {
                $('error').text(nickname+'さんを登録しました').css('color', 'blue');
                $('<a></a>').attr('href', './select_class.php').appendTo('body')

            }).fail(function (data) {
                console.log('fail');
            })
        }

    });
});