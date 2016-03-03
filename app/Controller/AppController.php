<?php

App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email'); //CakeEmaiilの利用、分けて記述

class AppController extends Controller {

  public $uses = array(); //使用するModel

  public $components = array(
      'Session', //Paginateのため記述
      'Flash', //ここからログイン認証用
      'Auth' => array(
          'loginRedirect' => array(
              'controller' => 'top',
              'action' => 'index'
          ),
          'logoutRedirect' => array(
              'controller' => 'users',
              'action' => 'login'
          ),
          'authenticate' => array(
              'Form' => array('passwordHasher' => 'Blowfish')
          )
      ),
      'DebugKit.Toolbar' //ページ右上の開発用デバッグツール
  );

  /*public function beforeFilter() {
      $this->Auth->allow('index'); //認証なしのページを設定
  }*/
}