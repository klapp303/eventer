<?php

App::uses('AppController', 'Controller');

class EventsController extends AppController
{
    public $uses = array('EventArtist', 'EventUser', 'EventStatus', 'Event', 'EventsDetail', 'EventsEntry', 'EventGenre', 'EntryGenre', 'User', 'Place', 'Artist'); //使用するModel
    
    public $components = array('Paginator', 'Search.Prg');
    
    public $paginate = array(
        'limit' => 20,
        'order' => array('id' => 'desc')
    );
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'eventer_fullwidth';
        $this->Auth->allow('schedule');
//        $this->Event->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
    }
    
    public function index()
    {
        $GUEST_USER_KEY = $this->Option->getOptionKey('GUEST_USER_KEY');
        
        //参加済のイベント一覧を取得しておく
        $join_lists = $this->EventUser->getJoinEntries($this->Auth->user('id'));
        //エントリーのみの一覧を取得しておく
        $entry_only_lists = $this->EventsEntry->getOnlyEntries($this->Auth->user('id'));
        
        //フォームの初期表示数
        $this->set('form_min', 2);
        
        $this->Paginator->settings = array( //eventsページのイベント一覧を設定
            'conditions' => array(
                'and' => array(
                    'EventsDetail.date >=' => date('Y-m-d'),
                    'or' => array(
                        array('EventsDetail.user_id' => $this->Auth->user('id')),
                        array('EventsDetail.id' => $join_lists['events_detail_id']),
                        array('EventsDetail.id' => $entry_only_lists['events_detail_id'])
                    )
                )
            ),
            'order' => array('EventsDetail.date' => 'asc', 'EventsDetail.time_start' => 'asc')
        );
        $event_lists = $this->Paginator->paginate('EventsDetail');
        //イベントのstatusを取得
        foreach ($event_lists as $key => $event_list) {
            $event_lists[$key]['EventsDetail']['status'] = $this->EventsEntry->getEventStatus($event_list['EventsDetail']['id']);
        }
        $event_genres = $this->EventGenre->find('list'); //プルダウン選択肢用
        $place_lists = $this->Place->find('list', array('conditions' => array('Place.id !=' => 5))); //プルダウン選択肢用
        $this->set(compact('event_lists', 'event_genres', 'place_lists'));
        
        //パラメータにidがあれば詳細ページを表示
        if (isset($this->request->params['id']) == true) {
            $event_detail = $this->EventsDetail->find('first', array(
                'conditions' => array(
                    'and' => array(
                        'EventsDetail.id' => $this->request->params['id'],
                        'or' => array(
                            array('EventsDetail.user_id' => $this->Auth->user('id')),
                            array('EventsDetail.id' => $join_lists['events_detail_id']),
                            array('EventsDetail.id' => $entry_only_lists['events_detail_id']),
                            array('Event.publish' => 1)
                        ),
//                        'EventsDetail.user_id !=' => $GUEST_USER_KEY
                    )
                ),
                'recursive' => 2
            ));
            if (!empty($event_detail)) { //データが存在する場合
                //ゲストユーザの場合は自身のイベントのみ
                if ($this->Auth->user('id') == $GUEST_USER_KEY) {
                    if ($event_detail['EventsDetail']['user_id'] != $GUEST_USER_KEY) {
                        $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
                        return;
                    }
                }
                
                $this->set('PLACE_OTHER_KEY', $this->Option->getOptionKey('PLACE_OTHER_KEY'));
                
                //breadcrumbの設定
                if ($event_detail['Event']['title'] == $event_detail['EventsDetail']['title']) {
                    $event_name = $event_detail['Event']['title'];
                } else {
                    $event_name = $event_detail['Event']['title'] . ' ' . $event_detail['EventsDetail']['title'];
                }
                $this->set('sub_page', $event_name);
//                echo'<pre>';print_r($event_detail);echo'</pre>';
                //イベント自体の見送り判定
                $event_detail_status = $this->EventStatus->checkEventsDetailStatus($event_detail['EventsDetail']['id']);
                $this->set('event_detail_status', $event_detail_status);
                //エントリー一覧
                $entry_lists = $this->EventsEntry->find('all', array(
                    'conditions' => array(
                        'EventsEntry.events_detail_id' => $event_detail['EventsDetail']['id']
                    ),
                    'order' => array('EventsEntry.date_start' => 'asc')
                ));
                //別日程
                $other_lists = $this->EventsDetail->find('all', array(
                    'conditions' => array(
                        'and' => array(
                            'EventsDetail.id !=' => $event_detail['EventsDetail']['id'],
                            'EventsDetail.event_id' => $event_detail['EventsDetail']['event_id'],
                            'or' => array(
                                array('EventsDetail.user_id' => $this->Auth->user('id')),
                                array('EventsDetail.id' => $join_lists['events_detail_id']),
                                array('EventsDetail.id' => $entry_only_lists['events_detail_id']),
                                array('Event.publish' => 1)
                            )
                        )
                    ),
                    'order' => array('EventsDetail.date' => 'asc', 'EventsDetail.time_start' => 'asc')
                ));
                //出演者
                $cast_lists = $this->EventArtist->find('all', array(
                    'conditions' => array(
                        'EventArtist.events_detail_id' => $event_detail['EventsDetail']['id']
                    ),
                    'order' => array('ArtistProfile.kana' => 'asc')
                ));
                foreach ($cast_lists as $key => $val) {
                    unset($cast_lists[$key]['Event']);
                    unset($cast_lists[$key]['EventsDetail']);
                }
                $this->set(compact('event_detail', 'entry_lists', 'other_lists', 'cast_lists'));
                
                $this->render('event');
                
            } else { //データが存在しない場合
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
            }
        }
    }
    
    public function add()
    {
        if ($this->request->is('post')) {
            //eventsテーブルに保存
            $dataEvent['Event'] = $this->request->data['Event'];
            $this->Event->set($dataEvent); //postデータをModelに渡してvalidate
            if ($this->Event->validates()) { //validate成功の処理
                if ($this->Event->save($dataEvent)) { //validate成功でsave
//                    $this->Session->setFlash('登録しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash($dataEvent['Event']['title'] . ' を登録できませんでした。', 'flashMessage');
                    
                    $this->redirect('/events/');
                }
            } else { //validate失敗の処理
                $this->Session->setFlash($dataEvent['Event']['title'] . ' の入力に不備があります。', 'flashMessage');
                
                $this->redirect('/events/');
            }
            
            //events_detailsテーブルに保存
            /* データをテーブルの構造に合わせて加工ここから */
            $saveEvent = $this->Event->find('first', array('order' => array('Event.id' => 'desc')));
            $dataDetails['EventsDetail'] = $this->request->data['EventsDetail'];
            foreach ($dataDetails['EventsDetail'] as $key => &$dataDetail) {
                $dataDetail['event_id'] = $saveEvent['Event']['id'];
                $dataDetail['user_id'] = $saveEvent['Event']['user_id'];
                if (@$dataDetail['time_open_null'] == 1) {
                    $dataDetail['time_open'] = null;
                }
                if (@$dataDetail['time_start_null'] == 1) {
                    $dataDetail['time_start'] = null;
                }
                if (!$dataDetail['title']) { //タイトルが無いデータは削除
                    unset($dataDetails['EventsDetail'][$key]);
                }
                if ($key >= $this->request->data['form_count']) { //表示フォーム数を超えるデータは削除
                    unset($dataDetails['EventsDetail'][$key]);
                }
            }
            unset($dataDetail);
            /* データをテーブルの構造に合わせて加工ここまで */
            foreach ($dataDetails['EventsDetail'] as $dataDetail) {
                //postデータが複数あるので1つずつvalidateする
                $this->EventsDetail->set($dataDetail); //postデータをModelに渡してvalidate
                if (!$this->EventsDetail->validates()) { //validate失敗の処理
                    $this->Session->setFlash($dataDetail['EventsDetail']['title'] . ' の入力に不備があります。', 'flashMessage');
                    
                    $this->redirect('/events/');
                }
            }
            if ($this->EventsDetail->saveMany($dataDetails['EventsDetail'])) { //validate成功でsave
                $message = '';
                foreach ($dataDetails['EventsDetail'] as $dataDetail) {
                    $message .= '<br>' . $dataDetail['title'];
                }
//                $message = ltrim($message, '<br>');
                $this->Session->setFlash($dataEvent['Event']['title'] . ' の' . $message . ' を登録しました。', 'flashMessage');
                
                //event_artistsテーブルに保存
                $saveDetails = $this->EventsDetail->find('list', array(
                    'conditions' => array('EventsDetail.event_id' => $saveEvent['Event']['id']),
                    'fields' => array('EventsDetail.id')
                ));
                $dataArtists = [];
                $array_artists = $this->Artist->find('list', array(
                    'conditions' => array(),
                    'fields' => array('Artist.name')
                ));
                foreach ($array_artists as $key => $val) {
                    //イベントタイトルにアーティスト名があれば出演者タグとして登録する
                    if (strpos($dataEvent['Event']['title'], $val) !== false) {
                        foreach ($saveDetails as $save_detail_id) {
                            $dataArtists[] = array(
                                'event_id' => $saveEvent['Event']['id'],
                                'events_detail_id' => $save_detail_id,
                                'artist_id' => $key
                            );
                        }
                    }
                }
                if (!empty($dataArtists)) {
                    $this->EventArtist->saveMany($dataArtists);
                }
                
            } else {
                $this->Session->setFlash($dataEvent['Event']['title'] . ' を登録できませんでした。', 'flashMessage');
            }
        }
        
        $this->redirect('/events/');
    }
    
    public function edit($id = null)
    {   
        //参加済のイベント一覧を取得しておく
        $join_lists = $this->EventUser->getJoinEntries($this->Auth->user('id'));
        //エントリーのみの一覧を取得しておく
        $entry_only_lists = $this->EventsEntry->getOnlyEntries($this->Auth->user('id'));
        
        //フォームの初期表示数
        $this->set('form_min', 2);
        
        $this->Paginator->settings = array( //eventsページのイベント一覧を設定
            'conditions' => array(
                'and' => array(
                    'EventsDetail.date >=' => date('Y-m-d'),
                    'or' => array(
                        array('EventsDetail.user_id' => $this->Auth->user('id')),
                        array('EventsDetail.id' => $join_lists['events_detail_id']),
                        array('EventsDetail.id' => $entry_only_lists['events_detail_id'])
                    )
                )
            ),
            'order' => array('EventsDetail.date' => 'asc', 'EventsDetail.time_start' => 'asc')
        );
        $event_lists = $this->Paginator->paginate('EventsDetail');
        //イベントのstatusを取得
        foreach ($event_lists as $key => $event_list) {
            $event_lists[$key]['EventsDetail']['status'] = $this->EventsEntry->getEventStatus($event_list['EventsDetail']['id']);
        }
        $event_genres = $this->EventGenre->find('list'); //プルダウン選択肢用
        $place_lists = $this->Place->find('list', array('conditions' => array('Place.id !=' => 5))); //プルダウン選択肢用
        $this->set(compact('event_lists', 'event_genres', 'place_lists'));
        
        if (empty($this->request->data)) {
            $this->request->data = $this->Event->findById($id); //postデータがなければ$idからデータを取得
            if (!empty($this->request->data)) { //データが存在する場合
                if ($this->request->data['Event']['user_id'] == $this->Auth->user('id')) { //データの作成者とログインユーザが一致する場合
                    //breadcrumbの設定
                    $this->set('sub_page', $this->request->data['Event']['title']);
                    foreach ($this->request->data['EventsDetail'] as &$events_detail) {
                        if ($events_detail['time_open'] == null) {
                            $events_detail['time_open_null'] = 1;
                        }
                        if ($events_detail['time_start'] == null) {
                            $events_detail['time_start_null'] = 1;
                        }
                    }
                    unset($events_detail);
                    $this->set('requestData', $this->request->data); //view側でnullかどうかを判定するため
                    //フォームの初期表示数を書き換え
                    $form_min = count($this->request->data['EventsDetail']);
                    if ($form_min %2 != 0) {
                        $form_min++;
                    }
                    $this->set('form_min', $form_min);
                } else { //データの作成者とログインユーザが一致しない場合
                    $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
                    
                    $this->redirect('/events/');
                }
            } else { //データが存在しない場合
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
                
                $this->redirect('/events/');
            }
        } else {
            //eventsテーブルに保存
            $dataEvent['Event'] = $this->request->data['Event'];
            $this->Event->set($dataEvent); //postデータをModelに渡してvalidate
            if ($this->Event->validates()) { //validate成功の処理
                if ($this->Event->save($dataEvent)) { //validate成功でsave
                    //$this->Session->setFlash('登録しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash($dataEvent['Event']['title'] . ' を修正できませんでした。', 'flashMessage');
                    
                    $this->redirect('/events/');
                }
            } else { //validate失敗の処理
                $this->Session->setFlash($dataEvent['Event']['title'] . ' の入力に不備があります。', 'flashMessage');
                
                $this->redirect('/events/');
            }
            
            //events_detailsテーブルに保存
            /* データをテーブルの構造に合わせて加工ここから */
            $dataDetails['EventsDetail'] = $this->request->data['EventsDetail'];
            $delEventsDetailId = [];
            foreach ($dataDetails['EventsDetail'] as $key => &$dataDetail) {
                $dataDetail['event_id'] = $dataEvent['Event']['id'];
                $dataDetail['user_id'] = $this->Auth->user('id');
                if (@$dataDetail['time_open_null'] == 1) {
                    $dataDetail['time_open'] = null;
                }
                if (@$dataDetail['time_start_null'] == 1) {
                    $dataDetail['time_start'] = null;
                }
                if (!$dataDetail['title']) { //タイトルが無いデータは削除
                    //元々データがあってタイトルが削除された場合は後で削除するため
                    if (@$dataDetail['id']) {
                        $delEventsDetailId = array_merge($delEventsDetailId, array($dataDetail['id']));
                    }
                    unset($dataDetails['EventsDetail'][$key]);
                }
                if ($key >= $this->request->data['form_count']) { //表示フォーム数を超えるデータは更新しない
                    unset($dataDetails['EventsDetail'][$key]);
                }
            }
            unset($dataDetail);
            /* データをテーブルの構造に合わせて加工ここまで */
            foreach ($dataDetails['EventsDetail'] as $dataDetail) {
                //postデータが複数あるので1つずつvalidateする
                $this->EventsDetail->set($dataDetail); //postデータをModelに渡してvalidate
                if (!$this->EventsDetail->validates()) { //validate失敗の処理
                    $this->Session->setFlash($dataDetail['EventsDetail']['title'] . ' の入力に不備があります。', 'flashMessage');
                    
                    $this->redirect('/events/');
                }
            }
            if ($this->EventsDetail->saveMany($dataDetails['EventsDetail'])) { //validate成功でsave
                //開演日時に変更があればevents_entriesテーブルを更新
                foreach ($dataDetails['EventsDetail'] as $dataDetail) {
                    if ($dataDetail['time_start']) {
                        $date_event = $dataDetail['date']['year'] . '-' . $dataDetail['date']['month'] . '-' . $dataDetail['date']['day'] . ' ' . $dataDetail['time_start']['hour'] . ':' . $dataDetail['time_start']['min'];
                    }
                    if (!$dataDetail['time_start'] || $dataDetail['time_start_null'] == 1) {
                        $date_event = $dataDetail['date']['year'] . '-' . $dataDetail['date']['month'] . '-' . $dataDetail['date']['day'];
                    }
                    $entryId = $this->EventsEntry->find('list', array(
                        'conditions' => array('EventsEntry.events_detail_id' => $dataDetail['id']),
                        'fields' => 'EventsEntry.id'
                    ));
                    foreach ($entryId as $id) {
                        $this->EventsEntry->id = $id;
                        $this->EventsEntry->savefield('date_event', $date_event);
                    }
                }
                //タイトルが削除されていればデータを削除
                foreach ($delEventsDetailId as $id) {
                    $this->EventsDetail->Behaviors->enable('SoftDelete');
                    $this->EventsDetail->delete($id);
                }
                
                $message = '';
                foreach ($dataDetails['EventsDetail'] as $dataDetail) {
                    $message .= '<br>' . $dataDetail['title'];
                }
//                $message = ltrim($message, '<br>');
                $this->Session->setFlash($dataEvent['Event']['title'] . ' の' . $message . ' を登録、修正しました。', 'flashMessage');
                
                $this->redirect('/events/');
                
            } else {
                $this->Session->setFlash($dataEvent['Event']['title'] . ' を登録、修正できませんでした。', 'flashMessage');
            }
        }
        
        $this->render('index');
    }
    
    public function delete($id = null)
    {
        if (empty($id)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        
        if ($this->request->is('post')) {
            $this->EventsDetail->Behaviors->enable('SoftDelete');
            if ($this->EventsDetail->delete($id)) {
                $this->Session->setFlash('削除しました。', 'flashMessage');
            } else {
                $this->Session->setFlash('削除できませんでした。', 'flashMessage');
            }
            
            $this->redirect('/events/');
        }
    }
    
    public function event_skip($id = null)
    {
        if ($this->request->is('post')) {
            $GUEST_USER_KEY = $this->Option->getOptionKey('GUEST_USER_KEY');
            
            if (empty($id)) {
                throw new NotFoundException(__('存在しないデータです。'));
            }
            $events_detail = $this->EventsDetail->findById($id);
            
            if ($events_detail['EventsDetail']['user_id'] != $this->Auth->user('id')) { //データの作成者とログインユーザが一致しない場合
                //公開中のイベントでなければredirect
                if ($events_detail['Event']['publish'] != 1) {
                    $this->redirect('/events/' . $id);
                }
            }
            if (!$events_detail) {
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
                $this->redirect('/events/' . $id);
            }
            
            //現在のステータスを取得
            $events_detail_status = $this->EventStatus->checkEventsDetailStatus($events_detail['EventsDetail']['id'], 'ARRAY');
            //既に見送るの場合はステータスを戻す
            if (@$events_detail_status['EventStatus']['status'] == 4) {
//                $this->Eventstatus->Behaviors->enable('SoftDelete');
                if ($this->EventStatus->delete($events_detail_status['EventStatus']['id'])) {
//                $this->EventStatus->id = $events_detail_status['EventStatus']['id'];
//                if ($this->EventStatus->saveField('status', 0)) {
                    $this->Session->setFlash('イベントのstatusを戻しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash('イベントのstatusを戻せませんでした。', 'flashMessage');
                }
                
            //それ以外はステータスを見送るに変更する
            } else {
                $save_data[] = array(
                    'event_id' => $events_detail['Event']['id'],
                    'events_detail_id' => $id,
                    'user_id' => $this->Auth->user('id'),
                    'status' => 4
                );
                if ($this->EventStatus->saveMany($save_data)) {
                    $this->Session->setFlash('イベントのstatusを見送りに変更しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash('イベントのstatusを変更できませんでした。', 'flashMessage');
                }
            }
            
            $this->redirect('/events/' . $id);
        }
    }
    
    public function entry_add($id = null)
    {
        $GUEST_USER_KEY = $this->Option->getOptionKey('GUEST_USER_KEY');
        
        //エントリーの日付カラムを定義しておく
        $entryDateColumn = $this->EventsEntry->getDateColumn();
        
        if ($this->request->is('post')) {
            $id = $this->request->data['EventsEntry']['events_detail_id'];
        }
        if (empty($id)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        $events_detail = $this->EventsDetail->findById($id);
        
        if ($events_detail['EventsDetail']['user_id'] != $this->Auth->user('id')) { //データの作成者とログインユーザが一致しない場合
            //公開中のイベントでなければredirect
            if ($events_detail['Event']['publish'] != 1) {
                $this->redirect('/events/' . $id);
            }
            
            //ゲストユーザの場合は自身のイベントのみ
            if ($this->Auth->user('id') == $GUEST_USER_KEY) {
                $this->redirect('/events/' . $id);
            }
        }
        
        //breadcrumbの設定
        if ($events_detail['Event']['title'] == $events_detail['EventsDetail']['title']) {
            $event_name = $events_detail['Event']['title'];
        } else {
            $event_name = $events_detail['Event']['title'] . ' ' . $events_detail['EventsDetail']['title'];
        }
        $this->set('sub_page', $event_name);
        
        $this->set('events_detail', $events_detail);
        $this->set('entry_genres', $this->EntryGenre->find('list'));
        $this->set('events_detail_id', $id);
        
        if ($this->request->is('post')) {
            //events_entriesテーブルに保存
            /* データをテーブルの構造に合わせて加工ここから */
            foreach ($entryDateColumn as $column) {
                if ($this->request->data['EventsEntry'][$column . '_null'] == 1) {
                    $this->request->data['EventsEntry'][$column] = null;
                }
            }
            /* データをテーブルの構造に合わせて加工ここまで */
            $this->EventsEntry->set($this->request->data); //postデータをModelに渡してvalidate
            if ($this->EventsEntry->validates()) { //validate成功の処理
                if ($this->EventsEntry->save($this->request->data)) { //validate成功でsave
                    $this->Session->setFlash($this->request->data['EventsEntry']['title'] . ' を登録しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash($this->request->data['EventsEntry']['title'] . ' を登録できませんでした。', 'flashMessage');
                }
            } else { //validate失敗の処理
                $this->Session->setFlash($this->request->data['EventsEntry']['title'] . ' の入力に不備があります。', 'flashMessage');
            }
            
            $this->redirect('/events/' . $this->request->data['EventsEntry']['events_detail_id']);
        }
        
        $this->render('entry');
    }
    
    public function entry_edit($id = null)
    {
        //エントリーの日付カラムを定義しておく
        $entryDateColumn = $this->EventsEntry->getDateColumn();
        
        if (empty($this->request->data)) {
            $this->set('entry_genres', $this->EntryGenre->find('list'));
            
            $this->request->data = $this->EventsEntry->findById($id); //postデータがなければ$idからデータを取得
            $this->set('events_detail_id', $this->request->data['EventsEntry']['events_detail_id']);
            if (!empty($this->request->data)) { //データが存在する場合
                if ($this->request->data['EventsEntry']['user_id'] == $this->Auth->user('id')) { //データの作成者とログインユーザが一致する場合
                    //breadcrumbの設定
                    if ($this->request->data['Event']['title'] == $this->request->data['EventsDetail']['title']) {
                        $event_name = $this->request->data['Event']['title'];
                    } else {
                        $event_name = $this->request->data['Event']['title'] . ' ' . $this->request->data['EventsDetail']['title'];
                    }
                    $this->set('sub_page', $event_name);
                    
                    foreach ($entryDateColumn as $column) {
                        if ($this->request->data['EventsEntry'][$column] == null) {
                            $this->request->data['EventsEntry'][$column . '_null'] = 1;
                        }
                    }
                    $this->set('requestData', $this->request->data); //view側でnullかどうかを判定するため
                } else { //データの作成者とログインユーザが一致しない場合
                    $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
                    
                    $this->redirect('/events/');
                }
            } else { //データが存在しない場合
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
                
                $this->redirect('/events/');
            }
        } else {
            //events_entriesテーブルに保存
            /* データをテーブルの構造に合わせて加工ここから */
            foreach ($entryDateColumn as $column) {
                if ($this->request->data['EventsEntry'][$column . '_null'] == 1) {
                    $this->request->data['EventsEntry'][$column] = null;
                }
            }
            /* データをテーブルの構造に合わせて加工ここまで */
            $this->EventsEntry->set($this->request->data); //postデータをModelに渡してvalidate
            if ($this->EventsEntry->validates()) { //validate成功の処理
                if ($this->EventsEntry->save($this->request->data)) { //validate成功でsave
                    $this->Session->setFlash($this->request->data['EventsEntry']['title'] . ' を修正しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash($this->request->data['EventsEntry']['title'] . ' を修正できませんでした。', 'flashMessage');
                }
            } else { //validate失敗の処理
                $this->Session->setFlash($this->request->data['EventsEntry']['title'] . ' の入力に不備があります。', 'flashMessage');
            }
            
            $this->redirect('/events/' . $this->request->data['EventsEntry']['events_detail_id']);
        }
        
        $this->render('entry');
    }
    
    public function entry_delete($id = null, $events_detail_id = null)
    {
        if (empty($id)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        
        if ($this->request->is('post')) {
            $this->EventsEntry->Behaviors->enable('SoftDelete');
            if ($this->EventsEntry->delete($id)) {
                $this->Session->setFlash('削除しました。', 'flashMessage');
            } else {
                $this->Session->setFlash('削除できませんでした。', 'flashMessage');
            }
            
            $this->redirect('/events/' . $events_detail_id);
        }
    }
    
    public function cast($id = null)
    {
        if ($this->request->is('post')) {
            $id = $this->request->data['EventsArtist']['events_detail_id'];
        }
        if (empty($id)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        $events_detail = $this->EventsDetail->findById($id);
        
        if ($events_detail['EventsDetail']['user_id'] != $this->Auth->user('id')) { //データの作成者とログインユーザが一致しない場合
            $this->redirect('/events/' . $id);
        }
        
        //breadcrumbの設定
        if ($events_detail['Event']['title'] == $events_detail['EventsDetail']['title']) {
            $event_name = $events_detail['Event']['title'];
        } else {
            $event_name = $events_detail['Event']['title'] . ' ' . $events_detail['EventsDetail']['title'];
        }
        $this->set('sub_page', $event_name);
        
        $this->set('events_detail', $events_detail);
        $this->set('events_detail_id', $id);
        
        //出演者一覧の取得
        $cast_lists = $this->EventArtist->find('all', array(
            'conditions' => array(
                'EventArtist.events_detail_id' => $id
            ),
            'order' => array('ArtistProfile.kana' => 'asc')
        ));
        $array_cast = [];
        foreach ($cast_lists as $key => $val) {
            unset($cast_lists[$key]['Event']);
            unset($cast_lists[$key]['EventsDetail']);
            $array_cast[$val['EventArtist']['id']] = $val['EventArtist']['artist_id']; //登録済み出演者のIDを取得しておく
        }
        //アーティスト一覧の取得
        $artist_lists = $this->Artist->find('all', array(
            'conditions' => array(
                'Artist.id !=' => $array_cast
            ),
            'order' => array('Artist.kana' => 'asc')
        ));
        $this->set(compact('cast_lists', 'artist_lists'));
        
        //event_artistsテーブルに保存
        if ($this->request->is('post')) {
            $save_data = [];
            $check_artist = [];
            foreach ($this->request->data['Artist'] as $artist) {
                //既にテーブルに登録されているかを判定
                $check_data = $this->EventArtist->checkExistData($id, $artist['artist_id']);
                if ($check_data == true) {
                    //登録されている場合は処理済みフラグ
                    $check_artist[] = $artist['artist_id'];
                    
                } elseif ($check_data == false) {
                    //登録されていない場合は新規登録
                    $save_data[] = array(
                        'event_id' => $this->request->data['EventsArtist']['event_id'],
                        'events_detail_id' => $id,
                        'artist_id' => $artist['artist_id']
                    );
                }
            }
            if ($this->EventArtist->saveMany($save_data)) {
                $error_flg = 0;
            } else {
                $this->Session->setFlash('データを登録できませんでした。', 'flashMessage');
                $error_flg = 1;
            }
            
            //登録されているデータで未処理のキャストは削除
            foreach ($array_cast as $key => $cast) {
                if (!in_array($cast, $check_artist)) {
                    $this->EventArtist->Behaviors->enable('SoftDelete');
                    if ($this->EventArtist->delete($key)) {
//                        $this->Session->setFlash('削除しました。', 'flashMessage');
                    } else {
                        $this->Session->setFlash('一部のデータを削除できませんでした。', 'flashMessage');
                        $error_flg = 1;
                    }
                }
            }
            
            if ($error_flg == 0) {
                $this->Session->setFlash('出演者を登録しました。', 'flashMessage');
            }
            
            $this->redirect('/events/' . $id);
        }
        
        $this->render('artist');
    }
    
    public function past_lists()
    {
        //breadcrumbの設定
        $this->set('sub_page', '過去のイベント一覧');
        
        //参加済のイベント一覧を取得しておく
        $join_lists = $this->EventUser->getJoinEntries($this->Auth->user('id'));
        //エントリーのみの一覧を取得しておく
        $entry_only_lists = $this->EventsEntry->getOnlyEntries($this->Auth->user('id'));
        
        if ($this->request->query && $this->request->query['search_word']) {
            $search_word = $this->request->query['search_word'];
            $this->set(compact('search_word'));
        } else {
            $search_word = null;
        }
        
        //search wordを整形する
        $search_conditions = $this->Event->searchWordToConditions($search_word);
        
        $this->Paginator->settings = array(
            'conditions' => array(
                'and' => array(
                    array(
                        'and' => $search_conditions
//                        'or' => array(
//                            'Event.title LIKE' => '%' . $search_word . '%',
//                            'EventsDetail.title LIKE' => '%' . $search_word . '%'
//                        )
                    ),
                    'EventsDetail.date <' => date('Y-m-d'),
                    'or' => array(
                        array('EventsDetail.user_id' => $this->Auth->user('id')),
                        array('EventsDetail.id' => $join_lists['events_detail_id']),
                        array('EventsDetail.id' => $entry_only_lists['events_detail_id'])
                    )
                )
            ),
            'order' => array('EventsDetail.date' => 'desc', 'EventsDetail.time_start' => 'asc')
        );
        $event_lists = $this->Paginator->paginate('EventsDetail');
        //イベントのstatusを取得
        foreach ($event_lists as $key => $event_list) {
            $event_lists[$key]['EventsDetail']['status'] = $this->EventsEntry->getEventStatus($event_list['EventsDetail']['id']);
        }
        $this->set(compact('event_lists'));
        
        //未対応のイベント
        $event_undecided_lists = $this->EventsDetail->find('all', array(
            'conditions' => array(
                'and' => array(
                    array(
                        'and' => $search_conditions
//                        'or' => array(
//                            'Event.title LIKE' => '%' . $search_word . '%',
//                            'EventsDetail.title LIKE' => '%' . $search_word . '%'
//                        )
                    ),
                    'EventsDetail.date <' => date('Y-m-d'),
                    'or' => array(
                        array('EventsDetail.user_id' => $this->Auth->user('id')),
//                        array('EventsDetail.id' => $join_lists['events_detail_id']),
                        array('EventsDetail.id' => $entry_only_lists['events_detail_id'])
                    )
                )
            ),
            'order' => array('date' => 'asc', 'EventsDetail.time_start' => 'asc')
        ));
        $excKey = array();
        //イベントのstatusを取得
        foreach ($event_undecided_lists as $key => $event_list) {
            $event_lists[$key]['EventsDetail']['status'] = $this->EventsEntry->getEventStatus($event_list['EventsDetail']['id']);
            if (!$event_lists[$key]['EventsDetail']['status'] == 0) { //検討中以外は対応済みなので除く
                array_push($excKey, $key);
            }
        }
        foreach ($excKey as $key) {
            unset($event_undecided_lists[$key]);
        }
        $this->set(compact('event_undecided_lists'));
    }
    
    public function past_lists_delete($id = null)
    {
        if (empty($id)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        
        if ($this->request->is('post')) {
            $this->EventsDetail->Behaviors->enable('SoftDelete');
            if ($this->EventsDetail->delete($id)) {
                $this->Session->setFlash('削除しました。', 'flashMessage');
            } else {
                $this->Session->setFlash('削除できませんでした。', 'flashMessage');
            }
            
            $this->redirect('/events/past_lists/');
        }
    }
    
    public function all_lists()
    {
        $GUEST_USER_KEY = $this->Option->getOptionKey('GUEST_USER_KEY');
        //ゲストユーザの場合
        if ($this->Auth->user('id') == $GUEST_USER_KEY) {
            $this->Session->setFlash('ゲストユーザは閲覧できません。', 'flashMessage');
            $this->redirect('/events/');
        }
        
        //breadcrumbの設定
        $this->set('sub_page', '公開されているイベント一覧');
        //ページ説明文の設定
        $this->set('description', '全体公開されているイベントの一覧になります。');
        //他ページリンクの設定
        $this->set('page_link', array(
            array('title' => '過去のイベントはこちら', 'url' => '/events/past_lists/')
        ));
        
        if ($this->request->query && $this->request->query['search_word']) {
            $search_word = $this->request->query['search_word'];
            $this->set(compact('search_word'));
        } else {
            $search_word = null;
        }
        
        //search wordを整形する
        $search_conditions = $this->Event->searchWordToConditions($search_word);
        
        $this->Paginator->settings = array(
            'conditions' => array(
                array(
                    'and' => $search_conditions
//                    'or' => array(
//                        'Event.title LIKE' => '%' . $search_word . '%',
//                        'EventsDetail.title LIKE' => '%' . $search_word . '%'
//                    )
                ),
                'EventsDetail.date >=' => date('Y-m-d'),
                'Event.publish' => 1,
                'EventsDetail.user_id !=' => $GUEST_USER_KEY
            ),
            'order' => array('EventsDetail.date' => 'asc', 'EventsDetail.time_start' => 'asc')
        );
        $event_lists = $this->Paginator->paginate('EventsDetail');
        //イベントのstatusを取得
        foreach ($event_lists as $key => $event_list) {
            $event_lists[$key]['EventsDetail']['status'] = $this->EventsEntry->getEventStatus($event_list['EventsDetail']['id']);
        }
        $this->set(compact('event_lists'));
        
        $this->render('event_lists');
    }
    
    public function all_lists_delete($id = null)
    {
        if (empty($id)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        
        if ($this->request->is('post')) {
            $this->EventsDetail->Behaviors->enable('SoftDelete');
            if ($this->EventsDetail->delete($id)) {
                $this->Session->setFlash('削除しました。', 'flashMessage');
            } else {
                $this->Session->setFlash('削除できませんでした。', 'flashMessage');
            }
            
            $this->redirect('/events/all_lists/');
        }
    }
    
    public function search()
    {
        $GUEST_USER_KEY = $this->Option->getOptionKey('GUEST_USER_KEY');
        //ゲストユーザの場合
        if ($this->Auth->user('id') == $GUEST_USER_KEY) {
            $this->Session->setFlash('ゲストユーザは閲覧できません。', 'flashMessage');
            $this->redirect('/events/');
        }
        
        //参加済のイベント一覧を取得しておく
        $join_lists = $this->EventUser->getJoinEntries($this->Auth->user('id'));
        //エントリーのみの一覧を取得しておく
        $entry_only_lists = $this->EventsEntry->getOnlyEntries($this->Auth->user('id'));
        
        //フォームの初期表示数
        $this->set('form_min', 2);
        
        if ($this->request->query && $this->request->query['search_word']) {
            $search_word = $this->request->query['search_word'];
            $this->set(compact('search_word'));
        } else {
            $this->redirect('/events/');
        }
        
        //search wordを整形する
        $search_conditions = $this->Event->searchWordToConditions($search_word);
        
        $this->Paginator->settings = array(
            'conditions' => array(
                array(
                    'and' => $search_conditions
//                    'or' => array(
//                        'Event.title LIKE' => '%' . $search_word . '%',
//                        'EventsDetail.title LIKE' => '%' . $search_word . '%'
//                    )
                ),
                'EventsDetail.date >=' => date('Y-m-d'),
                array(
                    'or' => array(
                        array('EventsDetail.user_id' => $this->Auth->user('id')),
                        array('EventsDetail.id' => $join_lists['events_detail_id']),
                        array('EventsDetail.id' => $entry_only_lists['events_detail_id']),
                        array('Event.publish' => 1)
                    )
                ),
                'EventsDetail.user_id !=' => $GUEST_USER_KEY
            ),
            'order' => array('EventsDetail.date' => 'asc', 'EventsDetail.time_start' => 'asc')
        );
        $event_lists = $this->Paginator->Paginate('EventsDetail');
        //イベントのstatusを取得
        foreach ($event_lists as $key => $event_list) {
            $event_lists[$key]['EventsDetail']['status'] = $this->EventsEntry->getEventStatus($event_list['EventsDetail']['id']);
        }
        
        if (!$event_lists) {
            $this->Session->setFlash('検索に一致するイベントはありません。', 'flashMessage');
        }
        
        $event_genres = $this->EventGenre->find('list'); //プルダウン選択肢用
        $place_lists = $this->Place->find('list', array('conditions' => array('Place.id !=' => 5))); //プルダウン選択肢用
        $this->set(compact('event_lists', 'event_genres', 'place_lists'));
        
        $this->render('index');
    }
    
    public function schedule($user_id = null, $mode = false)
    {
        if (empty($user_id)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        $user = $this->User->find('first', array(
            'conditions' => array('User.id' => $user_id)
        ));
        if (!$user || @$user['User']['json_data'] != 1) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        
        //参加済のイベント一覧を取得しておく
        $join_lists = $this->EventUser->getJoinEntries($user_id);
        //エントリーのみの一覧を取得しておく
        $entry_only_lists = $this->EventsEntry->getOnlyEntries($this->Auth->user('id'));
        
        //mode = allならば全てのイベントを取得
        if ($mode == 'all') {
            $MIN_YEAR_KEY = $this->Option->getOptionKey('MIN_YEAR_KEY');
            $date_from = $MIN_YEAR_KEY . '-01-01';
            $order = 'desc';
        //それ以外は未来のイベントのみ取得
        } else {
            $date_from = date('Y-m-d');
            $order = 'asc';
        }
        
        $event_lists = $this->EventsDetail->find('all', array(
            'conditions' => array(
                'or' => array(
                    array('EventsDetail.user_id' => $user_id),
                    array('EventsDetail.id' => $join_lists['events_detail_id']),
                    array('EventsDetail.id' => $entry_only_lists['events_detail_id'])
                ),
                'EventsDetail.date >=' => $date_from,
                'EventsDetail.deleted !=' => 1
            ),
            'order' => array('EventsDetail.date' => $order, 'EventsDetail.time_start' => $order),
            'contain' => array('Event', 'Place')
        ));
        
        $json_data = [];
        foreach ($event_lists as $key => $event) {
            $status = $this->EventsEntry->getEventStatus($event['EventsDetail']['id'], -1, $user_id);
            if ($status == 3 || $status == 4) { //落選、見送りの場合は除く
                unset($event_lists[$key]);
            } else {
                //データの整形
                $json_data['schedule'][$key]['event_id'] = $event['Event']['id'];
                $json_data['schedule'][$key]['detail_id'] = $event['EventsDetail']['id'];
                $json_data['schedule'][$key]['event_title'] = $event['Event']['title'];
                $json_data['schedule'][$key]['detail_title'] = $event['EventsDetail']['title'];
                $json_data['schedule'][$key]['date'] = $event['EventsDetail']['date'];
                $json_data['schedule'][$key]['time_open'] = $event['EventsDetail']['time_open'];
                $json_data['schedule'][$key]['time_start'] = $event['EventsDetail']['time_start'];
                $json_data['schedule'][$key]['place'] = $event['Place']['name'];
                $json_data['schedule'][$key]['status'] = $status;
            }
        }
        $json_data['schedule'] = array_merge($json_data['schedule']); //キーの振り直し
//        echo'<pre>';print_r($json_data);echo'</pre>';exit;
        
        $this->viewClass = 'Json';
        $this->set('json_data', $json_data);
        $this->set('_serialize', 'json_data');
    }
}
