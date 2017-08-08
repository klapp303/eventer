<?php

App::uses('AppModel', 'Model');

class EventStatus extends AppModel
{
    public $useTable = 'event_status';
    
//    public $actsAs = array('SoftDelete'); //関連テーブルのデータを取得されるので物理削除する
    
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
    
    public function checkEventsDetailStatus($id = null, $mode = null, $user_id = false, $status = false)
    {
        if ($mode == 'ARRAY') {
            $status = array();
        }
        if (!$id) {
            return $status;
        }
        
        $user_id = AuthComponent::user(['id']);
        $eventStatus = $this->find('first', array(
            'conditions' => array(
                'EventStatus.events_detail_id' => $id,
                'EventStatus.user_id' => $user_id
            ),
            'order' => array('EventStatus.created' => 'desc')
        ));
        if ($eventStatus) {
            if ($mode == 'ARRAY') {
                $status = $eventStatus;
            } else {
                $status = $eventStatus['EventStatus']['status'];
            }
        }
        
        return $status;
    }
}
