<?php

App::uses('AppModel', 'Model');

/**
 * Event Model.
 */
class Event extends AppModel {
  public $useTable = 'Events';
  public $actAs = ['SoftDelete'/*, 'Search.Searchable'*/];

  /*public $belongsTo = array(
      'EventsGenre' => array(
          'className' => 'EventsGenre', //関連付けるModel
          'foreignKey' => 'genre_id', //関連付けるためのfield、関連付け先は上記Modelのid
          'fields' => 'title' //関連付け先Modelの使用field
      )
  );*/

  public $validate = array(
      'title' => array(
          'rule' => 'notBlank',
          'required' => 'true'
      ),
      'amount' => array(
          'rule' => 'numeric',
          'required' => 'true',
          'message' => '値段を正しく入力してください。'
      )
  );

//  public $filtetArgs = ['' => ['' => '', '' => '']];
}