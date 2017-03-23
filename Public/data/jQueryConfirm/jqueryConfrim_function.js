/**
 * Created by Administrator on 2016-10-15.
 */
$(function(){
    
    $(".example-pc-3").bind("click",function(){
        $.confirm({
            title: 'Confirm!',
            content: 'Simple confirm!',
            confirm: function(){
                $.alert('Confirmed!');
            },
            cancel: function(){
                $.alert('Canceled!')
            }
        });
    });
    
    // $('#to_validation').bind("click",function () {
    //     $.confirm({
    //         theme: 'supervan' // 'material', 'bootstrap'
    //     });
    // });

    $('#to_validation').click(function () {
        $.confirm({
            theme:'white'
        });
    });

    
});