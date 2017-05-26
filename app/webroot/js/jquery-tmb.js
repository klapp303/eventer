jQuery(function($) {
    $('input[type=file]').before('<span class="tmb-image"></span>');
    
    //アップロードするファイルを選択
    $('input[type=file]').change(function() {
        var file = $(this).prop('files')[0];
        
        //画像以外は処理を停止
        if (! file.type.match('image.*')) {
            //クリア
            $(this).val('');
            $('span').html('');
            return;
        }
        
        //画像表示
        var reader = new FileReader();
        reader.onload = function() {
            var img_src = $('<img class="tmb-display">').attr('src', reader.result);
            $('.tmb-image').html(img_src);
        };
        reader.readAsDataURL(file);
        //元画像を非表示
        $('.js-tmb_pre').hide();
    });
});