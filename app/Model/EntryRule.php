<?php

App::uses('AppModel', 'Model');

class EntryRule extends AppModel
{
    public $useTable = 'entry_rules';
    
//    public $actsAs = array('SoftDelete');
    
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
