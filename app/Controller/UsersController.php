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
class UsersController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('User'); //使用するModel

/**
 * Displays a view
 *
 * @return void
 * @throws NotFoundException When the view file could not be found
 *	or MissingViewException in debug mode.
 */

  public function beforeFilter() {
      parent::beforeFilter();
      $this->layout = 'eventer_normal';
      // ユーザ自身による登録とログアウトを許可する
      $this->Auth->allow('add', 'logout', 'pw_renew');
      //$this->User->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
  }

  public function login() {
      if ($this->request->is('post')) {
        if ($this->Auth->login()) {
          $this->redirect($this->Auth->redirect());
        } else {
          $this->Flash->error(__('ユーザ名かパスワードが間違っています。'));
        }
      }
  }

  public function logout() {
      $this->redirect($this->Auth->logout());
  }

  public function index() {
      if (isset($this->request->params['id']) == TRUE) { //パラメータにidがあれば詳細ページを表示
        if ($this->Session->read('Auth.User.id') == $this->request->params['id']) { //パラメータのidがSession情報と一致する場合のみ
          $user_detail = $this->User->find('first', array(
              'conditions' => array('User.id' => $this->request->params['id'])
          ));
          $this->set('user_detail', $user_detail);
          $this->layout = 'eventer_fullwidth';
          $this->render('user');
        }
      } else {
          $this->redirect('/users/login/');
      }
  }

  public function add() {
      if ($this->request->is('post')) {
        $this->User->set($this->request->data); //postデータがあればModelに渡してvalidate
        if ($this->User->validates()) { //validate成功の処理
          $this->User->save($this->request->data); //validate成功でsave
          if ($this->User->save($this->request->data)) {
            $this->Session->setFlash('登録しました。', 'flashMessage');
            //save成功でメール送信
            $email = new CakeEmail('gmail');
            $email->to($this->request->data['User']['username'])
                  ->subject('【イベ幸委員会】会員登録完了')
                  ->template('users_add_thx', 'eventer_mail')
                  ->viewVars(array(
                      'mailaddress' => $this->request->data['User']['username'],
                      'name' => $this->request->data['User']['handlename'],
                      'password' => $this->request->data['User']['password']
                  )); //mailに渡す変数
            $email->send();
          } else {
            $this->Session->setFlash('登録できませんでした。', 'flashMessage');
          }
        } else { //validate失敗の処理
          $this->Session->setFlash('登録内容が正しくありません。', 'flashMessage');
          $this->render('add'); //validate失敗で元ページに戻る
        }
      }
  }

  public function edit($id = null) {
      $this->layout = 'eventer_fullwidth';
      if (empty($this->request->data)) {
        if (!$this->request->is('post')) { //post送信でない場合
          $this->redirect('/user/'.$id);
        }
        $this->request->data = $this->User->findById($id); //postデータがなければ$idからデータを取得
        $this->set('id', $this->request->data['User']['id']); //viewに渡すために$idをセット
      } else {
        $this->User->set($this->request->data); //postデータがあればModelに渡してvalidate
        if ($this->User->validates()) { //validate成功の処理
          $this->User->save($this->request->data); //validate成功でsave
          if ($this->User->save($id)) {
            $this->Session->setFlash('変更しました。', 'flashMessage');
          } else {
            $this->Session->setFlash('変更できませんでした。', 'flashMessage');
          }
        } else { //validate失敗の処理
          $this->Session->setFlash('変更内容が正しくありません。', 'flashMessage');
        }
        $this->redirect('/user/'.$id); //postデータがあればvalidate結果に関わらず元ページに戻る
      }
  }

  public function pw_edit($id = null) {
      $this->layout = 'eventer_fullwidth';
      $login_data = $this->Session->read('Auth.User'); //予めセッション情報を取得
      if (empty($this->request->data)) {
        if (!$this->request->is('post')) { //post送信でない場合
          $this->redirect('/user/'.$id);
        }
        $this->request->data = $this->User->findById($id); //postデータがなければ$idからデータを取得
        $this->set('id', $this->request->data['User']['id']); //viewに渡すために$idをセット
      } else {
        $this->User->set($this->request->data); //postデータがあればModelに渡してvalidate
        if ($this->User->validates()) { //validate成功の処理
          $this->User->id = $id; //validate成功でsave
          $this->User->saveField('password', $this->request->data['User']['password']);
          if ($this->User->save($id)) {
            $this->Session->setFlash('変更しました。', 'flashMessage');
            //save成功でメール送信
            $email = new CakeEmail('gmail');
            $email->to($login_data['username'])
                  ->subject('【イベ幸委員会】パスワード変更完了')
                  ->template('pw_edit_thx', 'eventer_mail')
                  ->viewVars(array(
                      'name' => $login_data['handlename'],
                      'password' => $this->request->data['User']['password']
                  )); //mailに渡す変数
            $email->send();
          } else {
            $this->Session->setFlash('変更できませんでした。', 'flashMessage');
          }
        } else { //validate失敗の処理
          $this->Session->setFlash('変更内容が正しくありません。', 'flashMessage');
        }
        $this->redirect('/user/'.$id); //postデータがあればvalidate結果に関わらず元ページに戻る
      }
  }

  public function pw_renew(){
      if (!empty($this->request->data)) {
        $data = $this->User->find('first', array(
            'conditions' => array('User.username' => $this->request->data['User']['username']) 
        ));
        if ($data) {
          //新しくパスワードを発行してsave
          $new_password = 'hoge'; //とりあえず新パスワードをhogeにする
          $this->User->id = $data['User']['id'];
          $this->User->saveField('password', $new_password);
          if ($this->User->save($new_password)) {
            //save成功でメール送信
            $email = new CakeEmail('gmail');
            $email->to($data['User']['username'])
                  ->subject('【イベ幸委員会】パスワードのお知らせ')
                  ->template('pw_renew_thx', 'eventer_mail')
                  ->viewVars(array(
                      'name' => $data['User']['handlename'],
                      'password' => $new_password
                  )); //mailに渡す変数
            $email->send();
            $this->Session->setFlash('メールアドレス宛に新しいパスワードを送信しました。', 'flashMessage');
            $this->render('login');
          } else {
            $this->Session->setFlash('パスワードの発行に失敗しました。', 'flashMessage');
          }
        } else {
          $this->Session->setFlash('登録されていないメールアドレスです。', 'flashMessage');
        }
      }
  }

/*  public function delete($id = null){
      if (empty($id)) {
        throw new NotFoundException(__('存在しないデータです。'));
      }
    
      if ($this->request->is('post')) {
        $this->User->Behaviors->enable('SoftDelete');
        if ($this->User->delete($id)) {
          $this->Session->setFlash('削除しました。', 'flashMessage');
        } else {
          $this->Session->setFlash('削除できませんでした。', 'flashMessage');
        }
        $this->redirect('/users/login/');
      }
  }*/
}