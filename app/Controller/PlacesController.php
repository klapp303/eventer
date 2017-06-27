<?php

App::uses('AppController', 'Controller');

class PlacesController extends AppController
{
    public $uses = array('Place', 'Prefecture', 'EventUser', 'Event', 'EventsDetail', 'EventsEntry'); //使用するModel
    
    public $components = array('Paginator');
    
    public $paginate = array(
        'limit' => 20,
//        'order' => array('id' => 'asc'),
//        'conditions' => array('id >' => 5) //id=5までは'その他'なので除外する
    );
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'eventer_fullwidth';
//        $this->Place->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
    }
    
    public function index()
    {
        $this->redirect('/places/place_lists/');
    }
    
    public function place_lists()
    {
        $this->set('PLACE_BLOCK_KEY', $this->Option->getOptionKey('PLACE_BLOCK_KEY'));
        
        $this->Paginator->settings = array(
            'limit' => 20,
            'conditions' => array('Place.id >' => $this->Option->getOptionKey('PLACE_OTHER_KEY')) //その他の会場は除外する
        );
        $place_lists = $this->Paginator->paginate('Place');
        //座席数を取得する
        foreach ($place_lists as $key => $val) {
            $place_lists[$key]['Place']['seats'] = $this->Place->getNumberSeats($val['Place']['id']);
        }
        $this->set('place_lists', $place_lists);
    }
    
    public function place_detail($id = null)
    {
        $GUEST_USER_KEY = $this->Option->getOptionKey('GUEST_USER_KEY');
        
        if ($id) { //パラメータにidがあれば詳細ページを表示
            $place_detail = $this->Place->find('first', array(
                'conditions' => array('and' => array(
                        'Place.id' => $id,
                        'Place.id >' => $this->Option->getOptionKey('PLACE_OTHER_KEY') //その他の会場は除外する
                    ))
            ));
            if (!empty($place_detail)) { //データが存在する場合
                //breadcrumbの設定
                $this->set('sub_page', $place_detail['Place']['name']);
                
                //座席数を取得する
                $place_detail['Place']['seats'] = $this->Place->getNumberSeats($id);
                $this->set('place_detail', $place_detail);
                /* 会場に紐付くイベント一覧を取得ここから */
                //参加済のイベント一覧を取得しておく
                $join_lists = $this->EventUser->getJoinEntries($this->Auth->user('id'));
                //エントリーのみの一覧を取得しておく
                $entry_only_lists = $this->EventsEntry->getOnlyEntries($this->Auth->user('id'));
                $event_lists = $this->EventsDetail->find('all', array( //place_detailページのイベント一覧を設定
                    'conditions' => array(
                        'and' => array(
                            'EventsDetail.date >=' => date('Y-m-d'),
                            'EventsDetail.place_id' => $id, //eventsページの一覧から会場で更に絞り込み
                            'or' => array(
                                array('EventsDetail.user_id' => $this->Auth->user('id')),
                                array('EventsDetail.id' => $join_lists['events_detail_id']),
                                array('EventsDetail.id' => $entry_only_lists['events_detail_id']),
                                array('Event.publish' => 1) //公開ステータスを追加
                            ),
                            'EventsDetail.user_id !=' => $GUEST_USER_KEY
                        )
                    ),
                    'order' => array('EventsDetail.date' => 'asc', 'EventsDetail.time_start' => 'asc')
                ));
                //イベントのstatusを取得
                foreach ($event_lists as $key => $event_list) {
                    $event_lists[$key]['EventsDetail']['status'] = $this->EventsEntry->getEventStatus($event_list['EventsDetail']['id']);
                }
                $this->set('event_lists', $event_lists);
                /* 会場に紐付くイベント一覧を取得ここまで */
                
            } else { //データが存在しない場合
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
                
                $this->redirect('/places/place_lists/');
            }
        }
    }
    
    public function add()
    {
        $GUEST_USER_KEY = $this->Option->getOptionKey('GUEST_USER_KEY');
        //ゲストユーザの場合
        if ($this->Auth->user('id') == $GUEST_USER_KEY) {
            $this->Session->setFlash('ゲストユーザは登録できません。', 'flashMessage');
        }
        
        //breadcrumbの設定
        $this->set('sub_page', '会場の登録');
        
        //都道府県の選択肢用
        $this->set('prefecture_lists', $this->Prefecture->find('list'));
        
        $PLACE_OTHER_KEY = $this->Option->getOptionKey('PLACE_OTHER_KEY');
        
        if ($this->request->is('post')) {
            //ゲストユーザの場合
            if ($this->Auth->user('id') == $GUEST_USER_KEY) {
                $this->Session->setFlash('ゲストユーザは登録できません。', 'flashMessage');
                $this->redirect('/places/place_lists/');
            }
            //sort値を追加する
            $place_count = $this->Place->find('count', array(
                'conditions' => array('Place.id >' => $PLACE_OTHER_KEY)
            ));
            $this->request->data['Place']['sort'] = $place_count + $PLACE_OTHER_KEY +1;
            
            $this->Place->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->Place->validates()) { //validate成功の処理
                /* ファイルの保存ここから */
                if ($this->data['Place']['file']['error'] != 4) { //新しいファイルがある場合
                    $upload_dir = '../webroot/files/place/'; //保存するディレクトリ
                    $upload_pass = $upload_dir . basename($this->data['Place']['file']['name']);
                    if (move_uploaded_file($this->data['Place']['file']['tmp_name'], $upload_pass)) { //ファイルを保存
                        $this->request->data['Place']['seat_name'] = $this->data['Place']['file']['name'];
                    } else {
                        $this->Session->setFlash('画像ファイルに不備があります。', 'flashMessage');
                        
                        $this->redirect('/places/place_lists/');
                    }
                }
                /* ファイルの保存ここまで */
                $this->Place->save($this->request->data); //validate成功でsave
                if ($this->Place->save($this->request->data)) {
                    $this->Session->setFlash('登録しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash('登録できませんでした。', 'flashMessage');
                }
            } else { //validate失敗の処理
                $this->Session->setFlash('登録できませんでした。', 'flashMessage');
                
                $this->redirect('/places/place_lists/'); //validate失敗で元ページを表示
            }
            
            $this->redirect('/places/place_lists/');
        }
        
        $this->render('place');
    }
    
    public function edit($id = null)
    {
        if ($this->Auth->user('role') < 3) {
            $this->redirect('/places/place_lists/');
        }
        
        $GUEST_USER_KEY = $this->Option->getOptionKey('GUEST_USER_KEY');
        
        //都道府県の選択肢用
        $this->set('prefecture_lists', $this->Prefecture->find('list'));
        
        if (empty($this->request->data)) {
            $this->request->data = $this->Place->findById($id); //postデータがなければ$idからデータを取得
            if (!empty($this->request->data)) { //データが存在する場合
                //breadcrumbの設定
                $this->set('sub_page', $this->request->data['Place']['name']);
                
                $this->set('id', $id); //viewに渡すために$idをセット
                $this->set('image_name', $this->request->data['Place']['seat_name']); //viewに渡すためにファイル名をセット
                //ゲストユーザの場合
                if ($this->Auth->user('id') == $GUEST_USER_KEY) {
                    $this->Session->setFlash('ゲストユーザは修正できません。', 'flashMessage');
                }
            } else { //データが存在しない場合
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
            }
            
        } else {
            //ゲストユーザの場合
            if ($this->Auth->user('id') == $GUEST_USER_KEY) {
                $this->Session->setFlash('ゲストユーザは修正できません。', 'flashMessage');
                $this->redirect('/places/place_lists/');
            }
            $this->Place->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->Place->validates()) { //validate成功の処理
                /* ファイルの保存ここから */
                if ($this->data['Place']['file']['error'] != 4) { //新しいファイルがある場合
                    $upload_dir = '../webroot/files/place/'; //保存するディレクトリ
                    $upload_pass = $upload_dir . basename($this->data['Place']['file']['name']);
                    if (move_uploaded_file($this->data['Place']['file']['tmp_name'], $upload_pass)) { //ファイルを保存
                        $this->request->data['Place']['seat_name'] = $this->data['Place']['file']['name'];
                        $file = new File(WWW_ROOT . 'files/place/' . $this->request->data['Place']['delete_name']); //前のファイルを削除
                        $file->delete();
                        $file->close();
                    } else {
                        $this->Session->setFlash('画像ファイルに不備があります。', 'flashMessage');
                        
                        $this->redirect('/places/place_lists/');
                    }
                }
                /* ファイルの保存ここまで */
                $this->Place->save($this->request->data); //validate成功でsave
                if ($this->Place->save($id)) {
                    $this->Session->setFlash('修正しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash('修正できませんでした。', 'flashMessage');
                }
                
                $this->redirect('/places/place_lists/');
                
            } else { //validate失敗の処理
                $this->set('id', $this->request->data['Place']['id']); //viewに渡すために$idをセット
                
//                $this->render('index'); //validate失敗でindexを表示
            }
        }
        
        $this->render('place');
    }
    
    public function delete($id = null)
    {
        if (empty($id)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        
        //ゲストユーザの場合
        if ($this->Auth->user('id') == $this->Option->getOptionKey('GUEST_USER_KEY')) {
            $this->Session->setFlash('ゲストユーザは削除できません。', 'flashMessage');
            $this->redirect('/places/place_lists/');
        }
        
        if ($this->request->is('post') and $id > $this->Option->getOptionKey('PLACE_BLOCK_KEY')) { //削除不可に設定したい会場データ
            $this->Place->Behaviors->enable('SoftDelete');
            if ($this->Place->delete($id)) {
                $this->Session->setFlash('削除しました。', 'flashMessage');
            } else {
                $this->Session->setFlash('削除できませんでした。', 'flashMessage');
            }
            
            $this->redirect('/places/place_lists/');
            
        } else {
            $this->Session->setFlash('削除できませんでした。', 'flashMessage');
            
            $this->redirect('/places/place_lists/');
            
        }
    }
    
    public function sort()
    {
        if ($this->Auth->user('role') < 3) {
            $this->redirect('/places/place_lists/');
        }
        
        //breadcrumbの設定
        $this->set('sub_page', '会場の並び替え');
        
        $PLACE_OTHER_KEY = $this->Option->getOptionKey('PLACE_OTHER_KEY');
        
        if (!$this->request->is('post')) {
            $place_lists = $this->Place->find('all', array(
                'conditions' => array('Place.id >' => $PLACE_OTHER_KEY), //その他の会場は除外する
                'fields' => array('Place.id', 'Place.sort', 'Place.name')
            ));
            $this->set('place_lists', $place_lists);
        } else {
            $i = $PLACE_OTHER_KEY;
            foreach ($this->request->data['Place'] as &$place) {
                $i++;
                $place['sort'] = $i;
                //postデータが複数あるので1つずつvalidateする
                $this->Place->set($place); //postデータをModelに渡してvalidate
                if (!$this->Place->validates()) { //validate失敗の処理
                    $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
                    
                    $this->redirect('/places/sort');
                }
            }
            unset($place);
            if ($this->Place->saveMany($this->request->data['Place'])) {
                $this->Session->setFlash('並び順を変更しました。', 'flashMessage');
            } else {
                $this->Session->setFlash('並び順を変更できませんでした。', 'flashMessage');
                
                $this->redirect('/places/sort/');
            }
            
            $this->redirect('/places/place_lists/');
        }
    }
    
    public function search()
    {
        $this->set('PLACE_BLOCK_KEY', $this->Option->getOptionKey('PLACE_BLOCK_KEY'));
        
        if ($this->request->query && $this->request->query['search_word']) {
            $search_word = $this->request->query['search_word'];
            $this->set(compact('search_word'));
        } else {
            $this->redirect('/places/');
        }
        
        $this->Paginator->settings = array(
            'conditions' => array(
                array(
                    'or' => array(
                        'Place.name LIKE' => '%' . $search_word . '%',
                        'Place.access LIKE' => '%' . $search_word . '%',
                    )
                ),
                'Place.id >' => $this->Option->getOptionKey('PLACE_OTHER_KEY') //その他の会場は除外する
            )
        );
        $place_lists = $this->Paginator->paginate('Place');
        
        if (!$place_lists) {
            $this->Session->setFlash('検索に一致する会場はありません。', 'flashMessage');
        }
        
        $this->set('place_lists', $place_lists);
        
        $this->render('place_lists');
    }
    
    public function event_lists($id = null)
    {
        $GUEST_USER_KEY = $this->Option->getOptionKey('GUEST_USER_KEY');
        
        if ($id) { //パラメータにidがあれば会場データを取得
            $place_detail = $this->Place->find('first', array(
                'conditions' => array('and' => array(
                        'Place.id' => $id,
                        'Place.id >' => $this->Option->getOptionKey('PLACE_OTHER_KEY') //その他の会場は除外する
                    ))
            ));
            if (!empty($place_detail)) { //データが存在する場合
                //breadcrumbの設定
                $this->set('sub_page', $place_detail['Place']['name'] . ' のイベント一覧');
                //ページ説明文の設定
                $this->set('description', '会場に紐付くイベント一覧です。<br>過去に開催されたものから開催予定のものまであります。');
                //他ページリンクの設定
                $this->set('page_link', array(
                    array('title' => '会場の詳細に戻る', 'url' => '/places/place_detail/' . $place_detail['Place']['id'])
                ));
                
                if ($this->request->query && $this->request->query['search_word']) {
                    $search_word = $this->request->query['search_word'];
                    $this->set(compact('search_word'));
                } else {
                    $search_word = null;
                }
                
                //search wordを整形する
                $search_conditions = $this->Event->searchWordToConditions($search_word);
                
                /* 会場に紐付くイベント一覧を取得ここから */
                //参加済のイベント一覧を取得しておく
                $join_lists = $this->EventUser->getJoinEntries($this->Auth->user('id'));
                //エントリーのみの一覧を取得しておく
                $entry_only_lists = $this->EventsEntry->getOnlyEntries($this->Auth->user('id'));
                $this->Paginator->settings = array(
                    'conditions' => array(
                        array(
                            'and' => $search_conditions
//                            'or' => array(
//                                'Event.title LIKE' => '%' . $search_word . '%',
//                                'EventsDetail.title LIKE' => '%' . $search_word . '%'
//                            )
                        ),
//                        'EventsDetail.date >=' => date('Y-m-d'),
                        'EventsDetail.place_id' => $id, //eventsページの一覧から会場で更に絞り込み
                        'or' => array(
                            array('EventsDetail.user_id' => $this->Auth->user('id')),
                            array('EventsDetail.id' => $join_lists['events_detail_id']),
                            array('EventsDetail.id' => $entry_only_lists['events_detail_id']),
                            array('Event.publish' => 1) //公開ステータスを追加
                        ),
                        'EventsDetail.user_id !=' => $GUEST_USER_KEY
                    ),
                    'order' => array('EventsDetail.date' => 'desc', 'EventsDetail.time_start' => 'asc')
                );
                $event_lists = $this->Paginator->paginate('EventsDetail');
                //イベントのstatusを取得
                foreach ($event_lists as $key => $event_list) {
                    $event_lists[$key]['EventsDetail']['status'] = $this->EventsEntry->getEventStatus($event_list['EventsDetail']['id']);
                }
                $this->set('event_lists', $event_lists);
                /* 会場に紐付くイベント一覧を取得ここまで */
                
                $this->render('/Events/event_lists');
                
            } else { //データが存在しない場合
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
                
                $this->redirect('/places/place_lists/');
            }
        }
    }
}
