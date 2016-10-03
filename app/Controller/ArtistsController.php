<?php

App::uses('AppController', 'Controller');

class ArtistsController extends AppController
{
    public $uses = array('Artist', 'EventArtist', 'EventsDetail'); //使用するModel
    
    public $components = array('Paginator');
    
    public $paginate = array(
        'limit' => 20,
//        'order' => array('kana' => 'asc')
    );
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'eventer_fullwidth';
//        $this->Place->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
    }
    
    public function index()
    {
        $this->redirect('/artists/artist_lists/');
    }
    
    public function artist_lists()
    {
        //出演者タグは多いのでカナの行毎に取得しておく
        $array_kana = array(
            array('name' => 'ア行', 'from' => 'ア', 'to' => 'カ'),
            array('name' => 'カ行', 'from' => 'カ', 'to' => 'サ'),
            array('name' => 'サ行', 'from' => 'サ', 'to' => 'タ'),
            array('name' => 'タ行', 'from' => 'タ', 'to' => 'ナ'),
            array('name' => 'ナ行', 'from' => 'ナ', 'to' => 'ハ'),
            array('name' => 'ハ行', 'from' => 'ハ', 'to' => 'マ'),
            array('name' => 'マ行', 'from' => 'マ', 'to' => 'ヤ'),
            array('name' => 'ヤ・ラ・ワ行', 'from' => 'ヤ', 'to' => 'ン'),
        );
        foreach ($array_kana as $key => $val) {
            ${'artist_lists_' . $key} = $this->Artist->find('all', array(
                'conditions' => array(
                    'Artist.kana >=' => $val['from'],
                    'Artist.kana <' => $val['to']
                ),
                'order' => array('Artist.kana' => 'asc')
            ));
            $this->set(compact('artist_lists_' . $key));
        }
        $this->set('array_kana', $array_kana);
    }
    
    public function artist_detail($id = null)
    {
        if (!$id) {
            redirect('/artists/artist_lists/');
        }
        
        $artist_detail = $this->Artist->find('first', array(
            'conditions' => array(
                'Artist.id' => $id
            )
        ));
        if (!$artist_detail) {
            $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
            
            redirect('/artists/artist_lists/');
        }
        $this->set('artist_detail', $artist_detail);
        
        //関連アーティスト
        $related_artist_lists = [];
        $this->set('related_artist_lists', $related_artist_lists);
        
        //開催予定のイベント
        $event_artists_lists = $this->EventArtist->find('list', array(
            'conditions' => array('EventArtist.artist_id' => $id),
            'fields' => array('EventArtist.events_detail_id')
        ));
        $event_lists = $this->EventsDetail->find('all', array(
            'conditions' => array(
                'EventsDetail.date >=' => date('Y-m-d'),
                'EventsDetail.id' => $event_artists_lists
            ),
            'order' => array('EventsDetail.date' => 'asc', 'EventsDetail.time_start' => 'asc')
        ));
        $this->set('event_lists', $event_lists);
    }
    
    public function add()
    {
        $GUEST_USER_KEY = $this->getOptionKey('GUEST_USER_KEY');
        //ゲストユーザの場合
        if ($this->Auth->user('id') == $GUEST_USER_KEY) {
            $this->Session->setFlash('ゲストユーザは登録できません。', 'flashMessage');
        }
        
        if ($this->request->is('post')) {
            //ゲストユーザの場合
            if ($this->Auth->user('id') == $GUEST_USER_KEY) {
                $this->Session->setFlash('ゲストユーザは登録できません。', 'flashMessage');
                $this->redirect('/artists/artist_lists/');
            }
            
            //既に登録されている場合は登録しない
            $duplicate_data = $this->Artist->find('first', array(
                'conditions' => array('Artist.name' => $this->request->data['Artist']['name'])
            ));
            if ($duplicate_data) {
                $this->Session->setFlash('既に ' . $this->request->data['Artist']['name'] . ' は登録されています。<br>見つからない場合、カナが間違って登録されているかもしれません。', 'flashMessage');
                $this->redirect('/artists/artist_lists/');
            }
            
            $this->Artist->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->Artist->validates()) { //validate成功の処理
                $this->Artist->save($this->request->data); //validate成功でsave
                if ($this->Artist->save($this->request->data)) {
                    $this->Session->setFlash($this->request->data['Artist']['name'] . ' を登録しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash($this->request->data['Artist']['name'] . ' を登録できませんでした。', 'flashMessage');
                }
            } else { //validate失敗の処理
                $this->Session->setFlash($this->request->data['Artist']['name'] . ' を登録できませんでした。', 'flashMessage');
                
                $this->redirect('/artists/artist_lists/'); //validate失敗で元ページを表示
            }
            
            $this->redirect('/artists/artist_lists/');
        }
    }
    
    public function edit($id = null)
    {
        if ($this->Auth->user('role') < 3) {
            $this->redirect('/artists/artist_lists/');
        }
        
        $GUEST_USER_KEY = $this->getOptionKey('GUEST_USER_KEY');
        
        if (empty($this->request->data)) {
            $this->request->data = $this->Artist->findById($id); //postデータがなければ$idからデータを取得
            if (!empty($this->request->data)) { //データが存在する場合
                $this->set('id', $id); //viewに渡すために$idをセット
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
                $this->redirect('/artists/artist_lists/');
            }
            $this->Artist->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->Artist->validates()) { //validate成功の処理
                $this->Artist->save($this->request->data); //validate成功でsave
                if ($this->Artist->save($id)) {
                    $this->Session->setFlash($this->request->data['Artist']['name'] . ' を修正しました。', 'flashMessage');
                    
                    $this->redirect('/artists/artist_detail/' . $id);
                    
                } else {
                    $this->Session->setFlash('修正できませんでした。', 'flashMessage');
                }
                
                $this->redirect('/artists/artist_lists/');
                
            } else { //validate失敗の処理
                $this->set('id', $this->request->data['Artist']['id']); //viewに渡すために$idをセット
                
//                $this->render('index'); //validate失敗でindexを表示
            }
        }
    }
    
    /*public function delete($id = null)
    {
        if (empty($id)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        
        //ゲストユーザの場合
        if ($this->Auth->user('id') == $this->getOptionKey('GUEST_USER_KEY')) {
            $this->Session->setFlash('ゲストユーザは削除できません。', 'flashMessage');
            $this->redirect('/places/place_lists/');
        }
        
        if ($this->request->is('post') and $id > $this->getOptionKey('PLACE_BLOCK_KEY')) { //削除不可に設定したい会場データ
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
    }*/
    
    /*public function search()
    {
        $this->set('PLACE_BLOCK_KEY', $this->getOptionKey('PLACE_BLOCK_KEY'));
        
        if ($this->request->query && $this->request->query['word']) {
            $search_word = $this->request->query['word'];
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
                'Place.id >' => $this->getOptionKey('PLACE_OTHER_KEY') //その他の会場は除外する
            )
        );
        $place_lists = $this->Paginator->paginate('Place');
        
        if (!$place_lists) {
            $this->Session->setFlash('検索に一致する会場はありません。', 'flashMessage');
        }
        
        $this->set('place_lists', $place_lists);
        
        $this->render('place_lists');
    }*/
}
