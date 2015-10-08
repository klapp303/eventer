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
	public $uses = array('Event'); //使用するModel

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
      'order' => array('date' => 'desc')
  );

  public function beforeFilter() {
    parent::beforeFilter();
    $this->layout = 'eventer_fullwidth';
  }

  public function index() {
//    $event_lists = $this->Event->find('all', array(
//        'order' => array('date' => 'desc')
//    ));
    $this->Paginator->settings = $this->paginate;
    $event_lists = $this->Paginator->paginate('Event');
    $event_counts = count($event_lists);
    $this->set('event_lists', $event_lists);
    $this->set('event_counts', $event_counts);
  }

  public function add() {
    if ($this->request->is('post')) {
      $this->Event->set($this->request->data); //postデータがあればModelに渡してvalidate
      if ($this->Event->validates()) { //validate成功の処理
        $this->Event->save($this->request->data); //validate成功でsave
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
//    $event_lists = $this->Event->find('all', array(
//        'order' => array('date' => 'desc')
//    ));
    $this->Paginator->settings = $this->paginate;
    $event_lists = $this->Paginator->paginate('Event');
    $event_counts = count($event_lists);
    $this->set('event_lists', $event_lists);
    $this->set('event_counts', $event_counts);

    if (empty($this->request->data)) {
      $this->request->data = $this->Event->findById($id); //postデータがなければ$idからデータを取得
      $this->set('id', $this->request->data['Event']['id']); //viewに渡すために$idをセット
    } else {
      $this->Event->set($this->request->data); //postデータがあればModelに渡してvalidate
      if ($this->Event->validates()) { //validate成功の処理
        $this->Event->save($this->request->data); //validate成功でsave
        if ($this->Event->save($id)) {
          $this->Session->setFlash('修正しました。', 'flashMessage');
        } else {
          $this->Session->setFlash('修正できませんでした。', 'flashMessage');
        }
        $this->redirect('/events/');
      } else { //validate失敗の処理
        $this->set('id', $this->request->data['Event']['id']); //viewに渡すために$idをセット
//        $this->render('index'); //validate失敗でindexを表示
      }
    }
  }

  public function deleted($id = null){
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
}