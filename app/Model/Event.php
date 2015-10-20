<?php

App::uses('AppModel', 'Model');

/**
 * Event Model.
 */
class Event extends AppModel {
  public $useTable = 'Events';
  public $actsAs = array('SoftDelete', 'Search.Searchable');

  public $belongsTo = array(
      'EventGenre' => array(
          'className' => 'EventGenre', //関連付けるModel
          'foreignKey' => 'genre_id', //関連付けるためのfield、関連付け先は上記Modelのid
          'fields' => 'title' //関連付け先Modelの使用field
      ),
      'EventPlace' => array(
          'className' => 'Place', //関連付けるModel
          'foreignKey' => 'place_id', //関連付けるためのfield、関連付け先は上記Modelのid
          'fields' => array('name', 'access') //関連付け先Modelの使用field
      ),
      'EntryGenre' => array(
          'className' => 'EntryGenre', //関連付けるModel
          'foreignKey' => 'entry_id', //関連付けるためのfield、関連付け先は上記Modelのid
          'fields' => 'title' //関連付け先Modelの使用field
      ),
      'UserName' => array(
          'className' => 'User', //関連付けるModel
          'foreignKey' => 'user_id', //関連付けるためのfield、関連付け先は上記Modelのid
          'fields' => 'handlename' //関連付け先Modelの使用field
      )
  );

  public $hasMany = array(
      'UserList' => array(
          'className' => 'EventUser', //関連付けるModel
          'foreignKey' => 'event_id' //関連付けるfield
      )
  );

  public $validate = array(
      'title' => array(
          'rule' => 'notBlank',
          'required' => 'create'
      ),
      'amount' => array(
          'rule' => 'numeric',
          'required' => 'create',
          'message' => '値段を正しく入力してください。'
      ),
      'number' => array(
          'rule' => 'numeric',
          'required' => 'create',
          'message' => '枚数を正しく入力してください。'
      )
  );

  public $filtetArgs = array(
      'title' => array('type' => 'value'),
      'status' => array('type' => 'value')
  );
}