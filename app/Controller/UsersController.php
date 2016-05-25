<?php

App::uses('AppController', 'Controller');
App::uses('File', 'Utility'); //ファイルAPI用
App::uses('Folder', 'Utility'); //フォルダAPI用

class UsersController extends AppController {

  public $uses = array(
      'User',
      'EntryGenre', 'Event', 'EventGenre', 'EventUser', 'EventsDetail', 'EventsEntry',
      'Option', 'Place'
  ); //使用するModel

  public function beforeFilter() {
      parent::beforeFilter();
      $this->layout = 'eventer_normal';
      // ユーザ自身による登録とログアウトを許可する
      $this->Auth->allow('add', 'logout', 'pw_renew');
//      $this->User->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
  }

  public function login() {
      //ログイン中の場合はredirect
      if ($this->Auth->user('id')) {
        $this->redirect('/');
      }
      
      if ($this->request->is('post')) {
        if ($this->Auth->login()) {
          /* ログイン時に定期バックアップを判定して作成ここから */
          $file_pass = '../backup';
          $file_name = 'eventer_backup';
          $backup_flg = 1;
          
          $folder = new Folder($file_pass);
          $lists = $folder->read();
          foreach ($lists[1] AS $list) { //ファイル名から日付を取得
            $name = str_replace(
                    array($file_name.'_', '.txt'),
                    '',
                    $list
            );
            if (date('Ymd', strtotime('-7 day')) < date($name)) { //直近のファイルがあればflgを消去
              $backup_flg = 0;
              break;
            }
          }
          
          if ($backup_flg == 1) { //flgがあればバックアップを作成
            //DBデータを取得する
            $array_model = array(
                'User',
                'EntryGenre', 'Event', 'EventGenre', 'EventUser', 'EventsDetail', 'EventsEntry',
                'Option', 'Place'
            );
            foreach ($array_model AS $model) {
              $this->$model->Behaviors->disable('SoftDelete');
              $datas = $this->$model->find('all', array('order' => $model.'.id', 'recursive' => -1));
              $this->set($model.'_datas', $datas);
              $this->set($model.'_tbl', $this->$model->useTable);
            }
            $this->set('array_model', $array_model);
            
            $this->layout = false;
            $sql = $this->render('sql_backup');
            $file = new File($file_pass.'/'.$file_name.'_'.date('Ymd').'.sql', true);
            if ($file->write($sql)) { //バックアップ成功時の処理
              $file->close();
              foreach ($lists[1] AS $list) {
                $file = new File($file_pass.'/'.$list);
                $file->delete();
                $file->close();
              }
            } else { //バックアップ失敗時の処理
              $file->close();
              $admin_mail = Configure::read('admin_mail');
              $email = new CakeEmail('gmail');
              $email->to($admin_mail)
                    ->subject('【イベ幸システム通知】バックアップエラー通知')
                    ->template('backup_error', 'eventer_mail')
                    ->viewVars(array(
                        'name' => '管理者'
                    )); //mailに渡す変数
              $email->send();
            }
          }
          /* ログイン時に定期バックアップを判定して作成ここまで */
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
      //ログイン中の場合はredirect
      if ($this->Auth->user('id')) {
        $this->redirect('/user/'.$this->Auth->user('id'));
      }
      
      if ($this->request->is('post')) {
        $this->User->set($this->request->data); //postデータがあればModelに渡してvalidate
        if ($this->User->validates()) { //validate成功の処理
          $this->User->save($this->request->data); //validate成功でsave
          if ($this->User->save($this->request->data)) {
            $this->Session->setFlash('登録完了です。登録されたメールアドレスに登録情報を送りました。', 'flashMessage');
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
            $this->redirect('/users/login/');
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
            //セッションのuser情報を更新する
            $user = $this->User->find('first', array('conditions' => array('User.id' => $id)));
            unset($user['User']['password']);
            $this->Session->write('Auth', $user);
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
      //ログイン中の場合はredirect
      if ($this->Auth->user('id')) {
        $this->redirect('/');
      }
      
      if (!empty($this->request->data)) {
        $data = $this->User->find('first', array(
            'conditions' => array('User.username' => $this->request->data['User']['username']) 
        ));
        if ($data) {
          //新しくパスワードを発行してsave
          $str = array_merge(range('a', 'z'), range('0', '9')/*, range('A', 'Z')*/);
          $new_password = null;
          for ($i = 0; $i < 8; $i++) { //桁数をここで指定
            $new_password .=$str[rand(0, count($str))];
          }
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
            $this->Session->setFlash('登録されているメールアドレスに新しいパスワードを送りました。', 'flashMessage');
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
