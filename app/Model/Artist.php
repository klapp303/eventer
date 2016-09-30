<?php

App::uses('AppModel', 'Model');

class Artist extends AppModel
{
    public $useTable = 'artists';
    
    public $actsAs = array('SoftDelete'/* , 'Search.Searchable' */);
    
//    public $belongsTo = array(
//        'Profile' => array(
//            'className' => 'Profile', //関連付けるModel
//            'foreignKey' => 'user_id', //関連付けるためのfield、関連付け先は上記Modelのid
//            'fields' => array('mailmaga', 'station') //関連付け先Modelの使用field
//        )
//    );
    
    public $validate = array(
        'name' => array(
            'rule_1' => array(
                'rule' => 'notBlank',
                'required' => 'create'
            ),
//            'rule_2' => array(
//                'rule' => array('maxLength', 25),
//                'message' => '名前は25文字以内です'
//            )
        ),
    );
    
//    public $filtetArgs = array(
//        'id' => array('type' => 'value'),
//        'title' => array('type' => 'value')
//    );
}
