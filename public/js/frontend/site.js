$(function(){
    $('.mycolorpicker').colorpicker();

    $('form .preview-btn').click(function () {
        $(this).button('loading');
        $('form .collage-btn').addClass('disabled');
        $('form').attr('method', 'get');
    });

    $('form .collage-btn').click(function () {
        $(this).button('loading');
        $('form .preview-btn').addClass('disabled');
        $('form').attr('method', 'post');
    });

});
