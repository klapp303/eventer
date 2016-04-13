<?php

App::uses('AppController', 'Controller');

class EventGenresController extends AppController {

  public $uses = array('EventGenre'); //使用するModel

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
      $this->Paginator->settings = $this->paginate;
      $sample_lists = $this->Paginator->paginate('Sample');
      $sample_counts = count($sample_lists);
      $this->set('sample_lists', $sample_lists);
      $this->set('sample_counts', $sample_counts);
  }

  /*public function add() {
      if ($this->request->is('post')) {
        $this->Sample->set($this->request->data); //postデータがあればModelに渡してvalidate
        if ($this->Sample->validates()) { //validate成功の処理
          $this->Sample->save($this->request->data); //validate成功でsave
          if ($this->Sample->save($this->request->data)) {
            $this->Session->setFlash('登録しました。', 'flashMessage');
          } else {
            $this->Session->setFlash('登録できませんでした。', 'flashMessage');
          }
        } else { //validate失敗の処理
          $this->render('index'); //validate失敗でindexを表示
        }
      }
  
      $this->redirect('/samples/');
  }*/

  /*public function edit($id = null) {
      $this->Paginator->settings = $this->paginate;
      $sample_lists = $this->Paginator->paginate('Sample');
      $sample_counts = count($sample_lists);
      $this->set('sample_lists', $sample_lists);
      $this->set('sample_counts', $sample_counts);
  
      if (empty($this->request->data)) {
        $this->request->data = $this->Sample->findById($id); //postデータがなければ$idからデータを取得
        $this->set('id', $this->request->data['Sample']['id']); //viewに渡すために$idをセット
      } else {
        $this->Sample->set($this->request->data); //postデータがあればModelに渡してvalidate
        if ($this->Sample->validates()) { //validate成功の処理
          $this->Sample->save($this->request->data); //validate成功でsave
          if ($this->Sample->save($id)) {
            $this->Session->setFlash('修正しました。', 'flashMessage');
          } else {
            $this->Session->setFlash('修正できませんでした。', 'flashMessage');
          }
          $this->redirect('/samples/');
        } else { //validate失敗の処理
          $this->set('id', $this->request->data['Sample']['id']); //viewに渡すために$idをセット
//          $this->render('index'); //validate失敗でindexを表示
        }
      }
  }*/

  /*public function delete($id = null){
      if (empty($id)) {
        throw new NotFoundException(__('存在しないデータです。'));
      }
      
      if ($this->request->is('post')) {
        $this->Sample->Behaviors->enable('SoftDelete');
        if ($this->Sample->delete($id)) {
          $this->Session->setFlash('削除しました。', 'flashMessage');
        } else {
          $this->Session->setFlash('削除できませんでした。', 'flashMessage');
        }
        $this->redirect('/samples/');
      }
  }*/
}
