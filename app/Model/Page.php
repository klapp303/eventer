<?php

App::uses('AppModel', 'Model');

class Page extends AppModel
{
    public $useTable = false;
    
//    public $actsAs = array('SoftDelete'/* , 'Search.Searchable' */);
    
//    public $belongsTo = array(
//        'SamplesGenre' => array(
//            'className' => 'SamplesGenre', //関連付けるModel
//            'foreignKey' => 'genre_id', //関連付けるためのfield、関連付け先は上記Modelのid
//            'fields' => 'title' //関連付け先Modelの使用field
//        )
//    );
    
//    public $validate = array(
//        'title' => array(
//            'rule' => 'notBlank',
//            'required' => 'create'
//        ),
//        'amount' => array(
//            'rule' => 'numeric',
//            'required' => false,
//            'allowEmpty' => true,
//            'message' => '金額を正しく入力してください。'
//        )
//    );
    
//    public $filtetArgs = array(
//        'id' => array('type' => 'value'),
//        'title' => array('type' => 'value')
//    );
    
    public function getArrayHistory($data = false) {
        //お知らせ、更新履歴を定義しておく
        $data = [
            0 => [
                'date' => '2015-10-17',
                'title' => 'イベ幸ver1.0リリース！',
                'sub' => []
            ],
            1 => [
                'date' => '2016-04-08',
                'title' => 'イベ幸ver2.0リリース！',
                'sub' => [
                    '別日程のイベントをまとめて管理できる（ツアーや1部2部など）',
                    '複数のエントリーをまとめて管理できる（FC先行と一般など）',
                    '会場データを大幅に追加',
                    '参加者機能から収支管理機能へ変更（参加者に関係なく管理が可能）'
                ]
            ],
            2 => [
                'date' => '2016-04-21',
                'title' => 'ver2.1にアップデート！',
                'sub' => [
                    'お知らせメール機能を追加'
                ]
            ],
            3 => [
                'date' => '2016-06-01',
                'title' => 'ver2.2にアップデート！',
                'sub' => [
                    '当選したイベントが被る場合にチケット余りに反映'
                ]
            ]
        ];
        
        return $data;
    }
    
    public function getArrayFaq($data = false) {
        //よくある質問を定義しておく
        /* カテゴリーの定義ここから */
        $array_category = [
            1 => 'イベント管理',
            2 => '収支管理',
            3 => '会場',
            4 => 'お知らせメール'
        ];
        /* カテゴリーの定義ここまで */
        
        /* オプション値の取得ここから */
        $this->loadModel('Option');
        $CURRENT_EVENT_OPTION = $this->Option->find('first', array(//オプション値を取得
            'conditions' => array('Option.title' => 'CURRENT_EVENT_KEY'),
            'fields' => 'Option.key'
        ));
        $CURRENT_EVENT_KEY = $CURRENT_EVENT_OPTION['Option']['key'];
        /* オプション値の取得ここまで */
        
        $data = [
            0 => [
                'date' => '2016-06-15',
                'question' => 'TOPページにある直近の予定って？',
                'answer' => '翌日から' . $CURRENT_EVENT_KEY. '日後までのイベントが表示されます。<br>'
                . '落選、見送りのイベントは表示されません。',
                'category_id' => 1
            ],
            1 => [
                'date' => '2016-06-15',
                'question' => 'イベントの日付がグレー表示になってるけど？',
                'answer' => '日付が過ぎたものはグレー表示されます。',
                'category_id' => 1
            ],
            2 => [
                'date' => '2016-06-15',
                'question' => 'イベントのステータス（状態）がいつの間にか変わってるよ？',
                'answer' => 'エントリーの登録が1つもない場合、過去のイベントは見送り、未来のイベントは検討中と表示されます。',
                'category_id' => 1
            ],
            3 => [
                'date' => '2016-06-15',
                'question' => 'イベントの公開設定って？',
                'answer' => '自分以外のユーザがイベント予定を見られるかどうかの設定です。<br>'
                . '全体に公開すればログインできる全てのユーザが閲覧する事ができます。<br>'
                . '非公開にすればイベントを登録した自分自身しか閲覧できません。',
                'category_id' => 1
            ],
            4 => [
                'date' => '2016-06-15',
                'question' => 'エントリーの支払方法って？',
                'answer' => '支払いが未対応かどうかのチェック方法が異なります。<br>'
                . '特にクレジットカード決済の場合は支払いは対応済みとして扱われます。<br>'
                . '（未対応の支払い一覧には表示されない）',
                'category_id' => 2
            ],
            5 => [
                'date' => '2016-06-15',
                'question' => '未対応の支払いって？',
                'answer' => '当選したが支払いがまだ行われていないと思われるエントリー一覧です。<br>'
                . '自動引き落としのクレジットカード決済や金額が0円のもの、イベント自体が終了したものは表示されません。<br>'
                . 'また終了したイベントでも支払方法が買取の場合は表示されます。<br><br>'
                . '確定ボタンを押す事で一覧から表示されなくなるので、支払い忘れや漏れをなくせます。',
                'category_id' => 2
            ],
            6 => [
                'date' => '2016-06-15',
                'question' => '未対応のチケット余りって？',
                'answer' => '複数枚の当選があるなど余りのチケットがあると思われるエントリー一覧です。<br>'
                . 'エントリー単位の表示ですが、基本的にはイベント毎に合計して複数枚ないか調べています。<br>'
                . 'またイベントの当選チケットが1枚の場合でも、他の当選したイベントと日程が被ると思われる場合には表示されます。<br><br>'
                . '確定ボタンを押す事で一覧から表示されなくなるので、チケットの余りを忘れる事なく管理できます。',
                'category_id' => 2
            ],
            7 => [
                'date' => '2016-06-15',
                'question' => '未対応の集金って？',
                'answer' => '余りのチケットで引取先が決まったと思われるエントリー一覧です。<br>'
                . '基本的にチケット余りで確定ボタンを押したものが表示されます。<br><br>'
                . '余ったチケットを友人知人に譲ったはいいけど代金は貰ったっけ？という曖昧さをなくせます。',
                'category_id' => 2
            ],
            8 => [
                'date' => '2016-06-15',
                'question' => '会場の収容人数って？',
                'answer' => '公式ページなどで公開されている最大収容人数です<br>'
                . '実際は使用されない座席、機材スペースなどがあるのでもっと少ない場合が多いです。',
                'category_id' => 3
            ],
            9 => [
                'date' => '2016-06-15',
                'question' => '会場の最寄り駅って？',
                'answer' => '会場の最寄り駅が1つ（主観で選んだものが）登録されています。<br>'
                . '主にイベント会場の最寄り駅までの経路の確認に使用します。',
                'category_id' => 3
            ],
            10 => [
                'date' => '2016-06-15',
                'question' => '会場の登録で緯度と経度が分からないんだけど？',
                'answer' => '会場のGoogleMapに使用するデータです。<br>'
                . 'GoogleMapで会場を検索すればURLの一部に緯度と経度が表示されますが、任意項目なので分からなければ空欄のまま登録できます。<br>'
                . 'ちなみに例示されている緯度と経度は東京駅のものになります。',
                'category_id' => 3
            ],
            11 => [
                'date' => '2016-06-15',
                'question' => '会場の並び替えって？',
                'answer' => 'イベント登録時の会場の選択肢や会場一覧の順番をソートできます。<br>'
                . '順番はユーザ毎の設定ではなく全ユーザに適用されます。<br>'
                . '気が向けばそのうちログインしたユーザ毎に対応する…かも？',
                'category_id' => 3
            ],
            12 => [
                'date' => '2016-06-15',
                'question' => 'お知らせメールが来ないよ？',
                'answer' => '直近の予定がない場合、ユーザ設定でお知らせメールを受信するに設定していない場合は配信されません。<br>'
                . 'ユーザ設定はページ右上のHNより変更できます。',
                'category_id' => 4
            ],
            13 => [
                'date' => '2016-06-15',
                'question' => 'お知らせメールの内容と本日の予定で送ったメールの内容とが違う？',
                'answer' => 'お知らせメールは今日を含む3日間の予定を配信しております。<br>'
                . 'そのため、本日の予定より多くなる場合があります。',
                'category_id' => 4
            ],
            14 => [
                'date' => '2016-06-15',
                'question' => '見送ったイベントの申込締切や当落発表がお知らせメールで来るよ？',
                'answer' => '他のイベントとの兼ね合いや予定変更などを考慮し、見送ったイベントについても基本的に日程のお知らせを配信しています。<br>'
                . '既に申込中のエントリーの申込締切、クレジットカード決済のエントリーの入金締切については配信しておりません。',
                'category_id' => 4
            ],
            15 => [
                'date' => '2016-06-15',
                'question' => '経路を確認するで日付指定、時刻指定が正しく表示されない？',
                'answer' => '開場開演時刻が登録されていない場合、12:00到着予定で検索されます。<br>'
                . '登録がある場合、開場時刻に到着予定→開演時刻に到着予定の優先順位で検索されます。<br>'
                . 'また遷移先のサービスの仕様上、3ヶ月以上先の日付指定はできません。',
                'category_id' => 0
            ],
            16 => [
                'date' => '2016-06-15',
                'question' => 'and more...ってあるけど？',
                'answer' => '参加者機能とかそのうちできたらいいね！という意気込みの表れです。実装されると決まったわけではありません。',
                'category_id' => 0
            ],
            17 => [
                'date' => '2016-06-15',
                'question' => 'スマホだと見にくいんだけど？',
                'answer' => '',
                'category_id' => 0
            ],
//            18 => [
//                'date' => '2016-06-15',
//                'question' => 'ツアーなどで1つのイベントに5つ以上公演があって登録しきれないよ？',
//                'answer' => '1つのイベントに対して最大4公演までの登録は仕様です。<br>'
//                . '登録フォームを追加するボタンを設置してもいいのですが面倒です…気が向いたら、ね。',
//                'category_id' => 1
//            ],
//            19 => [
//                'date' => '2016-06-15',
//                'question' => '',
//                'answer' => '',
//                'category_id' => 0
//            ],
        ];
        
        foreach ($data as $key => $val) {
            //カテゴリー名に置換する
            $data[$key]['category'] = 'その他'; //置換前のカテゴリー名を定義
            foreach ($array_category as $category_id => $category) {
                if ($val['category_id'] == $category_id) {
                    $data[$key]['category'] = $category;
                    break;
                }
            }
            
            //questionがない場合
            if (!$val['question']) {
                unset($data[$key]);
                continue;
            }
            
            //answerがない場合
            if (!$val['answer']) {
                $kikoenai = '<img src="/files/kikoenai.jpg">';
                $data[$key]['answer'] = $kikoenai;
            }
        }
        
        return $data;
    }
}
