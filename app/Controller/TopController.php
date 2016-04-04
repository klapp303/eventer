<?php

App::uses('AppController', 'Controller');

class TopController extends AppController {

	public $uses = array('Event', 'EventsDetail', 'EventsEntry'/*, 'EventUser'*/, 'Option'); //使用するModel

  public function beforeFilter() {
      parent::beforeFilter();
      $this->layout = 'eventer_fullwidth';
  }

  public function index() {
      /*$join_lists = $this->EventUser->find('list', array( //参加済みイベントのidを取得
          'conditions' => array('user_id' => $login_id),
          'fields' => 'event_id'
      ));*/
  
      //本日の予定
      $event_today_lists = $this->EventsEntry->searchEntryDate($this->Auth->user('id'));
      foreach ($event_today_lists AS $key => &$event) {
        if ($event['EventsEntry']['status'] == 3 || $event['EventsEntry']['status'] == 4) { //落選 or 見送りならば表示しない
          unset($event_today_lists[$key]);
        }
        $event['EventsEntry']['date_status'] = null;
        $entryDateColumn = $this->EventsEntry->getDateColumn();
        foreach ($entryDateColumn AS $key => $column) {
          if (date('Y-m-d', strtotime($event['EventsEntry'][$column])) == date('Y-m-d')) {
            $event['EventsEntry']['date_status'] = $key;
          }
        }
        if (date('Y-m-d', strtotime($event['EventsEntry']['date_event'])) == date('Y-m-d')) {
          $event['EventsEntry']['date_status'] = '本日開演';
        }
      }
      unset($event);
      $this->set('event_today_lists', $event_today_lists);
  
      //直近の予定
      $CURRENT_EVENT_OPTION = $this->Option->find('first', array( //オプション値を取得
          'conditions' => array('Option.title' => 'CURRENT_EVENT_KEY')
      ));
      $CURRENT_EVENT_KEY = $CURRENT_EVENT_OPTION['Option']['key'];
      $event_current_lists = $this->EventsEntry->searchEntryDate($this->Auth->user('id'), date('Y-m-d', strtotime('1 day')), date('Y-m-d', strtotime($CURRENT_EVENT_KEY.' day')));
      foreach ($event_current_lists AS $key => $event) {
        if ($event['EventsEntry']['status'] == 3 || $event['EventsEntry']['status'] == 4) { //落選 or 見送りならば表示しない
          unset($event_current_lists[$key]);
        }
      }
      $this->set('event_current_lists', $event_current_lists);
      
      //未対応の収支
      /*$budget_undecided_lists = $this->EventUser->find('all', array(
          'conditions' => array(
              'EventDetail.user_id' => $login_id,
              'EventUser.payment' => 0,
              'EventDetail.deleted !=' => 1 //紐付くテーブルのSoftDeleteは無視されるので記述
          ),
          'order' => array('EventDetail.date' => 'asc')
      ));
      $budget_undecided_count = count($budget_undecided_lists);
      $this->set('budget_undecided_count', $budget_undecided_count);*/
  
//      $this->Paginator->settings = array( //eventsページのイベント一覧を設定
//          'conditions' => array(
//              'and' => array(
//                  'date <' => date('Y-m-d'), //過去のイベントを取得
//                  'or' => array(
//                      array('Event.user_id' => $login_id),
//                      array('Event.id' => $join_lists)
//                  )
//              )
//          ),
//          'order' => array('date' => 'desc')
//      );
//      $event_lists = $this->Paginator->paginate('Event');
  }
}