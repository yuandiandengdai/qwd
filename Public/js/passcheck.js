/**
 * Created by Administrator on 2016-10-22.
 */
function loadPageData() {
    var ques = $('#question_show').html(); //题目信息
    var xc = $('#to_validation').text(); //获取验证的字样
    var qid = $('#question_id').val();    //获取题目的题号,input的值
    var answer = $("#answer_check").val();    //获取输入的答案
    var currentItem = $('#to_validation').attr("currentItem");//当前题目的序号

    if (answer == '') {
        alert('请输入你的答案再提交！');
        $('#answer_check').focus();
        return false;
    }

    $.ajax({
        type: "GET",
        url: "check", //验证处理页
        data: "memberAnswer=" + answer + "&qid=" + qid + "&c=" + currentItem,
        dataType: "json",
        success: function (data) {
            if (data.sts == 1) {
                //题库发生未知错误
                // var flashMsg = setFlash(data.msg);
                // $('#content').html(flashMsg).find('.flashmsg').flashOut();
                // $('.game_body').hide(3000);
                $('#to_validation').text("请重新选择房间");
                $("#to_validation").attr("disabled", true);
            } else if (data.sts == 2) {
                //回答正确，再接再厉哦
                // $('#card').text(data.num);
                // var x = $('.subject').eq(currentItem).html(); //获取下一道题目的问题
                // $('#question_show').html(x); //显示赋值
                // var next = ++currentItem;
                $("#answer_check").val("");
                $('#answer_check').focus();
                // $('#to_validation').attr('currentItem', next);
                // var flashMsg = setFlash(data.msg);
                // $('#content').html(flashMsg).find('.flashmsg').flashOut();
            } else if (data.sts == 3) {
                //已经赢得本局比赛
                $('#card').text(data.num);
                // var flashMsg = setFlash(data.msg);
                // $('#content').html(flashMsg).find('.flashmsg').flashOut();
                $('.game_body').hide(3000);
                $('#to_validation').text("游戏结束");
                $("#to_validation").attr("disabled", true);
            } else if (data.sts == 4) {
                //回答错误
                $('#answer_check').focus();
                // var flashMsg = setFlash(data.msg);
                // $('#content').html(flashMsg).find('.flashmsg').flashOut();
            }
        }
    });
}

$.fn.flashOut = function () {
    var f = $(this);
    var fto = function (to, next) {
        if ($.isFunction(next)) {
            setTimeout(function () {
                f.css("visibility", to ? "" : "hidden");
                next.call(this);
            }, 200);
        } else {
            setTimeout(function () {
                f.fadeTo(3000, 0, function () {
                    f.slideUp("fast", function () {
                        $(this).remove();
                    })
                });
            }, 2000);
        }
    };
    setTimeout(function () {
        fto(0, function () {
            fto(1, function () {
                fto(0, function () {
                    fto(1, function () {
                        fto(0, function () {
                            fto(1, function () {
                                fto(0);
                            });
                        });
                    })
                })
            })
        });
    }, 1000);
    return this;
};

function setFlash($msg) {
    return '<div class="flashmsg alert alert-primary" role="alert" style="color: red;font-size: 1.3em;text-align: center">' + $msg +
        '</div>';
}

