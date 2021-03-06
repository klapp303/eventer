<?php

App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth'); //パスワードのハッシュ化のため

class User extends AppModel
{
    public $useTable = 'users';
    
    public $actsAs = array('SoftDelete'/* , 'Search.Searchable' */);
    
//    public $belongsTo = array(
//        'Profile' => array(
//            'className' => 'Profile', //関連付けるModel
//            'foreignKey' => 'user_id', //関連付けるためのfield、関連付け先は上記Modelのid
//            'fields' => array('mailmaga', 'station') //関連付け先Modelの使用field
//        )
//    );
    
    public $validate = array(
        'username' => array(
            'rule_1' => array(
                'rule' => 'notBlank',
                'required' => 'create',
                'message' => 'メールアドレスを正しく入力してください。'
            ),
//            'rule_2' => array(
//                'rule' => 'alphaNumeric',
//                'message' => 'ユーザ名は半角英数のみです'
//            ),
//            'rule_3' => array(
//                'rule' => 'isHalfLetter',
//                'message' => 'ユーザ名は半角英数のみです'
//            ),
//            'rule_4' => array(
//                'rule' => array('between', 4, 10),
//                'message' => 'ユーザ名は4～10文字です'
//            ),
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
            'rule_2' => array( //1行表示は12文字、2行までなら一応崩れない
                'rule' => array('maxLength', 12),
                'message' => 'ハンドルネームは12文字以内です'
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
    
    public function beforeSave($options = [])
    {
        //パスワードのハッシュ化のため
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new BlowfishPasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                    $this->data[$this->alias]['password']
            );
        }
        
        return true;
    }
    
//    public $filtetArgs = array(
//        'id' => array('type' => 'value'),
//        'title' => array('type' => 'value')
//    );
}
