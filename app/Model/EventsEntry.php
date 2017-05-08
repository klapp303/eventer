<?php

App::uses('AppModel', 'Model');

class EventsEntry extends AppModel
{
    public $useTable = 'events_entries';
    
    public $actsAs = array('SoftDelete'/* , 'Search.Searchable' */);
    
    public $belongsTo = array(
        'Event' => array(
            'className' => 'Event', //関連付けるModel
            'foreignKey' => 'event_id', //関連付けるためのfield、関連付け先は上記Modelのid
//            'fields' => '' //関連付け先Modelの使用field
        ),
        'EventsDetail' => array(
            'className' => 'EventsDetail', //関連付けるModel
            'foreignKey' => 'events_detail_id', //関連付けるためのfield、関連付け先は上記Modelのid
//            'fields' => '' //関連付け先Modelの使用field
        ),
        'User' => array(
            'className' => 'User', //関連付けるModel
            'foreignKey' => 'user_id', //関連付けるためのfield、関連付け先は上記Modelのid
            'fields' => array('username', 'handlename') //関連付け先Modelの使用field
        ),
        'EntryGenre' => array(
            'className' => 'EntryGenre', //関連付けるModel
            'foreignKey' => 'entries_genre_id', //関連付けるためのfield、関連付け先は上記Modelのid
            'fields' => 'title' //関連付け先Modelの使用field
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
        //エントリーの各日付がどこまで過去のものかを判定
        $entryDateColumn = $this->getDateColumn();
        foreach ($results as $key => &$entry) {
            $status = 0;
            $entry['EventsEntry']['date_closed'] = $status;
            foreach ($entryDateColumn as $column) {
                $status++;
                if (@$entry['EventsEntry'][$column] != null && $entry['EventsEntry'][$column] < date('Y-m-d')) {
                    $entry['EventsEntry']['date_closed'] = $status;
                }
            }
            //イベントが終了している場合
            if (@$entry['EventsEntry']['date_event'] != null && $entry['EventsEntry']['date_event'] < date('Y-m-d')) {
                if ($entry['EventsEntry']['date_closed'] > 0) { //終了したエントリーの日付が登録されている場合のみ
                    $entry['EventsEntry']['date_closed'] = count($entryDateColumn);
                }
            }
        }
        unset($entry);
        
        return $results;
    }
    
    public function getEntryStatus($data = false)
    {
        //エントリーのstatusを定義
        $data = array(
            1 => array(
                'status' => 0,
                'name' => '検討中',
                'class' => 'safe'
            ),
            2 => array(
                'status' => 1,
                'name' => '申込中',
                'class' => 'like'
            ),
            3 => array(
                'status' => 2,
                'name' => '当選',
                'class' => 'true'
            ),
            4 => array(
                'status' => 3,
                'name' => '落選',
                'class' => 'false'
            ),
            5 => array(
                'status' => 4,
                'name' => '見送り',
                'class' => 'false'
            )
        );
        
        return $data;
    }
    
    public function getEventStatus($id = false, $status = -1, $publish = false)
    {
        //イベントのデータを取得しておく
        $this->loadModel('EventsDetail');
        $event_data = $this->EventsDetail->find('first', array('conditions' => array('EventsDetail.id' => $id)));
        
        //データの作成者とログインユーザが一致しない場合
        $user_id = AuthComponent::user(['id']);
        if ($event_data['EventsDetail']['user_id'] != $user_id && $publish == false) {
            return $status; //status = -1 を返す
        }
        
        $entry_lists = $this->find('all', array(
            'conditions' => array(
                'EventsEntry.events_detail_id' => $id
            )
        ));
        foreach ($entry_lists as $key => $entry_list) {
            if ($entry_list['EventsEntry']['status'] == 2) { //当選がある場合
                //当選枚数が1枚で売却している場合
                if ($entry_list['EventsEntry']['number'] == 1 && $entry_list['EventsEntry']['sales_status'] == 1) {
                    if ($status != 1) {
                        $status = -2; //申込中がなければstatusを上書き（下で書き換える）
                    }
                //売却していない当選がある場合
                } else {
                    $status = 2; //当選statusを確定させてループを抜ける
                    break;
                }
            }
            if ($entry_list['EventsEntry']['status'] == 1) { //申込中がある場合
                $status = 1;
            }
            if ($entry_list['EventsEntry']['status'] == 0 && $status != -2 && $status != 1) { //検討中がある場合
                $status = 0;
            }
            if ($entry_list['EventsEntry']['status'] == 3 && $status != -2 && $status != 1 && $status != 0) { //落選がある場合
                $status = 3;
            }
            if ($entry_list['EventsEntry']['status'] == 4 && $status != -2 && $status != 1 && $status != 0 && $status != 3) { //見送りがある場合
                $status = 4;
            }
        }
        
        //当選枚数が1枚で売却している場合はstatusを書き換える
        if ($status == -2) {
            $status = 4;
        }
        
        //エントリーが無い場合
        if (!$entry_lists) {
            if ($event_data['EventsDetail']['date'] < date('Y-m-d')) { //過去のイベントは見送り
                $status = 4;
            } else { //未来のイベントは検討中
                $status = 0;
            }
        }
        
        //イベント自体が見送りの場合
        $this->loadModel('EventStatus');
        $event_detail_status = $this->EventStatus->checkEventsDetailStatus($event_data['EventsDetail']['id']);
        if ($event_detail_status) {
            $status = $event_detail_status;
        }
        
        return $status;
    }
    
    public function getPaymentStatus($data = false)
    {
        //エントリーのstatusを定義
        $data = array(
            1 => array(
                'status' => 'credit',
                'name' => 'クレジットカード',
                'class' => ''
            ),
            2 => array(
                'status' => 'conveni',
                'name' => 'コンビニ支払',
                'class' => ''
            ),
            3 => array(
                'status' => 'delivery',
                'name' => '代金引換',
                'class' => ''
            ),
            4 => array(
                'status' => 'buy',
                'name' => '買取',
                'class' => ''
            ),
            5 => array(
                'status' => 'other',
                'name' => 'その他',
                'class' => ''
            )
        );
        
        return $data;
    }
    
    public function searchEntryDate($user_id = false, $s_date = false, $e_date = false, $join_lists = [])
    {
        //参加済のイベントを取得しておく
        if ($user_id) {
            $this->loadModel('EventUser');
            $join_lists = $this->EventUser->getJoinEntries($user_id);
        }
        
        if ($s_date) {
            $s_date = date('Y-m-d 00:00:00', strtotime($s_date));
        } else {
            $s_date = date('Y-m-d 00:00:00');
        }
        if ($e_date) {
            $e_date = date('Y-m-d 23:59:59', strtotime($e_date));
        } else {
            $e_date = date('Y-m-d 23:59:59');
        }
        
        $entry_lists = $this->find('all', array(
            'conditions' => array(
                'or' => array(
                    //参加済のイベントの場合は開催日時のみ
                    array(
                        'EventsEntry.id' => $join_lists['entry_id'],
                        'or' => array(
                            array(
                                'and' => array(
                                    'EventsEntry.date_event >=' => $s_date,
                                    'EventsEntry.date_event <=' => $e_date
                                )
                            )
                        ),
                    ),
                    array(
                        'EventsEntry.user_id' => $user_id,
                        'or' => array(
                            array(
                                'and' => array(
                                    'EventsEntry.date_start >=' => $s_date,
                                    'EventsEntry.date_start <=' => $e_date
                                )
                            ),
                            array(
                                'and' => array(
                                    'EventsEntry.date_close >=' => $s_date,
                                    'EventsEntry.date_close <=' => $e_date,
                                    'EventsEntry.status' => array(0, 4)
                                )
                            ),
                            array(
                                'and' => array(
                                    'EventsEntry.date_result >=' => $s_date,
                                    'EventsEntry.date_result <=' => $e_date
                                )
                            ),
                            array(
                                'and' => array(
                                    'EventsEntry.date_payment >=' => $s_date,
                                    'EventsEntry.date_payment <=' => $e_date,
                                    'EventsEntry.payment !=' => 'credit'
                                )
                            ),
                            array(
                                'and' => array(
                                    'EventsEntry.date_event >=' => $s_date,
                                    'EventsEntry.date_event <=' => $e_date
                                )
                            )
                        )
                    ),
                ),
                'EventsDetail.deleted !=' => 1
            ),
            'order' => array('EventsDetail.date' => 'asc', 'EventsDetail.time_start' => 'asc')
        ));
        
        return $entry_lists;
    }
    
    public function getDateColumn($sort = null)
    {
        $data = array(
            '申込開始' => 'date_start',
            '申込終了' => 'date_close',
            '当落発表' => 'date_result',
            '入金締切' => 'date_payment'
        );
        
        if ($sort == 'reverse') {
            $data = array_reverse($data);
            
            return $data;
            
        } else {
            return $data;
        }
    }
    
    public function getOnlyEntries($user_id = false, $data = ['list' => [], 'events_detail_id' => []])
    {
        $entry_lists = $this->find('list', array(
            'conditions' => array(
                'EventsEntry.user_id' => $user_id
            ),
            'fields' => array('EventsEntry.events_detail_id')
        ));
        
        $this->loadModel('EventsDetail');
        $event_lists = $this->EventsDetail->find('list', array(
            'conditions' => array(
                'EventsDetail.id' => $entry_lists,
                //イベント作成者とエントリー登録者が違うもののみ
                'EventsDetail.user_id !=' => $user_id
            ),
            'order' => array('EventsDetail.date' => 'asc', 'EventsDetail.time_start' => 'asc'),
            'fields' => array('EventsDetail.id')
        ));
        $data['events_detail_id'] = $event_lists;
        
        return $data;
    }
}
