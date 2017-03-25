/**
 * Created by Administrator on 2017-3-25.
 */
$(document).ready(function () {
    var flag = true;
    if (flag) {
        $.confirm({
            content: '请选择桌子后开始游戏',
            autoClose: 'cancel|3000'
        });
        return false;
    }
});  //提示选择