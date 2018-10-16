<?php

App::uses('AppModel', 'Model');

class EntryGenre extends AppModel
{
    public $useTable = 'entry_genres';
    
    public $actsAs = array(/*'SoftDelete'*/);
    
    public $belongsTo = array(
        'EntryCost' => array(
            'className' => 'EntryCost', //関連付けるModel
            'foreignKey' => 'entry_cost_id', //関連付けるためのfield、関連付け先は上記Modelのid
            'fields' => array('title', 'level') //関連付け先Modelの使用field
        ),
        'EntryRule' => array(
            'className' => 'EntryRule', //関連付けるModel
            'foreignKey' => 'entry_rule_id', //関連付けるためのfield、関連付け先は上記Modelのid
            'fields' => array('title', 'level') //関連付け先Modelの使用field
        ),
        'EntrySystem' => array(
            'className' => 'EntrySystem', //関連付けるModel
            'foreignKey' => 'entry_system_id', //関連付けるためのfield、関連付け先は上記Modelのid
            'fields' => array('title', 'url') //関連付け先Modelの使用field
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
}
