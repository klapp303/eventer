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
      ),
      'EntryGenre' => array(
          'className' => 'EntryGenre', //関連付けるModel
          'foreignKey' => 'entries_genre_id', //関連付けるためのfield、関連付け先は上記Modelのid
          'fields' => 'title' //関連付け先Modelの使用field
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

  public function getEventStatus($id = false, $status = 0) {
      $entry_lists = $this->find('all', array(
          'conditions' => array(
              'EventsEntry.events_detail_id' => $id
          )
      ));
      foreach ($entry_lists AS $entry_list) {
        if ($entry_list['EventsEntry']['status'] == 2) { //当選がある場合
          $status = 2;
          break;
        }
        if ($entry_list['EventsEntry']['status'] == 1) { //申込中がある場合
          $status = 1;
          break;
        }
        if ($entry_list['EventsEntry']['status'] == 0) { //検討中がある場合
          $status = 0;
          break;
        }
        if ($entry_list['EventsEntry']['status'] == 3) { //落選がある場合
          $status = 3;
          break;
        }
        if ($entry_list['EventsEntry']['status'] == 4) { //見送りがある場合
          $status = 4;
          break;
        }
      }
      
      return $status;
  }
}