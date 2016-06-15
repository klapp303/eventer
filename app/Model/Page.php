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
        //よくあるご質問を定義しておく
        $data = [
            0 => [
                'date' => '2016-06-01',
                'question' => 'ダミー質問ダミー質問',
                'answer' => 'ダミー回答<br>ダミー回答',
                'category' => 'イベント管理'
            ],
            1 => [
                'date' => '2016-06-05',
                'question' => 'ダミー質問ダミー質問',
                'answer' => 'ダミー回答<br>ダミー回答',
                'category' => '収支管理'
            ],
            2 => [
                'date' => '2016-06-11',
                'question' => 'ダミー質問ダミー質問',
                'answer' => 'ダミー回答<br>ダミー回答',
                'category' => 'イベント管理'
            ]
        ];
        
        return $data;
    }
}
