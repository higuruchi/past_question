jQuery(function ($) {
    
    $('button').on('click', function (e) {
        let className = $('input[name=className]');
        let classid = $('input[name=classid]');

        if (className.val() == '' || classid.val() == '') {
            $('p[name=error]').text('すべての必須項目に入力をして下さい').css('color', 'red');
    
            if (className.val() == '') {
                className.css('background-color', 'red');
            }
            if (classid.val() == '') {
                classid.css('background-color', 'red');
            }

        } else {
            $.ajax({
                url : './api.php',
                type : 'POST',
                dataType : 'json',
                data:{
                    command : 'addClass',
                    className : className.val(),
                    classid : classid.val(),
                }
            }).done(function (data) {
                if (data.result == 'success') {
                    $('p.error').text('授業名：'+className.val()+' '+'授業コード：'+classid.val()+'を追加しました').css('color', 'blue');
                    className.val('');
                    classid.val('');
                } else if (data.result == 'fail') {
                    if (data.stat === 'alreadyRegistered') {
                        $('p[name=error]').text('授業名：'+className.val()+' '+'授業コード：'+classid.val()+'はすでに登録されています').css('color', 'red');
                    } else if (data.stat === 'databaseError') {
                        $('p[name=error]').text('データベースエラー').css('color', 'red');
                    }
                }
            }).fail(function (data) {
                ('p[name=error]').text('授業名：'+className.val()+' '+'授業コード：'+classid.val()+'の追加に失敗しました').css('color', 'blue');
            })
        }
    });

});