<?php

App::uses('AppModel', 'Model');

/**
 * Place Model.
 */
class Place extends AppModel {
  public $useTable = 'Places';
  public $actsAs = array('SoftDelete'/*, 'Search.Searchable'*/);

  /*public $belongsTo = array(
      'SamplesGenre' => array(
          'className' => 'SamplesGenre', //関連付けるModel
          'foreignKey' => 'genre_id', //関連付けるためのfield、関連付け先は上記Modelのid
          'fields' => 'title' //関連付け先Modelの使用field
      )
  );*/

  public $validate = array(
      'name' => array(
          'rule' => 'notBlank',
          'required' => 'true'
      ),
      'access' => array(
          'rule' => 'notBlank',
          'required' => 'true'
      ),
      'capacity' => array(
          'rule' => 'numeric',
          'required' => 'true',
          'message' => '人数を正しく入力してください。'
      )
  );

//  public $filtetArgs = ['' => ['' => '', '' => '']];
}