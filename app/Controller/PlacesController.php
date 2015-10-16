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
class PlacesController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('Place', 'EventUser', 'Event', 'Option'); //使用するModel

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
      'order' => array('id' => 'asc'),
      'conditions' => array('id !=' => 1) //id=1は'その他'なので除外する
  );

  public function beforeFilter() {
      parent::beforeFilter();
      $this->layout = 'eventer_fullwidth';
      //$this->Place->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
  }

  public function index() {
      $this->redirect('/places/place_lists/');
  }

  public function place_lists() {
      $PLACE_BLOCK_OPTION = $this->Option->find('first', array( //オプション値を取得
          'conditions' => array('title' => 'PLACE_BLOCK_KEY')
      ));
      $PLACE_BLOCK_KEY = $PLACE_BLOCK_OPTION['Option']['key'];
      $this->set('PLACE_BLOCK_KEY', $PLACE_BLOCK_KEY);

//      $place_lists = $this->Place->find('all', array(
//          'order' => array('id' => 'asc')
//      ));
      $this->Paginator->settings = $this->paginate;
      $place_lists = $this->Paginator->paginate('Place');
      //$place_counts = count($place_lists);
      $this->set('place_lists', $place_lists);
      //$this->set('place_counts', $place_counts);
  }

  public function place_detail() {
      $login_id = $this->Session->read('Auth.User.id'); //何度も使用するので予め取得しておく

      if (isset($this->request->params['id']) == TRUE) { //パラメータにidがあれば詳細ページを表示
        $place_detail = $this->Place->find('first', array(
            'conditions' => array('and' => array(
                'Place.id' => $this->request->params['id'],
                'Place.id !=' => 1 //id=1は'その他'なので除外する
            ))
        ));
        if (!empty($place_detail)) { //データが存在する場合
          $this->set('place_detail', $place_detail);
          //会場に紐付くイベント一覧を取得
          $join_lists = $this->EventUser->find('list', array( //参加済みイベントのidを取得
              'conditions' => array('user_id' => $login_id),
              'fields' => 'event_id'
          ));
          $this->Paginator->settings = array( //place_detailページのイベント一覧を設定
              'conditions' => array(
                  'and' => array(
                      'date >=' => date('Y-m-d'),
                      'place_id' => $this->request->params['id'], //eventsページの一覧から会場で更に絞り込み
                      'or' => array(
                          array('Event.user_id' => $login_id),
                          array('Event.id' => $join_lists)
                      )
                  )
              ),
              'order' => array('date' => 'asc')
          );
          $event_lists = $this->Paginator->paginate('Event');
          $this->set('event_lists', $event_lists);
          //取得ここまで
          $this->layout = 'eventer_normal';
        } else { //データが存在しない場合
          $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
          $this->redirect('/places/place_lists/');
        }
      }
  }

  public function add() {
      if ($this->request->is('post')) {
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
      } //postデータがなければaddページを表示
  }

  public function edit($id = null) {
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
//          $this->render('index'); //validate失敗でindexを表示
        }
      }
  }

  public function delete($id = null){
      $PLACE_BLOCK_OPTION = $this->Option->find('first', array( //オプション値を取得
          'conditions' => array('title' => 'PLACE_BLOCK_KEY')
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
}