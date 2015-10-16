<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class EventsController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('Event', 'EventGenre', 'EntryGenre', 'User', 'EventUser', 'Place', 'Option'); //使用するModel

/**
 * Displays a view
 *
 * @return void
 * @throws NotFoundException When the view file could not be found
 *	or MissingViewException in debug mode.
 */

  public $components = array('Paginator');
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
      $login_id = $this->Session->read('Auth.User.id'); //何度も使用するので予め取得しておく
//      $event_lists = $this->Event->find('all', array(
//          'order' => array('date' => 'desc')
//      ));
      $join_lists = $this->EventUser->find('list', array( //参加済みイベントのidを取得
          'conditions' => array('user_id' => $login_id),
          'fields' => 'EventUser.event_id'
      ));
      $this->Paginator->settings = array( //eventsページのイベント一覧を設定
          'conditions' => array(
              'and' => array(
                  'date >=' => date('Y-m-d'),
                  'or' => array(
                      array('Event.user_id' => $login_id),
                      array('Event.id' => $join_lists),
                      array('Event.publish' => 1) //公開ステータスを追加
                  )
              )
          ),
          'order' => array('date' => 'asc')
      );
      $event_lists = $this->Paginator->paginate('Event');
      $event_genres = $this->EventGenre->find('list'); //プルダウン選択肢用
      $place_lists = $this->Place->find('list'); //プルダウン選択肢用
      $entry_genres = $this->EntryGenre->find('list'); //プルダウン選択肢用
      $USER_CARBON_OPTION = $this->Option->find('first', array( //オプション値を取得
          'conditions' => array('title' => 'USER_CARBON_KEY')
      ));
      $USER_CARBON_KEY = $USER_CARBON_OPTION['Option']['key'];
      $user_lists = $this->User->find('all', array( //チェックボックス選択肢用
          'fields' => array('id', 'handlename'),
          'conditions' => array('and' => array(
              array('id !=' => $login_id), //ログインユーザを除外
              array('id >' => $USER_CARBON_KEY), //管理者及び閲覧用アカウントを除外
              array('community_id' => 1) //参加者機能利用者のみ
          ))
      ));
      $this->set('event_lists', $event_lists);
      $this->set('event_genres', $event_genres);
      $this->set('place_lists', $place_lists);
      $this->set('entry_genres', $entry_genres);
      $this->set('user_lists', $user_lists);

      if (isset($this->request->params['id']) == TRUE) { //パラメータにidがあれば詳細ページを表示
        $this->Event->recursive = 2; //Event→EventUser→Userの2階層下までassociate
        $event_detail = $this->Event->find('first', array(
            'conditions' => array(
                'and' => array(
                    'Event.id' => $this->request->params['id'],
                    'or' => array( //作成者か参加者の場合のみ
                        array('Event.user_id' => $login_id),
                        array('Event.id' => $join_lists),
                        array('Event.publish' => 1) //公開ステータスを追加
                    )
                )
            )
        ));
        if (!empty($event_detail)) { //データが存在する場合
          $this->set('event_detail', $event_detail);
          $this->layout = 'eventer_normal';
          $this->render('event');
        } else { //データが存在しない場合
          $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
        }
      }
  }

  public function add() {
      if ($this->request->is('post')) {
        //viewでchekckedのfieldをnullに書き換える、JSが無効な場合を考えて残す
        if (isset($this->request->data['time_start']) == TRUE) { //開催時刻
          $this->request->data['Event']['time_start'] = null;
        }
        if (isset($this->request->data['entry_start']) == TRUE) { //申込開始日
          $this->request->data['Event']['entry_start'] = null;
        }
        if (isset($this->request->data['entry_end']) == TRUE) { //申込終了日
          $this->request->data['Event']['entry_end'] = null;
        }
        if (isset($this->request->data['announcement_date']) == TRUE) { //結果発表日
          $this->request->data['Event']['announcement_date'] = null;
        }
        if (isset($this->request->data['payment_end']) == TRUE) { //入金締切日
          $this->request->data['Event']['payment_end'] = null;
        }
        //書き換えここまで
        $this->Event->set($this->request->data); //postデータがあればModelに渡してvalidate
        if ($this->Event->validates()) { //validate成功の処理
          $this->Event->saveAssociated($this->request->data); //validate成功でsave
          if ($this->Event->save($this->request->data)) {
            $this->Session->setFlash('登録しました。', 'flashMessage');
          } else {
            $this->Session->setFlash('登録できませんでした。', 'flashMessage');
          }
        } else { //validate失敗の処理
          $this->render('index'); //validate失敗でindexを表示
        }
      }

      $this->redirect('/events/');
  }

  public function edit($id = null) {
      $login_id = $this->Session->read('Auth.User.id'); //何度も使用するので予め取得しておく
//      $event_lists = $this->Event->find('all', array(
//          'order' => array('date' => 'desc')
//      ));
      $join_lists = $this->EventUser->find('list', array( //参加済みイベントのidを取得
          'conditions' => array('user_id' => $login_id),
          'fields' => 'EventUser.event_id'
      ));
      $this->Paginator->settings = array( //eventsページのイベント一覧を設定
          'conditions' => array(
              'and' => array(
                  'date >=' => date('Y-m-d'),
                  'or' => array(
                      array('Event.user_id' => $login_id),
                      array('Event.id' => $join_lists)
                  )
              )
          ),
          'order' => array('date' => 'asc')
      );
      $event_lists = $this->Paginator->paginate('Event');
      $event_genres = $this->EventGenre->find('list'); //プルダウン選択肢用
      $place_lists = $this->Place->find('list'); //プルダウン選択肢用
      $entry_genres = $this->EntryGenre->find('list'); //プルダウン選択肢用
      $this->set('event_lists', $event_lists);
      $this->set('event_genres', $event_genres);
      $this->set('place_lists', $place_lists);
      $this->set('entry_genres', $entry_genres);

      if (empty($this->request->data)) {
        $this->request->data = $this->Event->findById($id); //postデータがなければ$idからデータを取得
        if (!empty($this->request->data)) { //データが存在する場合
          if ($this->request->data['Event']['user_id'] == $login_id) { //データの作成者とログインユーザが一致する場合
            $this->set('id', $id); //viewに渡すために$idをセット
            $USER_CARBON_OPTION = $this->Option->find('first', array( //オプション値を取得
                'conditions' => array('title' => 'USER_CARBON_KEY')
            ));
            $USER_CARBON_KEY = $USER_CARBON_OPTION['Option']['key'];
            $checked_lists = $this->EventUser->find('list', array( //checkedユーザを取得
                'fields' => 'EventUser.user_id',
                'conditions' => array('EventUser.event_id' => $id),
                'order' => array('EventUser.user_id' => 'asc')
            ));
              //checkedユーザが1人だった場合のバグを修正（追加済み参加者でid = array(x)となりエラー）
              if (count($checked_lists) == 1) {
                $checked_lists_only = $this->EventUser->find('first', array(
                    'fields' => 'user_id',
                    'conditions' => array('EventUser.event_id' => $id),
                ));
                $checked_lists = $checked_lists_only['EventUser']['user_id'];
              }
              //バグ修正ここまで
            $user_lists = $this->User->find('all', array( //チェックボックス選択肢用、値を無理やり引き継ぐ
                'fields' => array('id', 'handlename'),
                'conditions' => array('and' => array(
                    array('id !=' => $login_id), //ログインユーザを除外
                    array('id >' => $USER_CARBON_KEY), //管理者及び閲覧用アカウントを除外
                    array('id !=' =>  $checked_lists), //登録済参加者を除外
                    array('community_id' => 1) //参加者機能利用者のみ
                )),
                'order' => array('id' => 'asc')
            ));
            $checked_user_lists = $this->User->find('all', array( //チェックボックス選択肢用、checkedユーザ
                'fields' => array('id', 'handlename'),
                'conditions' => array('and' => array(
                    array('id !=' => $login_id), //ログインユーザを除外
                    array('id >' => $USER_CARBON_KEY), //管理者及び閲覧用アカウントを除外
                    array('id =' =>  $checked_lists) //登録済参加者
                )),
                'order' => array('id' => 'asc')
            ));
            $this->set('user_lists', $user_lists);
            $this->set('checked_user_lists', $checked_user_lists);
            $checked_lists_delete = $this->EventUser->find('all', array( //削除ボタンのために取得
                'fields' => 'id',
                'conditions' => array('EventUser.event_id' => $id),
                'order' => array('EventUser.user_id' => 'asc')
            ));
            $this->set('checked_lists_delete', $checked_lists_delete);
          } else { //データの作成者とログインユーザが一致しない場合
            $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
          $this->redirect('/events/'); //参加者の選択肢を引き継いで取得できないので、renderではない
          }
        } else { //データが存在しない場合
          $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
          $this->redirect('/events/'); //参加者の選択肢を引き継いで取得できないので、renderではない
        }
      } else {
        //viewでchekckedのfieldをnullに書き換える、JSが無効な場合を考えて残す
        if (isset($this->request->data['time_start']) == TRUE) { //開催時刻
          $this->request->data['Event']['time_start'] = null;
        }
        if (isset($this->request->data['entry_start']) == TRUE) { //申込開始日
          $this->request->data['Event']['entry_start'] = null;
        }
        if (isset($this->request->data['entry_end']) == TRUE) { //申込終了日
          $this->request->data['Event']['entry_end'] = null;
        }
        if (isset($this->request->data['announcement_date']) == TRUE) { //結果発表日
          $this->request->data['Event']['announcement_date'] = null;
        }
        if (isset($this->request->data['payment_end']) == TRUE) { //入金締切日
          $this->request->data['Event']['payment_end'] = null;
        }
        //書き換えここまで
        $this->Event->set($this->request->data); //postデータがあればModelに渡してvalidate
        if ($this->Event->validates()) { //validate成功の処理
          $this->Event->saveAssociated($this->request->data); //validate成功でsave
          if ($this->Event->save($id)) {
            $this->Session->setFlash('修正しました。', 'flashMessage');
          } else {
            $this->Session->setFlash('修正できませんでした。', 'flashMessage');
          }
          $this->redirect('/events/');
        } else { //validate失敗の処理
          $this->set('id', $this->request->data['Event']['id']); //viewに渡すために$idをセット
//          $this->render('index'); //validate失敗でindexを表示
        }
      }
  }

  public function delete($id = null) {
      if (empty($id)) {
        throw new NotFoundException(__('存在しないデータです。'));
      }
      
      if ($this->request->is('post')) {
        $this->Event->Behaviors->enable('SoftDelete');
        if ($this->Event->delete($id)) {
          $this->Session->setFlash('削除しました。', 'flashMessage');
        } else {
          $this->Session->setFlash('削除できませんでした。', 'flashMessage');
        }
        $this->redirect('/events/');
      }
  }

  public function checked_user_delete($id = null) {
      if (empty($id)) {
        throw new NotFoundException(__('存在しないデータです。'));
      }
      
      if ($this->request->is('post')) {
//        $this->EventUser->Behaviors->enable('SoftDelete');
        if ($this->EventUser->delete($id)) {
          $this->Session->setFlash('削除しました。', 'flashMessage');
        } else {
          $this->Session->setFlash('削除できませんでした。', 'flashMessage');
        }
        $this->redirect('/events/');
      }
  }

  public function event_lists() {
      $login_id = $this->Session->read('Auth.User.id'); //何度も使用するので予め取得しておく
      $join_lists = $this->EventUser->find('list', array( //参加済みイベントのidを取得
          'conditions' => array('EventUser.user_id' => $login_id),
          'fields' => 'EventUser.event_id'
      ));
      $this->Paginator->settings = array( //eventsページのイベント一覧を設定
          'conditions' => array(
              'and' => array(
                  'date <' => date('Y-m-d'), //過去のイベントを取得
                  'or' => array(
                      array('Event.user_id' => $login_id),
                      array('Event.id' => $join_lists),
                      array('Event.publish' => 1) //公開ステータスを追加
                  )
              )
          ),
          'order' => array('date' => 'desc')
      );
      $event_lists = $this->Paginator->paginate('Event');
      $this->set('event_lists', $event_lists);
      
      //未対応のイベント
      $event_undecided_lists = $this->Event->find('all', array(
          'conditions' => array(
              'and' => array(
                  'Event.date <' => date('Y-m-d'),
                  'Event.status <' => 2,
                  'Event.user_id' => $login_id
              )
          ),
          'order' => array('date' => 'asc')
      ));
      $this->set('event_undecided_lists', $event_undecided_lists);
  }

  public function event_lists_delete($id = null) {
      if (empty($id)) {
        throw new NotFoundException(__('存在しないデータです。'));
      }
      
      if ($this->request->is('post')) {
        $this->Event->Behaviors->enable('SoftDelete');
        if ($this->Event->delete($id)) {
          $this->Session->setFlash('削除しました。', 'flashMessage');
        } else {
          $this->Session->setFlash('削除できませんでした。', 'flashMessage');
        }
        $this->redirect('/events/event_lists/');
      }
  }
}