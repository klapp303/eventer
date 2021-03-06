<?php

App::uses('AppModel', 'Model');

class Page extends AppModel
{
    public $useTable = false;
    
//    public $actsAs = array('SoftDelete');
    
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
    
    public function getArrayHistory()
    {
        //お知らせ、更新履歴を定義しておく
        $data = [
            0 => [
                'date' => '2015-10-17',
                'title' => 'イベ幸ver1.0リリース',
                'sub' => []
            ],
            1 => [
                'date' => '2016-04-08',
                'title' => 'イベ幸ver2.0リリース',
                'sub' => [
                    '別日程のイベントをまとめて管理できる（ツアーや1部2部など）',
                    '複数のエントリーをまとめて管理できる（FC先行と一般など）',
                    '会場データを大幅に追加',
                    '参加者機能から収支管理機能へ変更（参加者に関係なく管理が可能）'
                ]
            ],
            2 => [
                'date' => '2016-04-21',
                'title' => 'ver2.1にアップデート',
                'sub' => [
                    'お知らせメール機能を追加'
                ]
            ],
            3 => [
                'date' => '2016-06-01',
                'title' => 'ver2.2にアップデート',
                'sub' => [
                    '当選したイベントが被る場合にチケット余りに反映'
                ]
            ],
            4 => [
                'date' => '2016-08-02',
                'title' => 'ver2.3にアップデート',
                'sub' => [
                    '会場データに都道府県を追加',
                    '大阪府の会場データを追加'
                ]
            ],
            5 => [
                'date' => '2016-10-04',
                'title' => 'ver2.4にアップデート',
                'sub' => [
                    '出演者タグ機能を追加'
                ]
            ],
            6 => [
                'date' => '2016-10-06',
                'title' => 'イベ幸ver3.0リリース',
                'sub' => [
                    'イベントの作成者に関わらずエントリーの登録ができる',
                    'ゲスト用アカウントを追加',
                    '一般に公開する'
                ]
            ],
            7 => [
                'date' => '2017-02-18',
                'title' => 'ver3.1にアップデート',
                'sub' => [
                    '会場データに座席図を追加'
                ]
            ],
            8 => [
                'date' => '2017-05-08',
                'title' => 'ver3.2にアップデート',
                'sub' => [
                    'イベント自体の見送りstatusを追加'
                ]
            ],
            9 => [
                'date' => '2017-06-28',
                'title' => 'ver3.3にアップデート',
                'sub' => [
                    '出演者タグ機能をアーティスト一覧に変更',
                    'アーティストに紐付くイベント参加データを追加'
                ]
            ],
            10 => [
                'date' => '2017-07-14',
                'title' => 'ver3.4にアップデート',
                'sub' => [
                    'イベント参加データ一覧のページを追加',
                    'イベント参加データに直近頻度を追加'
                ]
            ],
            11 => [
                'date' => '2017-08-16',
                'title' => 'ver3.5にアップデート',
                'sub' => [
                    'セットリスト情報を追加'
                ]
            ],
            12 => [
                'date' => '2017-09-13',
                'title' => 'ver3.6にアップデート',
                'sub' => [
                    'イベント参加データ分析のページを追加'
                ]
            ]
        ];
        
        return $data;
    }
    
    public function getArrayFaq()
    {
        //よくある質問を定義しておく
        /* カテゴリーの定義ここから */
        $array_category = [
            1 => 'イベント管理',
            2 => '収支管理',
            3 => 'アーティスト',
            4 => '会場',
            5 => 'お知らせメール'
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
                . '非公開にすればイベントを登録した自分自身、エントリーを登録したユーザしか閲覧できません。',
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
                . 'またイベントの当選チケットが1枚の場合でも、他の当選したイベントと日程が被ると思われる場合やイベント自体を見送った場合には表示されます。<br><br>'
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
                'category_id' => 4
            ],
            9 => [
                'date' => '2016-06-15',
                'question' => '会場の最寄り駅って？',
                'answer' => '会場の最寄り駅が1つ（主観で選んだものが）登録されています。<br>'
                . '主にイベント会場の最寄り駅までの経路の確認に使用します。',
                'category_id' => 4
            ],
            10 => [
                'date' => '2016-06-15',
                'question' => '会場の登録で緯度と経度が分からないんだけど？',
                'answer' => '会場のGoogleMapに使用するデータです。<br>'
                . 'GoogleMapで会場を検索すればURLの一部に緯度と経度が表示されますが、任意項目なので分からなければ空欄のまま登録できます。<br>'
                . 'ちなみに例示されている緯度と経度は東京駅のものになります。',
                'category_id' => 4
            ],
//            11 => [
//                'date' => '2016-06-15',
//                'question' => '会場の並び替えって？',
//                'answer' => 'イベント登録時の会場の選択肢や会場一覧の順番をソートできます。<br>'
//                . '順番はユーザ毎の設定ではなく全ユーザに適用されます。<br>'
//                . '気が向けばそのうちログインしたユーザ毎に対応する…かも？',
//                'category_id' => 4
//            ],
            12 => [
                'date' => '2016-06-15',
                'question' => 'お知らせメールが来ないよ？',
                'answer' => '直近の予定がない場合、ユーザ設定でお知らせメールを受信するに設定していない場合は配信されません。<br>'
                . 'ユーザ設定はページ右上のHNより変更できます。',
                'category_id' => 5
            ],
            13 => [
                'date' => '2016-06-15',
                'question' => 'お知らせメールの内容と本日の予定で送ったメールの内容とが違う？',
                'answer' => 'お知らせメールは今日を含む3日間の予定を配信しております。<br>'
                . 'そのため、本日の予定より多くなる場合があります。',
                'category_id' => 5
            ],
            14 => [
                'date' => '2016-06-15',
                'question' => '見送ったイベントの申込締切や当落発表がお知らせメールで来るよ？',
                'answer' => '他のイベントとの兼ね合いや予定変更などを考慮し、見送ったイベントについても基本的に日程のお知らせを配信しています。<br>'
                . '既に申込中のエントリーの申込締切、クレジットカード決済のエントリーの入金締切については配信しておりません。',
                'category_id' => 5
            ],
            15 => [
                'date' => '2016-06-15',
                'question' => '経路を確認するで日付指定、時刻指定が正しく表示されない？',
                'answer' => '開場開演時刻が登録されていない場合、12:00到着予定で検索されます。<br>'
                . '登録がある場合、開場時刻に到着予定→開演時刻に到着予定の優先順位で検索されます。<br>'
                . 'また遷移先のサービスの仕様上、3ヶ月以上先の日付指定はできません。',
                'category_id' => 99
            ],
//            16 => [
//                'date' => '2016-06-15',
//                'question' => 'and more...ってあるけど？',
//                'answer' => '参加者機能とかそのうちできたらいいね！という意気込みの表れです。実装されると決まったわけではありません。',
//                'category_id' => 99
//            ],
            17 => [
                'date' => '2016-06-15',
                'question' => 'スマホだと見にくいんだけど？',
                'answer' => '',
                'category_id' => 100
            ],
//            18 => [
//                'date' => '2016-06-15',
//                'question' => 'ツアーなどで1つのイベントに5つ以上公演があって登録しきれないよ？',
//                'answer' => '1つのイベントに対して最大4公演までの登録は仕様です。<br>'
//                . '登録フォームを追加するボタンを設置してもいいのですが面倒です…気が向いたら、ね。',
//                'category_id' => 1
//            ],
            19 => [
                'date' => '2016-10-06',
                'question' => '○○のアーティスト（出演者）が登録されていないよ？',
                'answer' => 'アーティスト（出演者）は任意に追加することができます。<br>'
                . '管理人はソロイベントやソロデビューした場合に制限をかけ、個人名義の乱立を抑制しています。',
                'category_id' => 3
            ],
            20 => [
                'date' => '2017-05-08',
                'question' => 'イベントの見送りって？',
                'answer' => 'エントリーの登録に関わらず、イベントを見送り扱いにします。',
                'category_id' => 1
            ],
            21 => [
                'date' => '2017-07-14',
                'question' => 'イベント管理データの直近頻度って？',
                'answer' => 'アーティスト毎の直近に参加した10イベントから算出した平均参加頻度です。<br>'
                . '参加数が10に満たないものは、頻度と同じ数値になります。',
                'category_id' => 3
            ],
//            22 => [
//                'date' => '2017-07-14',
//                'question' => '',
//                'answer' => '',
//                'category_id' => 99
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
