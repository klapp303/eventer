<?php

App::uses('AppModel', 'Model');

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
          'rule_1' => array(
              'rule' => 'notBlank',
              'required' => 'create'
          ),
          'rule_2' => array(
              'rule' => array('maxLength', 25),
              'message' => '会場名は25文字以内です'
          )
      ),
      'access' => array(
          'rule' => 'notBlank',
          'required' => 'create'
      ),
      'capacity' => array(
          'rule' => 'numeric',
          'required' => false,
          'allowEmpty' => true,
          'message' => '人数を正しく入力してください。'
      )
  );

  /*public $filtetArgs = array(
      'id' => array('type' => 'value'),
      'title' => array('type' => 'value')
  );*/
}