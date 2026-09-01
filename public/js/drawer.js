/*
 * Drawer
 */
$(function() {
    var ScrTop;
    //ドロワーを有効
    $('#sidr').removeClass('d-none');

    $('#sidr-right').sidr({
        side: 'right',

        // Open
        onOpen: function(){
            //スクロール位置
            ScrTop = $(document).scrollTop();

            // オーバーラップ表示
            $('#dwrapper').removeClass('d-none');
            $('body').addClass('body-fixed');//$('body').css('overflow', 'hidden');
            $('body').css({'top': 0});

            //スクロール位置維持
            $('body').scrollTop(ScrTop);
        },

        // Close
        onCloseEnd: function(){

            // オーバーラップ解除
            $('#dwrapper').addClass('d-none');
            $('body').removeClass('body-fixed');//$('body').css('overflow', 'inherit');

            //スクロール位置維持
            $(document).scrollTop(ScrTop);
        }
    });

    //ドロワー解除
    $('#dwrapper').on('click',function(){
        $.sidr('close');
    });
    $('.dwrapper-close').on('click',function(){
        $.sidr('close');
    });

});

