<?php

App::uses('AppModel', 'Model');

class Event extends AppModel {

  public $useTable = 'events';
  public $actsAs = array('SoftDelete', 'Search.Searchable');

  public $belongsTo = array(
      'User' => array(
          'className' => 'User', //関連付けるModel
          'foreignKey' => 'user_id', //関連付けるためのfield、関連付け先は上記Modelのid
          'fields' => array('username', 'handlename') //関連付け先Modelの使用field
      )
  );

  public $hasMany = array(
      'EventsDetail' => array(
          'className' => 'EventsDetail', //関連付けるModel
          'foreignKey' => 'event_id' //関連付けるfield
      )
  );

  public $validate = array(
      'title' => array(
          'rule' => 'notBlank',
          'required' => 'create'
      )
  );

  public $filtetArgs = array(
      'title' => array('type' => 'value'),
      'status' => array('type' => 'value')
  );
}