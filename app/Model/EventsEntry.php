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
            'fields' => array('title', 'entry_cost_id', 'entry_rule_id', 'entry_system_id') //関連付け先Modelの使用field
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
    
    public function getEntryStatus()
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
    
    public function getEventStatus($id = null, $user_id = null, $status = -1)
    {
        //イベントのデータを取得しておく
        $this->loadModel('EventsDetail');
        $event_data = $this->EventsDetail->find('first', array('conditions' => array('EventsDetail.id' => $id)));
        
        //データの作成者とログインユーザが一致しない場合
        if (!$user_id) {
            $user_id = AuthComponent::user(['id']);
        }
        if ($event_data['EventsDetail']['user_id'] != $user_id) {
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
        $event_detail_status = $this->EventStatus->checkEventsDetailStatus($event_data['EventsDetail']['id'], null, $user_id);
        if ($event_detail_status) {
            $status = $event_detail_status;
        }
        
        return $status;
    }
    
    public function getPaymentStatus()
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
    
    public function searchEntryDate($user_id = null, $s_date = null, $e_date = null, $join_lists = [])
    {
        //ユーザIDを収録
        if (!$user_id) {
            $user_id = AuthComponent::user(['id']);
        }
        //参加済のイベントを取得しておく
        $this->loadModel('EventUser');
        $join_lists = $this->EventUser->getJoinEntries($user_id);
        
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
        
        $this->Behaviors->load('Containable');
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
            'order' => array('EventsDetail.date' => 'asc', 'EventsDetail.time_start' => 'asc'),
            'contain' => array(
                'Event',
                'EventsDetail',
                'User',
                'EntryGenre' => array('EntryCost', 'EntryRule')
            )
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
    
    public function getOnlyEntries($user_id = null, $data = ['list' => [], 'events_detail_id' => []])
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
    
    public function formatEventsReport($event_lists = false, $artist_id = null, $user_id = null, $report = [])
    {
        //イベントのstatusとcastを取得
        $this->loadModel('EventArtist');
        foreach ($event_lists as $key => $val) {
            $event_lists[$key]['EventsDetail']['status'] = $this->getEventStatus($val['EventsDetail']['id'], $user_id);
            $event_lists[$key]['EventArtist'] = $this->EventArtist->getCastList($val['EventsDetail']['id']);
            //castの数とartist_idからワンマンかどうかのflgを立てておく
            if (count($event_lists[$key]['EventArtist']) == 1 && $event_lists[$key]['EventArtist'][0]['EventArtist']['artist_id'] == $artist_id) {
                $event_lists[$key]['EventsDetail']['oneman'] = 1;
            } else {
                $event_lists[$key]['EventsDetail']['oneman'] = 0;
            }
        }
        
        //イベントレポートを作成
        $count_report = array(
            'count_all' => 0,
            'count_entry' => 0,
            'count_win' => 0,
            'count_reject' => 0,
            'count_join' => 0,
            'per_win' => 0,
        );
        $report['all'] = $count_report;
        $report['oneman'] = $count_report;
        //イベント頻度を算出するため
        $event_join_lists = array();
        $oneman_join_lists = array();
        
        //イベントデータを算出
        foreach ($event_lists as $key => $val) {
            //登録数
            $report['all']['count_all']++;
            if ($val['EventsDetail']['oneman'] == 1) {
                $report['oneman']['count_all']++;
            }
            //申込んだイベント
            if ($val['EventsDetail']['status'] == 1 || $val['EventsDetail']['status'] == 2 || $val['EventsDetail']['status'] == 3) {
                $report['all']['count_entry']++;
                if ($val['EventsDetail']['oneman'] == 1) {
                    $report['oneman']['count_entry']++;
                }
                //当選したイベント
                if ($val['EventsDetail']['status'] == 2) {
                    $report['all']['count_win']++;
                    if ($val['EventsDetail']['oneman'] == 1) {
                        $report['oneman']['count_win']++;
                    }
                    //参加したイベント
                    if ($val['EventsDetail']['date'] < date('Y-m-d')) {
                        $report['all']['count_join']++;
                        $event_join_lists[] = $val;
                        if ($val['EventsDetail']['oneman'] == 1) {
                            $report['oneman']['count_join']++;
                            $oneman_join_lists[] = $val;
                        }
                    }
                //落選したイベント
                } elseif ($val['EventsDetail']['status'] == 3) {
                    $report['all']['count_reject']++;
                    if ($val['EventsDetail']['oneman'] == 1) {
                        $report['oneman']['count_reject']++;
                    }
                }
            }
        }
        //当選率
        if ($report['all']['count_win'] + $report['all']['count_reject'] > 0) {
            $report['all']['per_win'] = round($report['all']['count_win'] / ($report['all']['count_win'] + $report['all']['count_reject']), 3) * 100;
        }
        if ($report['oneman']['count_win'] + $report['oneman']['count_reject'] > 0) {
            $report['oneman']['per_win'] = round($report['oneman']['count_win'] / ($report['oneman']['count_win'] + $report['oneman']['count_reject']), 3) * 100;
        }
        //イベント頻度
        $report['all'] += $this->getEventsSpan($event_join_lists);
        $report['oneman'] += $this->getEventsSpan($oneman_join_lists);
//        echo'<pre>';print_r($report);echo'</pre>';exit;
        
        return $report;
    }
    
    public function getEventsSpan($event_lists = false, $span = ['span_current' => 0, 'span_rating' => 0, 'span_tenth' => 0])
    {
        if (count($event_lists) > 0) {
            //最初の参加イベントと最新の参加イベントを取得しておく
            $event_first = $event_lists[0];
            $event_lists = array_reverse($event_lists);
            $event_latest = $event_lists[0];
            
            //同じ日のイベントは一つとして計算
            foreach ($event_lists as $key => $val) {
                if ($key == 0) {
                    $pre_date = $val['EventsDetail']['date'];
                    continue;
                }
                if ($val['EventsDetail']['date'] == $pre_date) {
                    unset($event_lists[$key]);
                }
                $pre_date = $val['EventsDetail']['date'];
            }
            $event_lists = array_merge($event_lists);
            
            //参加したイベントが一つだけの場合
            if (count($event_lists) == 1) {
                $latest_time = strtotime(date('Y-m-d')) - strtotime($event_latest['EventsDetail']['date']);
                $latest_time = $latest_time /60 /60 /24;
                $span['span_current'] = $latest_time;
                $span['span_rating'] = 0;
                $span['span_tenth'] = 0;
            //参加したイベントが複数ある場合
            } else {
                $latest_time = strtotime(date('Y-m-d')) - strtotime($event_latest['EventsDetail']['date']);
                $latest_day = $latest_time /60 /60 /24;
                $span['span_current'] = $latest_day;
                $first_time = strtotime(date('Y-m-d')) - strtotime($event_first['EventsDetail']['date']);
                $first_day = $first_time /60 /60 /24;
                $span['span_rating'] = round($first_day / count($event_lists), 1);
                
                //直近10イベント分だけの頻度も算出しておく
                if (count($event_lists) < 10) {
                    $span['span_tenth'] = $span['span_rating'];
                } else {
                    $event_tenth = $event_lists[9];
                    $tenth_time = strtotime(date('Y-m-d')) - strtotime($event_tenth['EventsDetail']['date']);
                    $tenth_day = $tenth_time /60 /60 /24;
                    $span['span_tenth'] = round($tenth_day / 10, 1);
                }
            }
        }
        
        return $span;
    }
}
