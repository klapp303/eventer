function imageDrag(divName, imgName) {
    var imgUrl = '/files/place/' + imgName;
    
    //画像を先にロードしてサイズを取得しておく
    var imgObj = new Image();
    imgObj.src = imgUrl;
    imgObj.onload = function() {
        var IMG_W = imgObj.width;
        var IMG_H = imgObj.height;
        
        imageDragMain(divName, imgUrl, IMG_W, IMG_H);
    };
    
    //画像が読み込めなかった場合の処理
    imgObj.onerror = function() {
        $(divName).remove();
    };
};

function imageDragMain(divName, imgUrl, IMG_W, IMG_H) {
    //要素のサイズを取得
    var DIV_W = $(divName).width();
    var DIV_H = $(divName).height();
    
    //画像の初期位置
    var x = 0;
    var y = 0;
    if ((IMG_W - DIV_W) /2 > 0) x = (IMG_W - DIV_W) /2;
    
    $(divName).css({
        backgroundImage: 'url(' + imgUrl + ')',
        backgroundRepeat: 'no-repeat',
        backgroundPosition: 'top'
        
    //画像がクリックされた時にイベントを開始
    }).mousedown(function(e) {
        //クリック時の座標を取得
        var bx = e.pageX;
        var by = e.pageY;
        
        $(document).on('mousemove.move', function(e) {
            x += bx - e.pageX;
            bx = e.pageX;
            if (x < 0) x = 0;
            if (x > IMG_W - DIV_W) x = IMG_W - DIV_W;
            y += by - e.pageY;
            by = e.pageY;
            if (y < 0) y = 0;
            if (y > IMG_H - DIV_H) y = IMG_H - DIV_H;
            $(divName).css('background-position', '-' + x + 'px -' + y + 'px');
            
            return false;
            
        //カーソルが離された時にイベントを削除
        }).one('mouseup', function() {
            $(document).off('mousemove.move');
        });
        
        return false;
    });
};