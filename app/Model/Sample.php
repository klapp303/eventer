<?php

App::uses('AppModel', 'Model');

class Sample extends AppModel {

  public $useTable = 'Samples';
  public $actsAs = array('SoftDelete'/*, 'Search.Searchable'*/);

  /*public $belongsTo = array(
      'SamplesGenre' => array(
          'className' => 'SamplesGenre', //関連付けるModel
          'foreignKey' => 'genre_id', //関連付けるためのfield、関連付け先は上記Modelのid
          'fields' => 'title' //関連付け先Modelの使用field
      )
  );*/

  /*public $validate = array(
      'title' => array(
          'rule' => 'notBlank',
          'required' => 'create'
      ),
      'amount' => array(
          'rule' => 'numeric',
          'required' => false,
          'allowEmpty' => true,
          'message' => '金額を正しく入力してください。'
      )
  );*/

  /*public $filtetArgs = array(
      'id' => array('type' => 'value'),
      'title' => array('type' => 'value')
  );*/
}