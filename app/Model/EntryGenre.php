<?php

App::uses('AppModel', 'Model');

class EntryGenre extends AppModel {

  public $useTable = 'entry_genres';
  public $actsAs = array('SoftDelete');

  /*public $validate = array(
      'title' => array(
          'rule' => 'notBlank',
          'required' => 'create'
      ),
      'amount' => array(
          'rule' => 'numeric',
          'required' => false,
          'allowEmpty' => true,
          'message' => '値段を正しく入力してください。'
      )
  );*/
}
