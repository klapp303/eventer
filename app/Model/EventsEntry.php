<?php

App::uses('AppModel', 'Model');

class EventsEntry extends AppModel {

  public $useTable = 'events_entries';
  public $actsAs = array('SoftDelete'/*, 'Search.Searchable'*/);

  public $belongsTo = array(
      'Event' => array(
          'className' => 'Event', //関連付けるModel
          'foreignKey' => 'event_id', //関連付けるためのfield、関連付け先は上記Modelのid
//          'fields' => '' //関連付け先Modelの使用field
      ),
      'EventsDetail' => array(
          'className' => 'EventsDetail', //関連付けるModel
          'foreignKey' => 'events_detail_id', //関連付けるためのfield、関連付け先は上記Modelのid
//          'fields' => '' //関連付け先Modelの使用field
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

  public function afterFind($results, $primary = false) {
      //エントリーの各日付がどこまで過去のものかを判定
      $entryDateColumn = $this->getDateColumn();
      foreach ($results AS $key => &$entry) {
        $status = 0;
        $entry['EventsEntry']['date_closed'] = $status;
        foreach ($entryDateColumn AS $column) {
          $status++;
          if (@$entry['EventsEntry'][$column] != null && $entry['EventsEntry'][$column] < date('Y-m-d')) {
            $entry['EventsEntry']['date_closed'] = $status;
          }
        }
        //イベントが終了している場合
        if (@$entry['EventsEntry']['date_event'] != null && $entry['EventsEntry']['date_event'] < date('Y-m-d')) {
          if ($entry['EventsEntry']['date_closed'] > 0) { //終了したエントリーの日付が登録されている場合のみ
            $entry['EventsEntry']['date_closed'] = count($entryDateColumn);
          }
        }
      }
      unset($entry);
      
      return $results;
  }

  public function getEventStatus($id = false, $status = -1) {
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
        }
        if ($entry_list['EventsEntry']['status'] == 0 && $status != 1) { //検討中がある場合
          $status = 0;
        }
        if ($entry_list['EventsEntry']['status'] == 3 && $status != 1 && $status != 0) { //落選がある場合
          $status = 3;
        }
        if ($entry_list['EventsEntry']['status'] == 4 && $status != 1 && $status != 0 && $status != 3) { //見送りがある場合
          $status = 4;
        }
      }
      
      //エントリーが無い場合
      if (!$entry_lists) {
        $this->loadModel('EventsDetail');
        $event_data = $this->EventsDetail->find('first', array(
            'conditions' => array('EventsDetail.id' => $id)
        ));
        if ($event_data['EventsDetail']['date'] < date('Y-m-d')) { //過去のイベントは見送り
          $status = 4;
        } else { //未来のイベントは検討中
          $status = 0;
        }
      }
      
      return $status;
  }

  public function searchEntryDate($user_id = false, $s_date = false, $e_date = false, $join_lists = []) {
      //参加済のイベントを取得しておく
      if ($user_id) {
        $this->loadModel('EventUser');
        $join_events = $this->EventUser->getJoinEvents($user_id);
        $join_lists = $this->find('list', array(
            'conditions' => array('EventsEntry.events_detail_id' => $join_events['id']),
            'fields' => 'EventsEntry.id'
        ));
      }
      
      if ($s_date) {
        $s_date = date('Y-m-d 00:00:00', strtotime($s_date));
      } else {
        $s_date = date('Y-m-d 00:00:00');
      }
      if ($e_date) {
        $e_date = date('Y-m-d 23:59:59', strtotime($e_date));
      } else {
        $e_date = date('Y-m-d 23:59:59');
      }
      
      $entry_lists = $this->find('all', array(
          'conditions' => array(
              'or' => array(
                  //参加済のイベントの場合は開催日時のみ
                  array(
                      'EventsEntry.id' => $join_lists,
                      'or' => array(
                          array(
                              'and' => array(
                                  'EventsEntry.date_event >=' => $s_date,
                                  'EventsEntry.date_event <=' => $e_date
                              )
                          )
                      ),
                  ),
                  array(
                      'EventsEntry.user_id' => $user_id,
                      'or' => array(
                          array(
                              'and' => array(
                                  'EventsEntry.date_start >=' => $s_date,
                                  'EventsEntry.date_start <=' => $e_date
                              )
                          ),
                          array(
                              'and' => array(
                                  'EventsEntry.date_close >=' => $s_date,
                                  'EventsEntry.date_close <=' => $e_date,
                                  'EventsEntry.status' => array(0, 4)
                              )
                          ),
                          array(
                              'and' => array(
                                  'EventsEntry.date_result >=' => $s_date,
                                  'EventsEntry.date_result <=' => $e_date
                              )
                          ),
                          array(
                              'and' => array(
                                  'EventsEntry.date_payment >=' => $s_date,
                                  'EventsEntry.date_payment <=' => $e_date,
                                  'EventsEntry.payment !=' => 'credit'
                              )
                          ),
                          array(
                              'and' => array(
                                  'EventsEntry.date_event >=' => $s_date,
                                  'EventsEntry.date_event <=' => $e_date
                              )
                          )
                      )
                  ),
              ),
              'EventsDetail.deleted !=' => 1
          ),
          'order' => array('EventsDetail.date' => 'asc', 'EventsDetail.time_start' => 'asc')
      ));
      
      return $entry_lists;
  }

  public function getDateColumn($sort = null) {
      $data = array(
          '申込開始' => 'date_start',
          '申込終了' => 'date_close',
          '当落発表' => 'date_result',
          '入金締切' => 'date_payment'
      );
      
      if ($sort == 'reverse') {
        $data = array_reverse($data);
        return $data;
      } else {
        return $data;
      }
  }
}
