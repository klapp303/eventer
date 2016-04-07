<?php

App::uses('AppController', 'Controller');

class BudgetsController extends AppController {

	public $uses = array('EventsEntry'/*, 'EventUser'*/); //使用するModel

  public $components = array('Paginator');
  public $paginate = array(
      'limit' => 20,
      'order' => array('id' => 'desc')
  );

  public function beforeFilter() {
      parent::beforeFilter();
      $this->layout = 'eventer_fullwidth';
      //$this->Sample->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
  }

  public function index() {
  }

  public function unfixed_entry() {
      $this->set('unfixed_entry_lists', $this->EventsEntry->getUnfixedEntry($this->Auth->user('id')));
  }

  public function unfixed_ticket() {
      $this->set('unfixed_ticket_lists', $this->EventsEntry->getUnfixedTicket($this->Auth->user('id')));
  }

  /*public function in_lists() {
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
  }*/

  /*public function out_lists() {
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
  }*/

  /*public function edit($id = null) {
      if (empty($id)) {
        throw new NotFoundException(__('存在しないデータです。'));
      }
      
      if ($this->request->is('post')) {
        $this->EventUser->id = $id;
        $this->EventUser->saveField('payment', 1);
        $this->redirect('/budgets/in_lists/');
      }
  }*/

  /*public function delete($id = null){
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
  }*/
}