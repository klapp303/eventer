<?php

App::uses('AppController', 'Controller');

class BudgetsController extends AppController {

  public $uses = array('EventsDetail', 'EventsEntry'/*, 'EventUser'*/); //使用するModel

  public $components = array('Paginator');
  public $paginate = array(
      'limit' => 20,
      'order' => array('id' => 'desc')
  );

  public function beforeFilter() {
      parent::beforeFilter();
      $this->layout = 'eventer_fullwidth';
//      $this->Sample->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
  }

  public function index() {
  }

  public function unfixed_payment() {
      $this->set('column', 'payment');
      $this->set('unfixed_lists', $this->EventsDetail->getUnfixedPayment($this->Auth->user('id')));
      
      $this->render('unfixed_lists');
  }

  public function unfixed_sales() {
      $this->set('column', 'sales');
      $this->set('unfixed_lists', $this->EventsDetail->getUnfixedSales($this->Auth->user('id')));
      
      $this->render('unfixed_lists');
  }

  public function unfixed_collect() {
      $this->set('column', 'collect');
      $this->set('unfixed_lists', $this->EventsDetail->getUnfixedCollect($this->Auth->user('id')));
      
      $this->render('unfixed_lists');
  }

  public function fixed($id = false, $update_column = false) {
      if (empty($id) || empty($update_column)) {
        throw new NotFoundException(__('存在しないデータです。'));
      }
      
      if ($this->request->is('post')) {
        $this->EventsEntry->id = $id;
        if ($this->EventsEntry->savefield($update_column, 1)) {
          $this->Session->setFlash('対応済みに変更しました。', 'flashMessage');
        } else {
          $this->Session->setFlash('変更できませんでした。', 'flashMessage');
        }
        
        if ($update_column == 'payment_status') {
          $this->redirect('/budgets/unfixed_entry/');
        } elseif ($update_column == 'sales_status') {
          $this->redirect('/budgets/unfixed_ticket/');
        } elseif ($update_column == 'collect_status') {
          $this->redirect('/budgets/unfixed_collect/');
        } else { //未使用
          $this->redirect('/budgets/');
        }
      }
  }

  public function reset_status($column = false) {
      if (empty($column)) {
        throw new NotFoundException(__('存在しないデータです。'));
      }
      if ($column != 'payment' && $column != 'sales' && $column != 'collect') {
        throw new NotFoundException(__('存在しないデータです。'));
      }
      $this->set('reset_column', $column);
      
      $event_lists = $this->EventsDetail->find('all', array(
          'conditions' => array(
              'EventsDetail.user_id' => $this->Auth->User('id'),
              'EventsDetail.date >=' => date('Y-m-d'),
              'EventsDetail.deleted !=' => 1
          ),
          'order' => array('EventsDetail.date' => 'asc', 'EventsDetail.time_start' => 'asc')
      ));
      foreach ($event_lists AS $key => $event) {
        $entry_lists = $this->EventsEntry->find('all', array(
            'conditions' => array(
                'EventsEntry.events_detail_id' => $event['EventsDetail']['id'],
                'EventsEntry.user_id' => $this->Auth->User('id'),
                'EventsEntry.'.$column.'_status' => 1
            )
        ));
        //statusリセットできるエントリーがなければリストから削除
        if (!$entry_lists) {
          unset($event_lists[$key]);
        //statusリセットできるエントリーがあれば該当エントリーのみリストに残す
        } else {
          unset($event_lists[$key]['EventsEntry']);
          foreach ($entry_lists AS $entry) {
            $event_lists[$key]['EventsEntry'][] = $entry['EventsEntry'];
          }
        }
      }
      $this->set('unfixed_lists', array('list' => $event_lists, 'count' => count($event_lists)));
      $this->render('unfixed_lists');
  }

  public function reset($id = false, $reset_column = false) {
      if (empty($id) || empty($reset_column)) {
        throw new NotFoundException(__('存在しないデータです。'));
      }
      
      if ($this->request->is('post')) {
        $this->EventsEntry->id = $id;
        if ($this->EventsEntry->savefield($reset_column, 0)) {
          $this->Session->setFlash('対応済みを元に戻しました。', 'flashMessage');
        } else {
          $this->Session->setFlash('元に戻せませんでした。', 'flashMessage');
        }
        
        if ($reset_column == 'payment_status') {
          $this->redirect('/budgets/reset_status/payment/');
        } elseif ($reset_column == 'sales_status') {
          $this->redirect('/budgets/reset_status/sales/');
        } elseif ($reset_column == 'collect_status') {
          $this->redirect('/budgets/reset_status/collect/');
        } else { //未使用
          $this->redirect('/budgets/');
        }
      }
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
