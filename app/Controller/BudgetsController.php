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
class BudgetsController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('EventUser'); //使用するModel

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
      //$this->Sample->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
  }

  public function index() {
  }

  public function in_lists() {
      $login_id = $this->Session->read('Auth.User.id'); //何度も使用するので予め取得しておく
      $this->EventUser->recursive = 2; //EventUser→Event→EventGenreの2階層下までassociate
//      $sample_lists = $this->Sample->find('all', array(
//          'order' => array('date' => 'desc')
//      ));
      $this->Paginator->settings = array(
          'conditions' => array(
              'EventDetail.user_id' => $login_id,
              'EventUser.payment' => 0,
              'EventDetail.deleted !=' => 1 //紐付くテーブルのSoftDeleteは無視されるので記述
          ),
          'order' => array('EventDetail.date' => 'asc')
      );
      $in_lists = $this->Paginator->paginate('EventUser');
      $this->set('in_lists', $in_lists);

      if (isset($this->request->params['id']) == TRUE) { //パラメータにidがあれば詳細ページを表示
        $in_detail = $this->EventUser->find('first', array(
            'conditions' => array('EventUser.id' => $this->request->params['id'])
        ));
        if (!empty($in_detail)) { //データが存在する場合
          $this->set('in_detail', $in_detail);
          $this->render('in_lists');
        } else { //データが存在しない場合
          $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
        }
      }
  }

  public function out_lists() {
      $login_id = $this->Session->read('Auth.User.id'); //何度も使用するので予め取得しておく
      $this->EventUser->recursive = 2; //EventUser→Event→EventGenreの2階層下までassociate
//      $sample_lists = $this->Sample->find('all', array(
//          'order' => array('date' => 'desc')
//      ));
      $this->Paginator->settings = array(
          'conditions' => array(
              'EventUser.user_id' => $login_id,
              'EventUser.payment' => 0,
              'EventDetail.deleted !=' => 1 //紐付くテーブルのSoftDeleteは無視されるので記述
          ),
          'order' => array('EventDetail.date' => 'asc')
      );
      $out_lists = $this->Paginator->paginate('EventUser');
      $this->set('out_lists', $out_lists);

      if (isset($this->request->params['id']) == TRUE) { //パラメータにidがあれば詳細ページを表示
        $out_detail = $this->EventUser->find('first', array(
            'conditions' => array('EventUser.id' => $this->request->params['id'])
        ));
        if (!empty($out_detail)) { //データが存在する場合
          $this->set('out_detail', $out_detail);
          $this->render('out_lists');
        } else { //データが存在しない場合
          $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
        }
      }
  }

  public function edit($id = null) {
      if (empty($id)) {
        throw new NotFoundException(__('存在しないデータです。'));
      }
      
      if ($this->request->is('post')) {
        $this->EventUser->id = $id;
        $this->EventUser->saveField('payment', 1);
        $this->redirect('/budgets/in_lists/');
      }
  }

  public function delete($id = null){
      if (empty($id)) {
        throw new NotFoundException(__('存在しないデータです。'));
      }
    
      if ($this->request->is('post')) {
//        $this->Eventuser->Behaviors->enable('SoftDelete');
        if ($this->EventUser->delete($id)) {
          $this->Session->setFlash('削除しました。', 'flashMessage');
        } else {
          $this->Session->setFlash('削除できませんでした。', 'flashMessage');
        }
        $this->redirect('/budgets/in_lists/');
      }
  }
}