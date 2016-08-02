<?php

App::uses('AppModel', 'Model');

class EventUser extends AppModel
{
    public $useTable = 'event_users';
    
    public $actsAs = array(/* 'SoftDelete' */); //関連テーブルのデータを取得されるので物理削除する
    
    public $belongsTo = array(
        'UserProfile' => array(
            'className' => 'User', //関連付けるModel
            'foreignKey' => 'user_id', //関連付けるためのfield、関連付け先は上記Modelのid
            'fields' => 'handlename' //関連付け先Modelの使用field
        ),
        'Event' => array(
            'className' => 'Event', //関連付けるModel
            'foreignKey' => 'event_id', //関連付けるためのfield、関連付け先は上記Modelのid
//            'fields' => 'title' //関連付け先Modelの使用field
        ),
        'EventsDetail' => array(
            'className' => 'EventsDetail', //関連付けるModel
            'foreignKey' => 'events_detail_id', //関連付けるためのfield、関連付け先は上記Modelのid
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
    
    public function getJoinEvents($user_id = false, $data = ['id' => [], 'list' => []])
    {
        //参加済のデータからevent_idを取得
        $event_lists = $this->find('list', array(
            'conditions' => array('EventUser.user_id' => $user_id),
            'fields' => 'EventUser.event_id'
        ));
        //参加済のeventsデータと紐付くevents_detailsデータを取得
        $this->loadModel('EventsDetail');
        $events_detail_lists = $this->EventsDetail->find('all', array(
            'conditions' => array(
                'EventsDetail.event_id' => $event_lists
            ),
            'order' => array('EventsDetail.date' => 'asc', 'EventsDetail.time_start' => 'asc')
        ));
        //参加済の場合はflgを立てる
        foreach ($events_detail_lists as &$event) {
            if ($this->find('first', array(
                'conditions' => array(
                    'EventUser.events_detail_id' => $event['EventsDetail']['id']
                )
            ))) {
                $event['EventsDetail']['join_status'] = 1;
            } else {
                $event['EventsDetail']['join_status'] = 0;
            }
        }
        unset($event);
        $data['list'] = $events_detail_lists;
        
        //conditions用にevents_detail_idのリストを取得
        $events_detail_id = $this->EventsDetail->find('list', array(
            'conditions' => array(
                'EventsDetail.event_id' => $event_lists
            ),
            'order' => array('EventsDetail.date' => 'asc', 'EventsDetail.time_start' => 'asc'),
            'fields' => 'EventsDetail.id'
        ));
        $data['id'] = $events_detail_id;
        
        return $data;
    }
}
