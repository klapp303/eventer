jQuery(function($) {
    //グローバル変数
    var syncerTimeout = null ;
    
    //一連の処理
    $(function() {
        //スクロールイベントの設定
        $(window).scroll(function() {
            //1秒ごとに処理
            if (syncerTimeout == null) {
                //セットタイムアウトを設定
                syncerTimeout = setTimeout(function() {
                    
                    //対象のエレメント
                    var element = $('#move_top');
                    
                    //現在、表示されているか？
                    var visible = element.is(':visible');
                    
                    //最上部から現在位置までの距離を取得して、変数[now]に格納
                    var now = $(window).scrollTop();
                    
                    //最下部から現在位置までの距離を計算して、変数[under]に格納
                    var under = $('body').height() - (now + $(window).height());
                    
                    //最上部から現在位置までの距離(now)が400px以上かつ
                    //最下部から現在位置までの距離(under)が0px以上かつ…
                    if (now > 200 && 0 <= under) {
                        //非表示状態だったら
                        if (!visible) {
                            //[#page-top]をゆっくりフェードインする
                            element.fadeIn('slow');
                        }
                        
                    //400px以下かつ
                    //表示状態だったら
                    } else if (visible) {
                        //[#page-top]をゆっくりフェードアウトする
                        element.fadeOut('slow');
                    }
                    
                    //フラグを削除
                    syncerTimeout = null;
                }, 1000);
            }
        });
        
        //クリックイベントを設定する
        $('.move_top_button').click(function() {
			//スムーズにスクロールする
			$('html, body').animate({scrollTop:0}, 'slow');
		});
    });
});