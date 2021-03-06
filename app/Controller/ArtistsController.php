<?php

App::uses('AppController', 'Controller');

class ArtistsController extends AppController
{
    public $uses = array('Artist', 'EventArtist', 'EventUser', 'Event', 'EventsDetail', 'EventsEntry', 'Favorite', 'JsonData'); //使用するModel
    
    public $components = array('Paginator');
    
    public $paginate = array(
        'limit' => 20,
//        'order' => array('kana' => 'asc')
    );
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'eventer_normal';
//        $this->Place->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
    }
    
    public function index()
    {
        $this->redirect('/artists/artist_lists/');
    }
    
    public function artist_lists()
    {
        //アーティストタグは多いのでカナの行毎に取得しておく
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
        
        //breadcrumbの設定
        $this->set('sub_page', $artist_detail['Artist']['name']);
        //画像
        if ($artist_detail['Artist']['image_name']) {
            $artist_detail['Artist']['alt_name'] = $artist_detail['Artist']['name'];
        } else {
            $artist_detail['Artist']['image_name'] = '../no_image.jpg';
            $artist_detail['Artist']['alt_name'] = '';
        }
        //公式サイト
        $artist_detail['Artist']['link_urls'] = $this->Artist->getArrayLinkUrls($artist_detail['Artist']['link_urls']);
        //関連アーティスト
        $artist_detail['Artist']['related_artists'] = $this->Artist->getArrayRelatedArtists($artist_detail['Artist']['related_artists_id']);
        unset($artist_detail['Artist']['related_artists_id']);
        $this->set('artist_detail', $artist_detail);
        
        //イベント参加データ
        $conditions = $this->Artist->getEventsConditionsFromArtist($id, false, false, true);
        $event_all_lists = $this->EventsDetail->find('all', array(
            'conditions' => $conditions,
            'order' => array('EventsDetail.date' => 'asc', 'EventsDetail.time_start' => 'asc')
        ));
        $event_report = $this->EventsEntry->formatEventsReport($event_all_lists, $id);
        $this->set('event_report', $event_report);
        
        //開催予定のイベント
        $conditions = $this->Artist->getEventsConditionsFromArtist($id);
        $event_lists = $this->EventsDetail->find('all', array(
            'conditions' => $conditions,
            'order' => array('EventsDetail.date' => 'asc', 'EventsDetail.time_start' => 'asc')
        ));
        //イベントのstatusを取得
        foreach ($event_lists as $key => $event_list) {
            $event_lists[$key]['EventsDetail']['status'] = $this->EventsEntry->getEventStatus($event_list['EventsDetail']['id']);
        }
        $this->set('event_lists', $event_lists);
    }
    
    public function add()
    {
        $GUEST_USER_KEY = $this->Option->getOptionKey('GUEST_USER_KEY');
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
                //validate成功でsave
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
        
        $GUEST_USER_KEY = $this->Option->getOptionKey('GUEST_USER_KEY');
        
        if (empty($this->request->data)) {
            $this->request->data = $this->Artist->findById($id); //postデータがなければ$idからデータを取得
            if (!empty($this->request->data)) { //データが存在する場合
                $this->set('id', $id); //viewに渡すために$idをセット
                
                //ゲストユーザの場合
                if ($this->Auth->user('id') == $GUEST_USER_KEY) {
                    $this->Session->setFlash('ゲストユーザは修正できません。', 'flashMessage');
                }
                
                //breadcrumbの設定
                $this->set('sub_page', $this->request->data['Artist']['name']);
                
                $this->set('image_name', $this->request->data['Artist']['image_name']); //viewに渡すためにファイル名をセット
                /* 公式サイトのデータを整形ここから */
                $array_link_urls = $this->Artist->getArrayLinkUrls($this->request->data['Artist']['link_urls']);
                $this->request->data['Artist']['link_urls'] = $array_link_urls; 
                /* 公式サイトのデータを整形ここまで */
                /* 関連アーティストのデータを整形ここから */
                $array_related_id = $this->Artist->getArrayRelatedArtists($this->request->data['Artist']['related_artists_id']);
                $this->set('related_lists', $array_related_id);
                $related_lists = array($id); //自身のidは除外する
                foreach ($array_related_id as $val) {
                    $related_lists[] = $val['artist_id'];
                }
                $artist_lists = $this->Artist->find('list', array(
                    'conditions' => array(
                        'Artist.id !=' => $related_lists
                    ),
                    'fields' => array('Artist.name'),
                    'order' => array('Artist.kana' => 'asc')
                ));
                $this->set('artist_lists', $artist_lists);
                /* 関連アーティストのデータを整形ここまで */
            } else { //データが存在しない場合
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
            }
            
        } else {
            //ゲストユーザの場合
            if ($this->Auth->user('id') == $GUEST_USER_KEY) {
                $this->Session->setFlash('ゲストユーザは修正できません。', 'flashMessage');
                $this->redirect('/artists/artist_lists/');
            }
            
            /* 公式サイトのデータを整形ここから */
            $link_url_data = '';
            foreach (@$this->request->data['Artist']['link_urls'] as $val) {
                if ($val['link_url']) {
                    $link_url_data = $link_url_data . $val['link_url'] . ',';
                }
            }
            if ($link_url_data) {
                $link_url_data = rtrim($link_url_data, ',');
            } else {
                $link_url_data = null; //空白で登録させないため
            }
            $this->request->data['Artist']['link_urls'] = $link_url_data;
            /* 公式サイトのデータを整形ここまで */
            /* 関連アーティストのデータを整形ここから */
            $related_artist_data = '';
            foreach (@$this->request->data['Artist']['related_artists_id'] as $val) {
                $related_artist_data = $related_artist_data . $val['artist_id'] . ',';
            }
            if ($related_artist_data) {
                $related_artist_data = rtrim($related_artist_data, ',');
            } else {
                $related_artist_data = null; //空白で登録させないため
            }
            $this->request->data['Artist']['related_artists_id'] = $related_artist_data;
            /* 関連アーティストのデータを整形ここまで */
            $this->Artist->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->Artist->validates()) { //validate成功の処理
                /* ファイルの保存ここから */
                if ($this->data['Artist']['file']['error'] != 4) { //新しいファイルがある場合
                    $upload_dir = '../webroot/files/artist/'; //保存するディレクトリ
                    $upload_pass = $upload_dir . basename($this->data['Artist']['file']['name']);
                    if (move_uploaded_file($this->data['Artist']['file']['tmp_name'], $upload_pass)) { //ファイルを保存
                        $this->request->data['Artist']['image_name'] = $this->data['Artist']['file']['name'];
                        $file = new File(WWW_ROOT . 'files/artist/' . $this->request->data['Artist']['delete_name']); //前のファイルを削除
                        $file->delete();
                        $file->close();
                    } else {
                        $this->Session->setFlash('画像ファイルに不備があります。', 'flashMessage');
                        
                        $this->redirect('/artists/edit/' . $id);
                    }
                }
                /* ファイルの保存ここまで */
                //validate成功でsave
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
    
    public function delete($id = null)
    {
        if (empty($id)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        
        //ゲストユーザの場合
        if ($this->Auth->user('id') == $this->Option->getOptionKey('GUEST_USER_KEY')) {
            $this->Session->setFlash('ゲストユーザは削除できません。', 'flashMessage');
            $this->redirect('/artists/artist_lists/');
        }
        
        if ($this->request->is('post')) {
            $this->Artist->Behaviors->enable('SoftDelete');
            if ($this->Artist->delete($id)) {
                $this->Session->setFlash('削除しました。', 'flashMessage');
            } else {
                $this->Session->setFlash('削除できませんでした。', 'flashMessage');
            }
            
            $this->redirect('/artists/artist_lists/');
            
        } else {
            $this->Session->setFlash('削除できませんでした。', 'flashMessage');
            
            $this->redirect('/artists/artist_lists/');
            
        }
    }
    
    public function event_lists($id = null)
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
        
        //breadcrumbの設定
        $this->set('sub_page', $artist_detail['Artist']['name'] . ' のイベント一覧');
        //ページ説明文の設定
        $this->set('description', 'アーティストに紐付くイベント一覧です。<br>過去に開催されたものから開催予定のものまであります。');
        //他ページリンクの設定
        $this->set('page_link', array(
            array('title' => 'アーティストの詳細に戻る', 'url' => '/artists/artist_detail/' . $artist_detail['Artist']['id'])
        ));
        
        if ($this->request->query && $this->request->query['search_word']) {
            $search_word = $this->request->query['search_word'];
            $this->set(compact('search_word'));
        } else {
            $search_word = null;
        }
        
        //search wordを整形する
        $search_conditions = $this->Event->searchWordToConditions($search_word);
        
        //アーティストに紐付くイベント
        $conditions = $this->Artist->getEventsConditionsFromArtist($id, false, $search_conditions, true);
        $this->Paginator->settings = array(
            'conditions' => $conditions,
            'order' => array('EventsDetail.date' => 'desc', 'EventsDetail.time_start' => 'asc')
        );
        $event_lists = $this->Paginator->paginate('EventsDetail');
        //イベントのstatusを取得
        foreach ($event_lists as $key => $event_list) {
            $event_lists[$key]['EventsDetail']['status'] = $this->EventsEntry->getEventStatus($event_list['EventsDetail']['id']);
        }
        $this->set('event_lists', $event_lists);
        
        $this->render('/Events/event_lists');
    }
    
    public function compare_lists()
    {
        //breadcrumbの設定
        $this->set('sub_page', 'イベント参加データ一覧');
        
        //アーティスト別イベント参加データ一覧を取得
        $json_data = $this->JsonData->find('first', array(
            'conditions' => array(
                'JsonData.title' => 'artists_compare_lists',
                'JsonData.user_id' => $this->Auth->user('id')
            ),
            'fields' => 'JsonData.json_data'
        ));
        $event_reports = json_decode($json_data['JsonData']['json_data'], true);
        //お気に入り登録アーティストを取得しておく
        $favorites = $this->Favorite->find('list', array(
            'conditions' => array(
                'Favorite.user_id' => $this->Auth->user('id')
            ),
            'fields' => array('Favorite.artist_id')
        ));
        //ワンマンに複数参加したアーティストだけ表示する
        foreach ($event_reports as $key => $val) {
            if ($val['oneman']['count_join'] < 2 && in_array($val['Artist']['id'], $favorites) == false) {
                unset($event_reports[$key]);
            }
        }
        
        //paginatorは独自に設定する
        $params = $this->params['named'];
        if (@$params['sort'] && @$params['direction']) {
            $sort = $params['sort'];
            $array_sort = explode('_', $sort, 2);
            $direction = $params['direction'];
            //ソートを実行
            foreach ($event_reports as $key => $val) {
                $sort_reports[$key] = $val[$array_sort[0]][$array_sort[1]];
            }
            if ($direction == 'asc') {
                array_multisort($sort_reports, SORT_ASC, $event_reports);
            } else {
                array_multisort($sort_reports, SORT_DESC, $event_reports);
            }
        }
        
        $this->set('event_reports', $event_reports);
    }
    
    public function compare_lists_update()
    {
        //アーティスト別イベント参加データ一覧を更新
        $compare_lists = $this->Artist->getComparelist($this->Auth->user('id'));
        if ($this->JsonData->saveDataJson($compare_lists, 'artists_compare_lists')) {
            $this->Session->setFlash('イベント参加データ一覧を更新しました。', 'flashMessage');
        } else {
            $this->Session->setFlash('イベント参加データ一覧を更新できませんでした。', 'flashMessage');
        }
        
        $this->redirect('/artists/compare_lists');
    }
}
