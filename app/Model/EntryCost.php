<?php

App::uses('AppModel', 'Model');

class EntryCost extends AppModel
{
    public $useTable = 'entry_costs';
    
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
