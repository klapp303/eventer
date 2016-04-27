<?php

App::uses('AppModel', 'Model');

class EventsDetail extends AppModel {

  public $useTable = 'events_details';
  public $actsAs = array('SoftDelete', 'Containable'/*, 'Search.Searchable'*/);

  public $belongsTo = array(
      'Event' => array(
          'className' => 'Event', //関連付けるModel
          'foreignKey' => 'event_id', //関連付けるためのfield、関連付け先は上記Modelのid
          //'fields' => '' //関連付け先Modelの使用field
      ),
      'User' => array(
          'className' => 'User', //関連付けるModel
          'foreignKey' => 'user_id', //関連付けるためのfield、関連付け先は上記Modelのid
          'fields' => array('username', 'handlename') //関連付け先Modelの使用field
      ),
      'EventGenre' => array(
          'className' => 'EventGenre', //関連付けるModel
          'foreignKey' => 'genre_id', //関連付けるためのfield、関連付け先は上記Modelのid
          'fields' => 'title' //関連付け先Modelの使用field
      ),
      'Place' => array(
          'className' => 'Place', //関連付けるModel
          'foreignKey' => 'place_id', //関連付けるためのfield、関連付け先は上記Modelのid
          'fields' => array('name', 'access') //関連付け先Modelの使用field
      )
  );

  public $hasMany = array(
      'EventsEntry' => array(
          'className' => 'EventsEntry', //関連付けるModel
          'foreignKey' => 'events_detail_id' //関連付けるfield
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

  public function getUnfixedPayment($user_id = false, $status = 0, $limit = 20, $data = ['list' => [], 'count' => 0]) {
      $event_lists = $this->find('all', array(
          'conditions' => array(
              'EventsDetail.user_id' => $user_id,
              'EventsDetail.deleted !=' => 1
          ),
          'order' => array('EventsDetail.date' => ($status == 0)? 'asc': 'desc', 'EventsDetail.time_start' => 'asc')
      ));
      
      $this->loadModel('EventsEntry');
      foreach ($event_lists AS $key => $event) {
        $entry_lists = $this->EventsEntry->find('all', array(
            'conditions' => array(
                'EventsEntry.events_detail_id' => $event['EventsDetail']['id'],
                'EventsEntry.user_id' => $user_id,
                'EventsEntry.price >' => 0,
                array(
                    'or' => array(
                        'EventsEntry.payment !=' => 'credit',
                        'EventsEntry.payment' => null,
                    )
                ),
                'EventsEntry.payment_status' => $status,
                'EventsEntry.status' => 2,
                array(
                    'or' => array(
                        'EventsDetail.date >=' => date('Y-m-d'),
                        'EventsEntry.payment' => 'buy'
                    ),
                )
            )
        ));
        //未払いのエントリーがなければリストから削除
        if (!$entry_lists) {
          unset($event_lists[$key]);
        //未払いのエントリーがあれば未払いのみリストに残す
        } else {
          unset($event_lists[$key]['EventsEntry']);
          foreach ($entry_lists AS $entry) {
            $event_lists[$key]['EventsEntry'][] = $entry['EventsEntry'];
          }
          $data['count'] += count($entry_lists);
        }
      }
      
      //keyを振り直して整形
      $event_lists = array_merge($event_lists);
      //paginatorを使用しないので表示できる件数を現実的な値にしておく
      if ($data['count'] > $limit) {
        array_splice($event_lists, $limit);
      }
      $data['list'] = $event_lists;
      
      return $data;
  }

  public function getUnfixedSales($user_id = false, $status = 0, $limit = 20, $data = ['list' => [], 'count' => 0]) {
      $event_lists = $this->find('all', array(
          'conditions' => array(
              'EventsDetail.user_id' => $user_id,
              'EventsDetail.date >=' => date('Y-m-d'),
              'EventsDetail.deleted !=' => 1
          ),
          'order' => array('EventsDetail.date' => ($status == 0)? 'asc': 'desc', 'EventsDetail.time_start' => 'asc')
      ));
      
      $this->loadModel('EventsEntry');
      foreach ($event_lists AS $key => &$event) {
        $entry_lists = $this->EventsEntry->find('all', array(
            'conditions' => array(
                'EventsEntry.events_detail_id' => $event['EventsDetail']['id'],
                'EventsEntry.user_id' => $user_id,
                'EventsEntry.sales_status' => $status,
                'EventsEntry.status' => 2
            )
        ));
        //当選枚数が1枚以下ならばリストから削除
        $event['number'] = 0;
        foreach ($entry_lists AS $entry) {
          $event['number'] += $entry['EventsEntry']['number'];
        }
        if ($event['number'] <= 1) {
          unset($event_lists[$key]);
        //当選枚数が2枚以上ならば当選したエントリーのみ残す
        } else {
          unset($event_lists[$key]['EventsEntry']);
          foreach ($entry_lists AS $entry) {
            $event_lists[$key]['EventsEntry'][] = $entry['EventsEntry'];
          }
          $data['count'] += count($entry_lists);
        }
      }
      unset($event);
      $data['count'] = count($event_lists);
      
      //keyを振り直して整形
      $event_lists = array_merge($event_lists);
      //paginatorを使用しないので表示できる件数を現実的な値にしておく
      if ($data['count'] > $limit) {
        array_splice($event_lists, $limit);
      }
      $data['list'] = $event_lists;
      
      return $data;
  }

  public function getUnfixedCollect($user_id = false, $status = 0, $limit = 20, $data = ['list' => [], 'count' => 0]) {
      $event_lists = $this->find('all', array(
          'conditions' => array(
              'EventsDetail.user_id' => $user_id,
              'EventsDetail.deleted !=' => 1
          ),
          'order' => array('EventsDetail.date' => ($status == 0)? 'asc': 'desc', 'EventsDetail.time_start' => 'asc')
      ));
      
      $this->loadModel('EventsEntry');
      foreach ($event_lists AS $key => $event) {
        $entry_lists = $this->EventsEntry->find('all', array(
            'conditions' => array(
                'EventsEntry.events_detail_id' => $event['EventsDetail']['id'],
                'EventsEntry.user_id' => $user_id,
                'EventsEntry.sales_status' => 1,
                'EventsEntry.collect_status' => $status,
                'EventsEntry.status' => 2
            )
        ));
        //未回収のエントリーがなければリストから削除
        if (!$entry_lists) {
          unset($event_lists[$key]);
        //未回収のエントリーがあれば未回収のみリストに残す
        } else {
          unset($event_lists[$key]['EventsEntry']);
          foreach ($entry_lists AS $entry) {
            $event_lists[$key]['EventsEntry'][] = $entry['EventsEntry'];
          }
          $data['count'] += count($entry_lists);
        }
      }
      
      //keyを振り直して整形
      $event_lists = array_merge($event_lists);
      //paginatorを使用しないので表示できる件数を現実的な値にしておく
      if ($data['count'] > $limit) {
        array_splice($event_lists, $limit);
      }
      $data['list'] = $event_lists;
      
      return $data;
  }
}
