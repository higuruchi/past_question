jQuery(function ($) {
    let id = $('input[type=hidden][name=id]').val();


    // function addReplyComment(event) {

    //     console.log(event);

    //     if (comment.val() != '') {
    //         $.ajax({
    //             url : 'api.php',
    //             type : 'POST',
    //             dataType : 'json',
    //             data : {
    //                 command : 'addReplyComment',
    //                 classid : id,
    //                 comment : comment.val(),
    //                 commentid : commentid
    //             }
    //         }).done(function (data) {

    //         }).fail(function (data) {

    //         })
    //     }
    // }


    $(document).ready(function(){
        let commentid = $('summary div.mcomment:last').attr('data-commentid');
        if (commentid == undefined) {
            commentid = 0;
        }

        $.ajax({
            url : 'api.php',
            type : 'GET',
            data : {
                command : 'getMainComment',
                classid : id,
                commentid : commentid,
            } 
        }).done(function (data) {
            // 差分のデータを受け取る
            let mainComment = data.main;
            
           
            if (data.result == 'success') {
                if (mainComment.length != 0) {
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
                            $.ajax({
                                url : 'api.php',
                                type :'GET',
                                data : {
                                    command : 'getReplyComment',
                                    commentid : element.commentid,
                                    classid : id
                                }
                            }).done(function (data) {
                                let replyComment = data.replyComment;
                                repul.html('');
                                replyComment.forEach(function (element) {
                                    let li = $('<li></li>')
                                    let rettime = $('<div></div>').addClass('time').text(element.time);
                                    let retcomment = $('<div></div>').addClass('mcomment').text(element.comment);

                                    li.append(rettime).append(retcomment);
                                    repul.append(li);
                                });
                            }).fail(function () {
                                console.log('通信失敗');
                            });
                        });

                        let repText = $('<input>').attr('type', 'text');
                        let repButton = $('<button></button>').text('送信').attr('data-commentid', element.commentid);

                        details.append(repText).append(repButton);
                        // repButton.on('click', {comment : $(this).prev().val(), commentid : $(this).attr('data-commentid')}, addReplyComment);
                        // repButton.on('click',function () {console.log($(this).prev().val(), $(this).attr('data-commentid'))});
                   
                        
                   
                    });
                }
            }
        }).fail(function () {
            // 失敗した場合の処理
            console.log('通信に失敗しました');
        })
    });

    function addComment(event) {
        let comment = event.data.comment;
        if (comment.val() != '') {
            $.ajax({
                url:'api.php',
                type:'POST',
                dataType: 'json',
                data:{
                    command : 'addComment',
                    classid : id,
                    comment : comment.val(),
                }
            }).done(function (data) {
                comment.val('');
                let mainComment = data.main;
                console.log(data);
               
                if (data.result == 'success') {
                    if (mainComment.length != 0) {
                       mainComment.forEach(function (element) {
                           let time = $('<div></div>').addClass('time').text(element.time);
                           let comment = $('<div></div>').addClass('mcomment').attr('data-commentid', element.commentid).text(element.comment);
                           let details = $('<details></details>');
                           let summary = $('<summary></summary>');

                           summary.append(time).append(comment);
                           details.append(summary);
                           $('div.comment').append(details);
                       });
                    } else {
                        console.log('あれ？');
                    }
                }
                
            }).fail(function (data) {
                console.log('失敗しました');
                console.log(data);
            })
        } else {
            console.log('コメントなし');
        }
    }
    // コメントの送信
    $('button[name=submitComment]').on('click', {comment : $('input[type=text]')}, addComment);

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
                $('button[name=logInOut]').text('ログイン').removeClass('logout').addClass('login');
            }
        })
    });

    $('.login').on('click', function () {
        console.log('ok');
    })

    // 一定時間ごとに新しいメッセージを取得する

    $(function (){
        setInterval(function (){
            let commentid = $('summary div.mcomment:last').attr('data-commentid');
            if (commentid == undefined) {
                commentid = 0;
            } 

            console.log(commentid);
            $.ajax({
                url : 'api.php',
                type : 'GET',
                data : {
                    command : 'getMainComment',
                    classid : id,
                    commentid : commentid,
                } 
            }).done(function (data) {
                // 差分のデータを受け取る
                let mainComment = data.main;
                console.log(data);
               
                if (data.result == 'success') {
                    if (mainComment.length != 0) {
                       mainComment.forEach(function (element) {
                           let time = $('<div></div>').addClass('time').text(element.time);
                           let comment = $('<div></div>').addClass('mcomment').attr('data-commentid', element.commentid).text(element.comment);
                           let details = $('<details></details>');
                           let summary = $('<summary></summary>');

                           summary.append(time).append(comment);
                           details.append(summary);
                           $('div.comment').append(details);
                       });
                    }
                }
            }).fail(function () {
                // 失敗した場合の処理
                console.log('通信に失敗しました');
            })
        }, 10000);
    })

    // ----------------------------------------
});