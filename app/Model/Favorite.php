<?php

App::uses('AppModel', 'Model');

class Favorite extends AppModel
{
    public $useTable = 'favorites';
    
//    public $actsAs = array('SoftDelete'); //関連テーブルのデータを取得されるので物理削除する
    
    public $belongsTo = array(
//        'UserProfile' => array(
//            'className' => 'User', //関連付けるModel
//            'foreignKey' => 'user_id', //関連付けるためのfield、関連付け先は上記Modelのid
//            'fields' => 'handlename' //関連付け先Modelの使用field
//        ),
        'Artist' => array(
            'className' => 'Artist', //関連付けるModel
            'foreignKey' => 'artist_id', //関連付けるためのfield、関連付け先は上記Modelのid
//            'fields' => 'title' //関連付け先Modelの使用field
        )
    );
    
//    public $validate = array(
//        'title' => array(
//            'rule' => 'notBlank',
//            'required' => 'create'
//        ),
//        'amount' => array(
//            'rule' => 'numeric',
//            'required' => false,
//            'allowEmpty' => true,
//            'message' => '値段を正しく入力してください。'
//        )
//    );
    
//    public $filtetArgs = array(
//        'id' => array('type' => 'value'),
//        'title' => array('type' => 'value')
//    );
}
