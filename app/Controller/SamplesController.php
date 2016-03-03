<?php

App::uses('AppController', 'Controller');

class SamplesController extends AppController {

	public $uses = array('Sample'); //使用するModel

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

  /*public function index() {
//      $sample_lists = $this->Sample->find('all', array(
//          'order' => array('date' => 'desc')
//      ));
      $this->Paginator->settings = $this->paginate;
      $sample_lists = $this->Paginator->paginate('Sample');
      //$sample_counts = count($sample_lists);
      $this->set('sample_lists', $sample_lists);
      //$this->set('sample_counts', $sample_counts);
  
      if (isset($this->request->params['id']) == TRUE) { //パラメータにidがあれば詳細ページを表示
        $sample_detail = $this->Sample->find('first', array(
            'conditions' => array('Sample.id' => $this->request->params['id'])
        ));
        if (!empty($sample_detail)) { //データが存在する場合
          $this->set('sample_detail', $sample_detail);
          $this->render('sample');
        } else { //データが存在しない場合
          $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
        }
      }
  }*/

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
//      $sample_lists = $this->Sample->find('all', array(
//          'order' => array('date' => 'desc')
//      ));
      $this->Paginator->settings = $this->paginate;
      $sample_lists = $this->Paginator->paginate('Sample');
      //$sample_counts = count($sample_lists);
      $this->set('sample_lists', $sample_lists);
      //$this->set('sample_counts', $sample_counts);
  
      if (empty($this->request->data)) {
        $this->request->data = $this->Sample->findById($id); //postデータがなければ$idからデータを取得
        if (!empty($this->request->data)) { //データが存在する場合
          $this->set('id', $id); //viewに渡すために$idをセット
        } else { //データが存在しない場合
          $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
        }
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