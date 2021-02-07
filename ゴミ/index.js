jQuery(function ($) {
    let nickname = $('input[type="text"][name="nickname"]');
    let password = $('input[type="password"][name="pass"]');

    $('input[type="submit"]').on('click', function(e){
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
            e.preventDefault();
        }
    });
});