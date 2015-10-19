<?php

App::uses('AppModel', 'Model');

/**
 * EventUser Model.
 */
class EventUser extends AppModel {
  public $useTable = 'Event_users';
  public $actsAs = array(/*'SoftDelete'*/); //関連テーブルのデータを取得されるので物理削除する

  public $belongsTo = array(
      'UserProfile' => array(
          'className' => 'User', //関連付けるModel
          'foreignKey' => 'user_id', //関連付けるためのfield、関連付け先は上記Modelのid
          'fields' => 'handlename' //関連付け先Modelの使用field
      ),
      'EventDetail' => array(
          'className' => 'Event', //関連付けるModel
          'foreignKey' => 'event_id', //関連付けるためのfield、関連付け先は上記Modelのid
          //'fields' => 'title' //関連付け先Modelの使用field
      )
  );

  /*public $validate = array(
      'title' => array(
          'rule' => 'notBlank',
          'required' => 'true'
      ),
      'amount' => array(
          'rule' => 'numeric',
          'required' => 'true',
          'message' => '値段を正しく入力してください。'
      )
  );*/

    /*public $filtetArgs = array(
      'id' => array('type' => 'value'),
      'title' => array('type' => 'value')
  );*/
}