<?php

App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth'); //パスワードのハッシュ化のため

/**
 * User Model.
 */
class User extends AppModel {
  public $useTable = 'Users';
  public $actsAs = array('SoftDelete'/*, 'Search.Searchable'*/);

  public $validate = array(
      'username' => array(
          'rule_1' => array(
              'rule' => 'notBlank',
              'required' => 'create',
              'message' => 'メールアドレスを正しく入力してください。'
          ),
          /*'rule_2' => array(
              'rule' => 'alphaNumeric',
              'message' => 'ユーザ名は半角英数のみです'
          ),*/
          /*'rule_3' => array(
              'rule' => 'isHalfLetter',
              'message' => 'ユーザ名は半角英数のみです'
          ),*/
          /*'rule_4' => array(
              'rule' => array('between', 4, 10),
              'message' => 'ユーザ名は4～10文字です'
          ),*/
          'rule_5' => array(
              'rule' => array('email', true),
              'message' => 'メールアドレスを正しく入力してください。'
          ),
          'rule_6' => array(
              'rule' => 'isUnique',
              'message' => '既に登録されているメールアドレスです。'
          )
      ),
      'handlename' => array(
          'rule_1' => array(
              'rule' => 'notBlank',
              'required' => 'create',
              'message' => 'ハンドルネームを正しく入力してください。'
          ),
          'rule_2' => array(
              'rule' => array('maxLength', 16),
              'message' => 'ハンドルネームは16文字以内です'
          )
      ),
      'password' => array(
          'rule_1' => array(
              'rule' => 'notBlank',
              'required' => 'create',
              'message' => 'パスワードを正しく入力してください。'
          ),
          'rule_2' => array(
              'rule' => 'alphaNumeric',
              'message' => 'パスワードは半角英数のみです'
          ),
          'rule_3' => array(
              'rule' => 'isHalfLetter',
              'message' => 'パスワードは半角英数のみです'
          ),
          'rule_4' => array(
              'rule' => array('between', 4, 10),
              'message' => 'パスワードは4～10文字です'
          )
      )
  );

  public function beforeSave($options = array()) { //パスワードのハッシュ化のため
      if (isset($this->data[$this->alias]['password'])) {
        $passwordHasher = new BlowfishPasswordHasher();
        $this->data[$this->alias]['password'] = $passwordHasher->hash(
            $this->data[$this->alias]['password']
        );
      }
      return true;
  }

  /*public $filtetArgs = array(
      'id' => array('type' => 'value'),
      'title' => array('type' => 'value')
  );*/
}