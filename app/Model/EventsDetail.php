<?php

App::uses('AppModel', 'Model');

class EventsDetail extends AppModel
{
    public $useTable = 'events_details';
    
    public $actsAs = array('SoftDelete', 'Containable'/* , 'Search.Searchable' */);
    
    public $belongsTo = array(
        'Event' => array(
            'className' => 'Event', //関連付けるModel
            'foreignKey' => 'event_id', //関連付けるためのfield、関連付け先は上記Modelのid
//            'fields' => '' //関連付け先Modelの使用field
        ),
        'User' => array(
            'className' => 'User', //関連付けるModel
            'foreignKey' => 'user_id', //関連付けるためのfield、関連付け先は上記Modelのid
            'fields' => array('username', 'handlename') //関連付け先Modelの使用field
        ),
        'EventGenre' => array(
            'className' => 'EventGenre', //関連付けるModel
            'foreignKey' => 'genre_id', //関連付けるためのfield、関連付け先は上記Modelのid
            'fields' => 'title' //関連付け先Modelの使用field
        ),
        'Place' => array(
            'className' => 'Place', //関連付けるModel
            'foreignKey' => 'place_id', //関連付けるためのfield、関連付け先は上記Modelのid
            'fields' => array('name', 'access', 'prefecture_id') //関連付け先Modelの使用field
        )
    );
    
    public $hasMany = array(
        'EventsEntry' => array(
            'className' => 'EventsEntry', //関連付けるModel
            'foreignKey' => 'events_detail_id' //関連付けるfield
        )
    );
    
    public $validate = array(
        'title' => array(
            'rule' => 'notBlank',
            'required' => 'create'
        )
    );
    
//    public $filtetArgs = array(
//        'id' => array('type' => 'value'),
//        'title' => array('type' => 'value')
//    );
    
    public function afterFind($results, $primary = false)
    {
        //place_idからplaceデータを取得できない場合（placeデータが削除されている）
        $this->loadModel('Place');
        $place_default_data = $this->Place->find('first', array(
            'conditions' => array(
                'Place.id' => 5
            )
        ));
        if (@$results['Place'] && @!$results['Place']['name']) {
            $results['Place'] = $place_default_data['Place'];
            $results['Place']['Prefecture'] = $place_default_data['Prefecture'];
        }
        foreach ($results as $key => $result) {
            if (@$result['Place'] && @!$result['Place']['name']) {
                $results[$key]['Place'] = $place_default_data['Place'];
                $results[$key]['Place']['Prefecture'] = $place_default_data['Prefecture'];
            }
        }
        
        return $results;
    }
    
    public function getUnfixedPayment($user_id = false, $status = 0, $limit = 20, $data = ['list' => [], 'count' => 0])
    {
        $event_lists = $this->find('all', array(
            'conditions' => array(
//                'EventsDetail.user_id' => $user_id,
                'EventsDetail.deleted !=' => 1
            ),
            'order' => array('EventsDetail.date' => ($status == 0)? 'asc' : 'desc', 'EventsDetail.time_start' => 'asc')
        ));
        
        //参加済のイベントで未払いの一覧を取得しておく
        $this->loadModel('EventUser');
        $join_lists = $this->EventUser->find('list', array(
            'conditions' => array(
                'EventUser.user_id' => $user_id,
                'EventUser.payment_status' => $status
            ),
            'fields' => array('EventUser.events_entry_id')
        ));
        
        $this->loadModel('EventsEntry');
        foreach ($event_lists as $key => $event) {
            $entry_lists = $this->EventsEntry->find('all', array(
                'conditions' => array(
                    'or' => array(
                        //参加済のイベントで未払いの場合
                        array(
                            'EventsEntry.events_detail_id' => $event['EventsDetail']['id'],
                            'EventsEntry.id' => $join_lists,
                            'EventsEntry.price >' => 0,
                            'EventsEntry.status' => 2
                        ),
                        array(
                            'EventsEntry.events_detail_id' => $event['EventsDetail']['id'],
                            'EventsEntry.user_id' => $user_id,
                            'EventsEntry.price >' => 0,
                            array(
                                'or' => array(
                                    'EventsEntry.payment !=' => 'credit',
                                    'EventsEntry.payment' => null
                                )
                            ),
                            'EventsEntry.payment_status' => $status,
                            'EventsEntry.status' => 2,
                            array(
                                'or' => array(
                                    'EventsDetail.date >=' => date('Y-m-d'),
                                    'EventsEntry.payment' => 'buy'
                                ),
                            )
                        )
                    )
                )
            ));
            //未払いのエントリーがなければリストから削除
            if (!$entry_lists) {
                unset($event_lists[$key]);
                //未払いのエントリーがあれば未払いのみリストに残す
            } else {
                unset($event_lists[$key]['EventsEntry']);
                foreach ($entry_lists as $entry) {
//                    //参加済のイベントで未払いの場合はflgを立てる
//                    if ($entry['EventsEntry']['user_id'] != $user_id) {
//                        
//                    }
                    
                    $event_lists[$key]['EventsEntry'][] = $entry['EventsEntry'];
                }
                $data['count'] += count($entry_lists);
            }
        }
        
        //keyを振り直して整形
        $event_lists = array_merge($event_lists);
        //paginatorを使用しないので表示できる件数を現実的な値にしておく
        if ($data['count'] > $limit) {
            array_splice($event_lists, $limit);
        }
        $data['list'] = $event_lists;
        
        return $data;
    }
    
    public function getUnfixedSales($user_id = false, $status = 0, $limit = 20, $data = ['list' => [], 'count' => 0])
    {
        $event_lists = $this->find('all', array(
            'conditions' => array(
//                'EventsDetail.user_id' => $user_id,
                'EventsDetail.date >=' => date('Y-m-d'),
                'EventsDetail.deleted !=' => 1
            ),
            'recursive' => 2,
            'order' => array('EventsDetail.date' => ($status == 0)? 'asc' : 'desc', 'EventsDetail.time_start' => 'asc')
        ));
        
        $this->loadModel('EventsEntry');
        foreach ($event_lists as $key => &$event) {
            $entry_lists = $this->EventsEntry->find('all', array(
                'conditions' => array(
                    'EventsEntry.events_detail_id' => $event['EventsDetail']['id'],
                    'EventsEntry.user_id' => $user_id,
                    'EventsEntry.sales_status' => $status,
                    'EventsEntry.status' => 2
                )
            ));
            //イベント毎の当選枚数を計算
            $event['number'] = 0;
            foreach ($entry_lists as $entry) {
                $event['number'] += $entry['EventsEntry']['number'];
            }
            //当選枚数が0枚ならばリストから削除
            if ($event['number'] == 0) {
                unset($event_lists[$key]);
                //当選枚数が1枚ならば当選したエントリーを残す
            } elseif ($event['number'] == 1) {
                unset($event_lists[$key]['EventsEntry']);
                $event_lists[$key]['EventsEntry'][] = $entry_lists[0]['EventsEntry'];
                //当選枚数が2枚以上ならば当選したエントリーのみ残してcountに加算
            } else {
                unset($event_lists[$key]['EventsEntry']);
                foreach ($entry_lists as $entry) {
                    $event_lists[$key]['EventsEntry'][] = $entry['EventsEntry'];
                }
                $data['count'] += count($entry_lists);
            }
        }
        unset($event);
        
        //当選枚数が1枚のイベントの日時が被っていないかを判定
        foreach ($event_lists as $key => $event) {
            if ($event['number'] == 1) {
                foreach ($event_lists as $other_key => $other_event) {
                    if ($key == $other_key) {
                        continue; //自身のイベントとは比較しない
                    }
                    
                    //日時が被っているイベントがある場合
                    if ($this->checkConflictEventTime($event, $other_event) == true) {
                        $data['count'] ++;
                        continue 2;
                    }
                }
                //日時が被っているイベントがない場合
                unset($event_lists[$key]);
            }
        }
        
        //keyを振り直して整形
        $event_lists = array_merge($event_lists);
        //paginatorを使用しないので表示できる件数を現実的な値にしておく
        if ($data['count'] > $limit) {
            array_splice($event_lists, $limit);
        }
        $data['list'] = $event_lists;
        
        return $data;
    }
    
    public function getUnfixedCollect($user_id = false, $status = 0, $limit = 20, $data = ['list' => [], 'count' => 0])
    {
        $event_lists = $this->find('all', array(
            'conditions' => array(
//                'EventsDetail.user_id' => $user_id,
                'EventsDetail.deleted !=' => 1
            ),
            'order' => array('EventsDetail.date' => ($status == 0)? 'asc' : 'desc', 'EventsDetail.time_start' => 'asc')
        ));
        
        $this->loadModel('EventsEntry');
        foreach ($event_lists as $key => $event) {
            $entry_lists = $this->EventsEntry->find('all', array(
                'conditions' => array(
                    'EventsEntry.events_detail_id' => $event['EventsDetail']['id'],
                    'EventsEntry.user_id' => $user_id,
                    'EventsEntry.sales_status' => 1,
                    'EventsEntry.collect_status' => $status,
                    'EventsEntry.status' => 2
                )
            ));
            //未回収のエントリーがなければリストから削除
            if (!$entry_lists) {
                unset($event_lists[$key]);
                //未回収のエントリーがあれば未回収のみリストに残す
            } else {
                unset($event_lists[$key]['EventsEntry']);
                foreach ($entry_lists as $entry) {
                    $event_lists[$key]['EventsEntry'][] = $entry['EventsEntry'];
                }
                $data['count'] += count($entry_lists);
            }
        }
        
        //keyを振り直して整形
        $event_lists = array_merge($event_lists);
        //paginatorを使用しないので表示できる件数を現実的な値にしておく
        if ($data['count'] > $limit) {
            array_splice($event_lists, $limit);
        }
        $data['list'] = $event_lists;
        
        return $data;
    }
    
    public function checkConflictEventTime($event = false, $other_event = false)
    {
        //イベントデータを定義しておく
        //開演時刻
        $a_start = date('H:i', strtotime($event['EventsDetail']['time_start']));
        $b_start = date('H:i', strtotime($other_event['EventsDetail']['time_start']));
        //公演時間
        if ($event['EventsDetail']['genre_id'] == 1 || $event['EventsDetail']['genre_id'] == 6) {
            $a_time = 180; //ライブ、その他は180分
        } elseif ($event['EventsDetail']['genre_id'] == 4 || $event['EventsDetail']['genre_id'] == 5) {
            $a_time = 30; //見本市、即売会は30分（融通がきくので）
        } else {
            $a_time = 90; //リリイベ、トーク、それ以外は90分
        }
        if ($other_event['EventsDetail']['genre_id'] == 1 || $other_event['EventsDetail']['genre_id'] == 6) {
            $b_time = 180; //ライブ、その他は180分
        } elseif ($other_event['EventsDetail']['genre_id'] == 4 || $other_event['EventsDetail']['genre_id'] == 5) {
            $b_time = 30; //見本市、即売会は30分（融通がきくので）
        } else {
            $b_time = 90; //リリイベ、トーク、それ以外は90分
        }
        //終演時刻
        $a_end = date('H:i', strtotime($a_start . ' +' . $a_time . ' minute'));
        $b_end = date('H:i', strtotime($b_start . ' +' . $b_time . ' minute'));
        //会場の地域
        $a_place = $event['Place']['Prefecture']['state'];
        $b_place = $other_event['Place']['Prefecture']['state'];
        
        //開催日が違う場合は被りなし
        if ($event['EventsDetail']['date'] != $other_event['EventsDetail']['date']) {
            return false;
        }
        
        //地域が違う場合は被りあり
        if ($a_place != $b_place) {
            return true;
        }
        
        //開催日と地域が同じ場合は開演時刻と終演時刻によって判定
        if ($a_end < $b_start || $b_end < $a_start) {
            return false;
            
        } else {
            return true;
        }
    }
}
