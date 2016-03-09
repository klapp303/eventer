<?php

App::uses('AppModel', 'Model');

class EventsEntry extends AppModel {

  public $useTable = 'events_entries';
  public $actsAs = array('SoftDelete'/*, 'Search.Searchable'*/);

  public $belongsTo = array(
      'Event' => array(
          'className' => 'Event', //関連付けるModel
          'foreignKey' => 'event_id', //関連付けるためのfield、関連付け先は上記Modelのid
          //'fields' => '' //関連付け先Modelの使用field
      ),
      'EventsDetail' => array(
          'className' => 'EventsDetail', //関連付けるModel
          'foreignKey' => 'events_detail_id', //関連付けるためのfield、関連付け先は上記Modelのid
          //'fields' => '' //関連付け先Modelの使用field
      ),
      'User' => array(
          'className' => 'User', //関連付けるModel
          'foreignKey' => 'user_id', //関連付けるためのfield、関連付け先は上記Modelのid
          'fields' => array('username', 'handlename') //関連付け先Modelの使用field
      )
  );

  public $validate = array(
      'title' => array(
          'rule' => 'notBlank',
          'required' => 'create'
      )
  );

  /*public $filtetArgs = array(
      'id' => array('type' => 'value'),
      'title' => array('type' => 'value')
  );*/
}