<?php

App::uses('AppController', 'Controller');

class PlacesController extends AppController
{
    public $uses = array('Place', 'Prefecture', 'EventUser', 'Event', 'EventsDetail', 'Option'); //使用するModel
    
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
        $PLACE_BLOCK_OPTION = $this->Option->find('first', array( //オプション値を取得
            'conditions' => array('Option.title' => 'PLACE_BLOCK_KEY'),
            'fields' => 'Option.key'
        ));
        $PLACE_BLOCK_KEY = $PLACE_BLOCK_OPTION['Option']['key'];
        $this->set('PLACE_BLOCK_KEY', $PLACE_BLOCK_KEY);
        
        $PLACE_OTHER_OPTION = $this->Option->find('first', array( //オプション値を取得
            'conditions' => array('Option.title' => 'PLACE_OTHER_KEY'),
            'fields' => 'Option.key'
        ));
        $PLACE_OTHER_KEY = $PLACE_OTHER_OPTION['Option']['key'];
        
        $this->Paginator->settings = array(
            'limit' => 20,
            'conditions' => array('Place.id >' => $PLACE_OTHER_KEY) //その他の会場は除外する
        );
        $place_lists = $this->Paginator->paginate('Place');
        //座席数を取得する
        foreach ($place_lists as $key => $val) {
            $place_lists[$key]['Place']['seats'] = $this->Place->getNumberSeats($val['Place']['id']);
        }
        $this->set('place_lists', $place_lists);
    }
    
    public function place_detail()
    {
        $PLACE_OTHER_OPTION = $this->Option->find('first', array( //オプション値を取得
            'conditions' => array('Option.title' => 'PLACE_OTHER_KEY'),
            'fields' => 'Option.key'
        ));
        $PLACE_OTHER_KEY = $PLACE_OTHER_OPTION['Option']['key'];
        
        if (isset($this->request->params['id']) == true) { //パラメータにidがあれば詳細ページを表示
            $place_detail = $this->Place->find('first', array(
                'conditions' => array('and' => array(
                        'Place.id' => $this->request->params['id'],
                        'Place.id >' => $PLACE_OTHER_KEY //その他の会場は除外する
                    ))
            ));
            if (!empty($place_detail)) { //データが存在する場合
                //座席数を取得する
                $place_detail['Place']['seats'] = $this->Place->getNumberSeats($this->request->params['id']);
                $this->set('place_detail', $place_detail);
                /* 会場に紐付くイベント一覧を取得ここから */
                //参加済のイベント一覧を取得しておく
                $join_lists = $this->EventUser->getJoinEvents($this->Auth->user('id'));
                $event_lists = $this->EventsDetail->find('all', array( //place_detailページのイベント一覧を設定
                    'conditions' => array(
                        'and' => array(
                            'EventsDetail.date >=' => date('Y-m-d'),
                            'EventsDetail.place_id' => $this->request->params['id'], //eventsページの一覧から会場で更に絞り込み
                            'or' => array(
                                array('EventsDetail.user_id' => $this->Auth->user('id')),
                                array('EventsDetail.id' => $join_lists['id']),
                                array('Event.publish' => 1) //公開ステータスを追加
                            )
                        )
                    ),
                    'order' => array('EventsDetail.date' => 'asc', 'EventsDetail.time_start' => 'asc')
                ));
                $this->set('event_lists', $event_lists);
                /* 会場に紐付くイベント一覧を取得ここまで */
                
                $this->layout = 'eventer_normal';
                
            } else { //データが存在しない場合
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
                
                $this->redirect('/places/place_lists/');
            }
        }
    }
    
    public function add()
    {
        $PLACE_OTHER_OPTION = $this->Option->find('first', array( //オプション値を取得
            'conditions' => array('Option.title' => 'PLACE_OTHER_KEY'),
            'fields' => 'Option.key'
        ));
        $PLACE_OTHER_KEY = $PLACE_OTHER_OPTION['Option']['key'];
        
        //都道府県の選択肢用
        $this->set('prefecture_lists', $this->Prefecture->find('list'));
        
        if ($this->request->is('post')) {
            //sort値を追加する
            $place_count = $this->Place->find('count', array(
                'conditions' => array('Place.id >' => $PLACE_OTHER_KEY)
            ));
            $this->request->data['Place']['sort'] = $place_count + $PLACE_OTHER_KEY +1;
            
            $this->Place->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->Place->validates()) { //validate成功の処理
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
        //都道府県の選択肢用
        $this->set('prefecture_lists', $this->Prefecture->find('list'));
        
        if (empty($this->request->data)) {
            $this->request->data = $this->Place->findById($id); //postデータがなければ$idからデータを取得
            if (!empty($this->request->data)) { //データが存在する場合
                $this->set('id', $id); //viewに渡すために$idをセット
            } else { //データが存在しない場合
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
            }
        } else {
            $this->Place->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->Place->validates()) { //validate成功の処理
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
        $PLACE_BLOCK_OPTION = $this->Option->find('first', array( //オプション値を取得
            'conditions' => array('Option.title' => 'PLACE_BLOCK_KEY'),
            'fields' => 'Option.key'
        ));
        $PLACE_BLOCK_KEY = $PLACE_BLOCK_OPTION['Option']['key'];
        
        if (empty($id)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        
        if ($this->request->is('post') and $id > $PLACE_BLOCK_KEY) { //削除不可に設定したい会場データ
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
        $PLACE_OTHER_OPTION = $this->Option->find('first', array( //オプション値を取得
            'conditions' => array('Option.title' => 'PLACE_OTHER_KEY'),
            'fields' => 'Option.key'
        ));
        $PLACE_OTHER_KEY = $PLACE_OTHER_OPTION['Option']['key'];
        
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
        $PLACE_BLOCK_OPTION = $this->Option->find('first', array( //オプション値を取得
            'conditions' => array('Option.title' => 'PLACE_BLOCK_KEY'),
            'fields' => 'Option.key'
        ));
        $PLACE_BLOCK_KEY = $PLACE_BLOCK_OPTION['Option']['key'];
        $this->set('PLACE_BLOCK_KEY', $PLACE_BLOCK_KEY);
        
        $PLACE_OTHER_OPTION = $this->Option->find('first', array( //オプション値を取得
            'conditions' => array('Option.title' => 'PLACE_OTHER_KEY'),
            'fields' => 'Option.key'
        ));
        $PLACE_OTHER_KEY = $PLACE_OTHER_OPTION['Option']['key'];
        
        if ($this->request->query) {
            $search_word = $this->request->query['word'];
        }
        
        $this->Paginator->settings = array(
            'conditions' => array(
                'Place.name LIKE' => '%' . $search_word . '%',
                'Place.id >' => $PLACE_OTHER_KEY //その他の会場は除外する
            )
        );
        $place_lists = $this->Paginator->paginate('Place');
        $this->set('place_lists', $place_lists);
        
        $this->render('place_lists');
    }
}
