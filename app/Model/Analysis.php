<?php

App::uses('AppModel', 'Model');

class Analysis extends AppModel
{
    public $useTable = false;
    
//    public $actsAs = array('SoftDelete');
    
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
    
    //ユーザに紐付く全てのイベントを取得
    public function getEventData($user_id = null)
    {
        //ユーザIDを取得
        if (!$user_id) {
            $user_id = AuthComponent::user(['id']);
        }
        
        //参加済のイベント一覧を取得しておく
        $this->loadModel('EventUser');
        $join_lists = $this->EventUser->getJoinEntries($user_id);
        //エントリーのみの一覧を取得しておく
        $this->loadModel('EventsEntry');
        $entry_only_lists = $this->EventsEntry->getOnlyEntries($user_id);
        
        $this->loadModel('EventsDetail');
        $event_lists = $this->EventsDetail->find('all', array(
            'conditions' => array(
                'or' => array(
                    array('EventsDetail.user_id' => $user_id),
                    array('EventsDetail.id' => $join_lists['events_detail_id']),
                    array('EventsDetail.id' => $entry_only_lists['events_detail_id'])
                ),
                'EventsDetail.deleted !=' => 1
            ),
            'order' => array('EventsDetail.date' => 'asc', 'EventsDetail.time_start' => 'asc'),
            'contain' => array('Event', 'Place')
        ));
        
        $this->loadModel('EventArtist');
        foreach ($event_lists as $key => $event) {
            //出演者の取得
            $cast_lists = $this->EventArtist->getCastList($event['EventsDetail']['id']);
            $cast_data = array();
            foreach ($cast_lists as $cast) {
                $cast_data[] = array(
                    'id' => $cast['EventArtist']['artist_id'],
                    'name' => $cast['ArtistProfile']['name']
                );
            }
            $event_lists[$key]['Artist'] = $cast_data;
            //イベントステータスの取得
            $status = $this->EventsEntry->getEventStatus($event['EventsDetail']['id'], $user_id);
            $event_lists[$key]['EventsDetail']['status'] = $status;
        }
        
        return $event_lists;
    }
    
    public function countEventFromEventList($event_lists = false, $event_counts = ['event' => 0, 'entry' => 0, 'join' => 0])
    {
        foreach ($event_lists as $event) {
            $event_counts['event']++;
            if ($event['EventsDetail']['status'] == 0) { //検討中
                
            }
            if ($event['EventsDetail']['status'] == 1) { //申込中
                $event_counts['entry']++;
            }
            if ($event['EventsDetail']['status'] == 2) { //当選
                $event_counts['entry']++;
                $event_counts['join']++;
            }
            if ($event['EventsDetail']['status'] == 3) { //落選
                $event_counts['entry']++;
            }
            if ($event['EventsDetail']['status'] == 4) { //見送り
                
            }
        }
        
        return $event_counts;
    }
    
    //モード毎の配列に整形、$mode = year, artist, place, music
    public function formatEventListToArray($event_lists = false, $mode = false, $options = false, $data = [])
    {
        if (!$event_lists) {
            return $data;
        }
        
        //オプションの設定
        //statusは配列で指定、なければ 当選 = 2 のみ
        if (@$options['status']) {
            $array_status = $options['status'];
        } else {
            $array_status = array(2);
        }
        //artistの指定があればconditionに追加するため
        if (@$options['artist']) {
            $conditions_artist = array('EventSetlist.artist_id' => $options['artist']);
        } else {
            $conditions_artist = array();
        }
        
        //モード指定がなければデフォルトに戻す
        if (!$mode) {
            if (count($event_lists) > 0) {
                if (@$event_lists[0]['EventsDetail']) {
                    $data = $event_lists;
                } else {
                    foreach ($event_lists as $events) {
                        foreach ($events as $key => $event) {
                            if ($key !== 'analysis') {
                                $data[] = $event;
                            }
                        }
                    }
                    //日付の昇順にソートし直しておく
                    foreach ($data as $key => $val) {
                        $sort_date[$key] = $val['EventsDetail']['date'];
                    }
                    array_multisort($sort_date, SORT_ASC, $data);
                }
            } else {
                $data = $event_lists;
            }
        
        //年毎の配列に整形
        } elseif ($mode == 'year') {
            foreach ($event_lists as $key => $event) {
                if (in_array($event['EventsDetail']['status'], $array_status)) {
                    list($yy, $mm, $dd) = explode('-', $event['EventsDetail']['date']);
                    $data[$yy . '年'][] = $event;
                }
            }
            //年データを追加
            foreach ($data as $key => $val) {
                $data[$key]['analysis'] = array(
                    'count' => count($val),
                    'year' => $key
                );
            }
            //年の降順にソートしておく
            foreach ($data as $key => $val) {
                $sort[$key] = $val['analysis']['year'];
            }
            array_multisort($sort, SORT_DESC, $data);
        
        //アーティスト毎の配列に整形
        } elseif ($mode == 'artist') {
            foreach ($event_lists as $key => $event) {
                if (in_array($event['EventsDetail']['status'], $array_status)) {
                    foreach ($event['Artist'] as $cast) {
                        $data[$cast['id'] . ':::' . $cast['name']][] = $event;
                    }
                }
            }
            //アーティストデータを追加
            foreach ($data as $key => $val) {
                list($artist_id, $artist_name) = explode(':::', $key);
                $data[$key]['analysis'] = array(
                    'count' => count($val),
                    'artist' => array(
                        'id' => $artist_id,
                        'name' => $artist_name
                    )
                );
            }
            //アーティストのイベント数の降順にソートしておく
            foreach ($data as $key => $val) {
                $sort[$key] = $val['analysis']['count'];
            }
            array_multisort($sort, SORT_DESC, $data);
        
        //会場ごとの配列に変換
        } elseif ($mode == 'place') {
            foreach ($event_lists as $key => $event) {
                if (in_array($event['EventsDetail']['status'], $array_status)) {
                    $data[$event['Place']['name']][] = $event;
                }
            }
            //会場データを追加
            foreach ($data as $key => $val) {
                $data[$key]['analysis'] = array(
                    'count' => count($val),
                    'place' => $val[0]['Place']['name']
                );
            }
            //会場のイベント数の降順にソートしておく
            foreach ($data as $key => $val) {
                $sort[$key] = $val['analysis']['count'];
            }
            array_multisort($sort, SORT_DESC, $data);
        
        //楽曲ごとの配列に変換
        } elseif ($mode == 'music') {
            //セットリストを取得して
            $this->loadModel('EventSetlist');
            foreach ($event_lists as $key => $event) {
                if (in_array($event['EventsDetail']['status'], $array_status)) {
                    $setlist = $this->EventSetlist->find('all', array(
                        'conditions' => array(
                            'EventSetlist.events_detail_id' => $event['EventsDetail']['id'],
                            $conditions_artist
                        )
                    ));
                    //楽曲ごとに整形
                    foreach ($setlist as $key2 => $music) {
                        //explodeを想定して曲名に含まれなそうな文字で区切る、イベントデータはevents_detail_idのみ
                        $data[$music['EventSetlist']['title'] . ':::' . $music['ArtistProfile']['name']][] = $event['EventsDetail']['id'];
                    }
                }
            }
            //楽曲データを追加
            foreach ($data as $key => $val) {
                list($music_title, $music_artist) = explode(':::', $key);
                $data[$key]['analysis'] = array(
                    'count' => count($val),
                    'music' => array(
                        'title' => $music_title,
                        'artist' => $music_artist
                    )
                );
            }
            //楽曲のイベント数の降順にソートしておく
            foreach ($data as $key => $val) {
                $sort[$key] = $val['analysis']['count'];
            }
            array_multisort($sort, SORT_DESC, $data);
        
        //モード指定のエラー
        } else {
            trigger_error(htmlentities('Undefined mode in formatEventListToArray.'), E_USER_ERROR);
        }
        
        return $data;
    }
}
