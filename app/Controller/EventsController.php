<?php

App::uses('AppController', 'Controller');

class EventsController extends AppController {

  public $uses = array('EventUser', 'Event', 'EventsDetail', 'EventsEntry', 'EventGenre', 'EntryGenre', 'User', 'Place'); //使用するModel

  public $components = array('Paginator', 'Search.Prg');
  public $paginate = array(
      'limit' => 20,
      'order' => array('id' => 'desc')
  );

  public function beforeFilter() {
      parent::beforeFilter();
      $this->layout = 'eventer_fullwidth';
      //$this->Event->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
  }

  public function index() {
      //参加済のイベント一覧を取得しておく
      $join_lists = $this->EventUser->getJoinEvents($this->Auth->user('id'));
      
      $this->Paginator->settings = array( //eventsページのイベント一覧を設定
          'conditions' => array(
              'and' => array(
                  'EventsDetail.date >=' => date('Y-m-d'),
                  'or' => array(
                      array('EventsDetail.user_id' => $this->Auth->user('id')),
                      array('EventsDetail.id' => $join_lists['id'])
                  )
              )
          ),
          'order' => array('EventsDetail.date' => 'asc', 'EventsDetail.time_start' => 'asc')
      );
      $event_lists = $this->Paginator->paginate('EventsDetail');
      foreach ($event_lists AS &$event_list) {
        $event_list['EventsDetail']['status'] = $this->EventsEntry->getEventStatus($event_list['EventsDetail']['id']);
      }
      unset($event_list);
      $event_genres = $this->EventGenre->find('list'); //プルダウン選択肢用
      $place_lists = $this->Place->find('list'); //プルダウン選択肢用
      $this->set(compact('event_lists', 'event_genres', 'place_lists'));
  
      if (isset($this->request->params['id']) == TRUE) { //パラメータにidがあれば詳細ページを表示
        $event_detail = $this->EventsDetail->find('first', array(
            'conditions' => array(
                'and' => array(
                    'EventsDetail.id' => $this->request->params['id'],
                    'or' => array(
                        array('EventsDetail.user_id' => $this->Auth->user('id')),
                        array('EventsDetail.id' => $join_lists['id']),
                        array('Event.publish' => 1)
                    )
                )
            )
        ));
        if (!empty($event_detail)) { //データが存在する場合
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
                          array('EventsDetail.id' => $join_lists['id']),
                          array('Event.publish' => 1)
                      )
                  )
              ),
              'order' => array('EventsDetail.date' => 'asc', 'EventsDetail.time_start' => 'asc')
          ));
          $this->set(compact('event_detail', 'entry_lists', 'other_lists'));
          $this->render('event');
        } else { //データが存在しない場合
          $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
        }
      }
  }

  public function add() {
      if ($this->request->is('post')) {
        //eventsテーブルに保存
        $dataEvent['Event'] = $this->request->data['Event'];
        $this->Event->set($dataEvent); //postデータをModelに渡してvalidate
        if ($this->Event->validates()) { //validate成功の処理
          if ($this->Event->save($dataEvent)) { //validate成功でsave
            //$this->Session->setFlash('登録しました。', 'flashMessage');
          } else {
            $this->Session->setFlash($dataEvent['Event']['title'].' を登録できませんでした。', 'flashMessage');
            $this->redirect('/events/');
          }
        } else { //validate失敗の処理
          $this->Session->setFlash($dataEvent['Event']['title'].' の入力に不備があります。', 'flashMessage');
          $this->redirect('/events/');
        }
        
        //events_detailsテーブルに保存
        /* データをテーブルの構造に合わせて加工ここから */
        $saveEvent = $this->Event->find('first', array('order' => array('Event.id' => 'desc')));
        $dataDetails['EventsDetail'] = $this->request->data['EventsDetail'];
        foreach ($dataDetails['EventsDetail'] AS $key => &$dataDetail) {
          $dataDetail['event_id'] = $saveEvent['Event']['id'];
          $dataDetail['user_id'] = $saveEvent['Event']['user_id'];
          if ($dataDetail['time_open_null'] == 1) {
            $dataDetail['time_open'] = null;
          }
          if ($dataDetail['time_start_null'] == 1) {
            $dataDetail['time_start'] = null;
          }
          if (!$dataDetail['title']) {
            unset($dataDetails['EventsDetail'][$key]);
          }
        }
        unset($dataDetail); //foreachの参照渡しでのデータ書き換えを回避
        /* データをテーブルの構造に合わせて加工ここまで */
        foreach ($dataDetails['EventsDetail'] AS $dataDetail) {
          //postデータが複数あるので1つずつvalidateする
          $this->EventsDetail->set($dataDetail); //postデータをModelに渡してvalidate
          if (!$this->EventsDetail->validates()) { //validate失敗の処理
            $this->Session->setFlash($dataDetail['EventsDetail']['title'].' の入力に不備があります。', 'flashMessage');
            $this->redirect('/events/');
          }
        }
        if ($this->EventsDetail->saveMany($dataDetails['EventsDetail'])) { //validate成功でsave
          $message = '';
          foreach ($dataDetails['EventsDetail'] AS $dataDetail) {
            $message .= '<br>'.$dataDetail['title'];
          }
          //$message = ltrim($message, '<br>');
          $this->Session->setFlash($dataEvent['Event']['title'].' の'.$message.' を登録しました。', 'flashMessage');
        } else {
          $this->Session->setFlash($dataEvent['Event']['title'].' を登録できませんでした。', 'flashMessage');
        }
      }
  
      $this->redirect('/events/');
  }

  public function edit($id = null) {
      //参加済のイベント一覧を取得しておく
      $join_lists = $this->EventUser->getJoinEvents($this->Auth->user('id'));
      
      $this->Paginator->settings = array( //eventsページのイベント一覧を設定
          'conditions' => array(
              'and' => array(
                  'EventsDetail.date >=' => date('Y-m-d'),
                  'or' => array(
                      array('EventsDetail.user_id' => $this->Auth->user('id')),
                      array('EventsDetail.id' => $join_lists['id'])
                  )
              )
          ),
          'order' => array('EventsDetail.date' => 'asc', 'EventsDetail.time_start' => 'asc')
      );
      $event_lists = $this->Paginator->paginate('EventsDetail');
      foreach ($event_lists AS &$event_list) {
        $event_list['EventsDetail']['status'] = $this->EventsEntry->getEventStatus($event_list['EventsDetail']['id']);
      }
      unset($event_list);
      $event_genres = $this->EventGenre->find('list'); //プルダウン選択肢用
      $place_lists = $this->Place->find('list'); //プルダウン選択肢用
      $this->set(compact('event_lists', 'event_genres', 'place_lists'));
  
      if (empty($this->request->data)) {
        $this->request->data = $this->Event->findById($id); //postデータがなければ$idからデータを取得
        if (!empty($this->request->data)) { //データが存在する場合
          if ($this->request->data['Event']['user_id'] == $this->Auth->user('id')) { //データの作成者とログインユーザが一致する場合
            foreach ($this->request->data['EventsDetail'] AS &$events_detail) {
              if ($events_detail['time_open'] == null) {
                $events_detail['time_open_null'] = 1;
              }
              if ($events_detail['time_start'] == null) {
                $events_detail['time_start_null'] = 1;
              }
            }
            unset($events_detail);
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
        //eventsテーブルに保存
        $dataEvent['Event'] = $this->request->data['Event'];
        $this->Event->set($dataEvent); //postデータをModelに渡してvalidate
        if ($this->Event->validates()) { //validate成功の処理
          if ($this->Event->save($dataEvent)) { //validate成功でsave
            //$this->Session->setFlash('登録しました。', 'flashMessage');
          } else {
            $this->Session->setFlash($dataEvent['Event']['title'].' を修正できませんでした。', 'flashMessage');
            $this->redirect('/events/');
          }
        } else { //validate失敗の処理
          $this->Session->setFlash($dataEvent['Event']['title'].' の入力に不備があります。', 'flashMessage');
          $this->redirect('/events/');
        }
        
        //events_detailsテーブルに保存
        /* データをテーブルの構造に合わせて加工ここから */
        $dataDetails['EventsDetail'] = $this->request->data['EventsDetail'];
        foreach ($dataDetails['EventsDetail'] AS $key => &$dataDetail) {
          $dataDetail['event_id'] = $dataEvent['Event']['id'];
          $dataDetail['user_id'] = $this->Auth->user('id');
          if ($dataDetail['time_open_null'] == 1) {
            $dataDetail['time_open'] = null;
          }
          if ($dataDetail['time_start_null'] == 1) {
            $dataDetail['time_start'] = null;
          }
          if (!$dataDetail['title']) {
            unset($dataDetails['EventsDetail'][$key]);
          }
        }
        unset($dataDetail); //foreachの参照渡しでのデータ書き換えを回避
        /* データをテーブルの構造に合わせて加工ここまで */
        foreach ($dataDetails['EventsDetail'] AS $dataDetail) {
          //postデータが複数あるので1つずつvalidateする
          $this->EventsDetail->set($dataDetail); //postデータをModelに渡してvalidate
          if (!$this->EventsDetail->validates()) { //validate失敗の処理
            $this->Session->setFlash($dataDetail['EventsDetail']['title'].' の入力に不備があります。', 'flashMessage');
            $this->redirect('/events/');
          }
        }
        if ($this->EventsDetail->saveMany($dataDetails['EventsDetail'])) { //validate成功でsave
          //開演日時に変更があればevents_entriesテーブルを更新
          foreach ($dataDetails['EventsDetail'] AS $dataDetail) {
            if ($dataDetail['time_start']) {
              $date_event = $dataDetail['date']['year'].'-'.$dataDetail['date']['month'].'-'.$dataDetail['date']['day'].' '.$dataDetail['time_start']['hour'].':'.$dataDetail['time_start']['min'];
            }
            if (!$dataDetail['time_start'] || $dataDetail['time_start_null'] == 1) {
              $date_event = $dataDetail['date']['year'].'-'.$dataDetail['date']['month'].'-'.$dataDetail['date']['day'];
            }
            $entryId = $this->EventsEntry->find('list', array(
                'conditions' => array('EventsEntry.events_detail_id' => $dataDetail['id']),
                'fields' => 'EventsEntry.id'
            ));
            foreach ($entryId AS $id) {
              $this->EventsEntry->id = $id;
              $this->EventsEntry->savefield('date_event', $date_event);
            }
          }
          $message = '';
          foreach ($dataDetails['EventsDetail'] AS $dataDetail) {
            $message .= '<br>'.$dataDetail['title'];
          }
          //$message = ltrim($message, '<br>');
          $this->Session->setFlash($dataEvent['Event']['title'].' の'.$message.' を登録、修正しました。', 'flashMessage');
          $this->redirect('/events/');
        } else {
          $this->Session->setFlash($dataEvent['Event']['title'].' を登録、修正できませんでした。', 'flashMessage');
        }
      }
  
      $this->render('index');
  }

  public function delete($id = null) {
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

  public function entry_add($id = null) {
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
        $this->redirect('/event/'.$id);
      }
      $this->set('events_detail', $events_detail);
      $this->set('entry_genres', $this->EntryGenre->find('list'));
      $this->set('events_detail_id', $id);
      
      if ($this->request->is('post')) {
        //events_entriesテーブルに保存
        /* データをテーブルの構造に合わせて加工ここから */
        foreach ($entryDateColumn AS $column) {
          if ($this->request->data['EventsEntry'][$column.'_null'] == 1) {
            $this->request->data['EventsEntry'][$column] = null;
          }
        }
        /* データをテーブルの構造に合わせて加工ここまで */
        $this->EventsEntry->set($this->request->data); //postデータをModelに渡してvalidate
        if ($this->EventsEntry->validates()) { //validate成功の処理
          if ($this->EventsEntry->save($this->request->data)) { //validate成功でsave
            $this->Session->setFlash($this->request->data['EventsEntry']['title'].' を登録しました。', 'flashMessage');
          } else {
            $this->Session->setFlash($this->request->data['EventsEntry']['title'].' を登録できませんでした。', 'flashMessage');
          }
        } else { //validate失敗の処理
          $this->Session->setFlash($this->request->data['EventsEntry']['title'].' の入力に不備があります。', 'flashMessage');
        }
        $this->redirect('/event/'.$this->request->data['EventsEntry']['events_detail_id']);
      }
  
      $this->render('entry');
  }

  public function entry_edit($id = null) {
      //エントリーの日付カラムを定義しておく
      $entryDateColumn = $this->EventsEntry->getDateColumn();
      
      if (empty($this->request->data)) {
        $this->set('entry_genres', $this->EntryGenre->find('list'));
        
        $this->request->data = $this->EventsEntry->findById($id); //postデータがなければ$idからデータを取得
        $this->set('events_detail_id', $this->request->data['EventsEntry']['events_detail_id']);
        if (!empty($this->request->data)) { //データが存在する場合
          if ($this->request->data['EventsEntry']['user_id'] == $this->Auth->user('id')) { //データの作成者とログインユーザが一致する場合
            foreach ($entryDateColumn AS $column) {
              if ($this->request->data['EventsEntry'][$column] == null) {
                $this->request->data['EventsEntry'][$column.'_null'] = 1;
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
        foreach ($entryDateColumn AS $column) {
          if ($this->request->data['EventsEntry'][$column.'_null'] == 1) {
            $this->request->data['EventsEntry'][$column] = null;
          }
        }
        /* データをテーブルの構造に合わせて加工ここまで */
        $this->EventsEntry->set($this->request->data); //postデータをModelに渡してvalidate
        if ($this->EventsEntry->validates()) { //validate成功の処理
          if ($this->EventsEntry->save($this->request->data)) { //validate成功でsave
            $this->Session->setFlash($this->request->data['EventsEntry']['title'].' を修正しました。', 'flashMessage');
          } else {
            $this->Session->setFlash($this->request->data['EventsEntry']['title'].' を修正できませんでした。', 'flashMessage');
          }
        } else { //validate失敗の処理
          $this->Session->setFlash($this->request->data['EventsEntry']['title'].' の入力に不備があります。', 'flashMessage');
        }
        $this->redirect('/event/'.$this->request->data['EventsEntry']['events_detail_id']);
      }
  
      $this->render('entry');
  }

  public function entry_delete($id = null, $events_detail_id = null) {
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
        $this->redirect('/event/'.$events_detail_id);
      }
  }

  public function past_lists() {
      //参加済のイベント一覧を取得しておく
      $join_lists = $this->EventUser->getJoinEvents($this->Auth->user('id'));
      
      $this->Paginator->settings = array(
          'conditions' => array(
              'and' => array(
                  'EventsDetail.date <' => date('Y-m-d'),
                  'or' => array(
                      array('EventsDetail.user_id' => $this->Auth->user('id')),
                      array('EventsDetail.id' => $join_lists['id'])
                  )
              )
          ),
          'order' => array('EventsDetail.date' => 'desc', 'EventsDetail.time_start' => 'asc')
      );
      $event_lists = $this->Paginator->paginate('EventsDetail');
      foreach ($event_lists AS &$event_list) {
        $event_list['EventsDetail']['status'] = $this->EventsEntry->getEventStatus($event_list['EventsDetail']['id']);
      }
      unset($event_list);
      $this->set(compact('event_lists'));
      
      //未対応のイベント
      $event_undecided_lists = $this->EventsDetail->find('all', array(
          'conditions' => array(
              'and' => array(
                  'EventsDetail.date <' => date('Y-m-d'),
                  'or' => array(
                      array('EventsDetail.user_id' => $this->Auth->user('id')),
                      //array('EventsDetail.id' => $join_lists['id'])
                  )
              )
          ),
          'order' => array('date' => 'asc', 'EventsDetail.time_start' => 'asc')
      ));
      $excKey = array();
      foreach ($event_undecided_lists AS $key => &$event_list) {
        $event_list['EventsDetail']['status'] = $this->EventsEntry->getEventStatus($event_list['EventsDetail']['id']);
        if (!$event_list['EventsDetail']['status'] == 0) { //検討中以外は除く
          array_push($excKey, $key);
        }
      }
      unset($event_list);
      foreach ($excKey AS $key) {
        unset($event_undecided_lists[$key]);
      }
      $this->set(compact('event_undecided_lists'));
  }

  public function past_lists_delete($id = null) {
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

  public function all_lists() {
      $this->Paginator->settings = array(
          'conditions' => array(
              'EventsDetail.date >=' => date('Y-m-d'),
              'Event.publish' => 1
          ),
          'order' => array('EventsDetail.date' => 'asc', 'EventsDetail.time_start' => 'asc')
      );
      $event_lists = $this->Paginator->paginate('EventsDetail');
      foreach ($event_lists AS &$event_list) {
        $event_list['EventsDetail']['status'] = $this->EventsEntry->getEventStatus($event_list['EventsDetail']['id']);
      }
      unset($event_list);
      $this->set(compact('event_lists'));
  }

  public function all_lists_delete($id = null) {
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

  public function search() {
      //参加済のイベント一覧を取得しておく
      $join_lists = $this->EventUser->getJoinEvents($this->Auth->user('id'));
      
      if ($this->request->query) {
        $search_title = $this->request->query['title'];
      }
      
      $event_lists = $this->EventsDetail->find('all', array(
          'conditions' => array(
              array(
                  'or' => array(
                      'Event.title LIKE' => '%'.$search_title.'%',
                      'EventsDetail.title LIKE' => '%'.$search_title.'%'
                  )
              ),
              array(
                  'or' => array(
                      array('Event.publish' => 1),
                      array('EventsDetail.id' => $join_lists['id'])
                  )
              )
          ),
          'order' => array('EventsDetail.date' => 'asc', 'EventsDetail.time_start' => 'asc')
      ));
      foreach ($event_lists AS &$event_list) {
        $event_list['EventsDetail']['status'] = $this->EventsEntry->getEventStatus($event_list['EventsDetail']['id']);
      }
      unset($event_list);
      $event_genres = $this->EventGenre->find('list'); //プルダウン選択肢用
      $place_lists = $this->Place->find('list'); //プルダウン選択肢用
      $this->set(compact('event_lists', 'event_genres', 'place_lists'));
      
      $this->render('index');
  }
}
