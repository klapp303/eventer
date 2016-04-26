<?php

App::uses('AppController', 'Controller');

class TopController extends AppController {

  public $uses = array('Event', 'EventsDetail', 'EventsEntry'/*, 'EventUser'*/, 'Option'); //使用するModel

  public function beforeFilter() {
      parent::beforeFilter();
      $this->layout = 'eventer_fullwidth';
  }

  public function index() {
      //参加済のイベント一覧を取得しておく
//      $join_lists = $this->EventUser->getJoinEvents($this->Auth->user('id'));
  
      //未対応の件数
      $unfixed_payment_lists = $this->EventsDetail->getUnfixedPayment($this->Auth->user('id'));
      $unfixed_sales_lists = $this->EventsDetail->getUnfixedSales($this->Auth->user('id'));
      $unfixed_collect_lists = $this->EventsDetail->getUnfixedCollect($this->Auth->user('id'));
      $this->set(compact('unfixed_payment_lists', 'unfixed_sales_lists', 'unfixed_collect_lists'));
      
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
          'conditions' => array('Option.title' => 'CURRENT_EVENT_KEY'),
          'fields' => 'Option.key'
      ));
      $CURRENT_EVENT_KEY = $CURRENT_EVENT_OPTION['Option']['key'];
      $event_current_lists = $this->EventsEntry->searchEntryDate($this->Auth->user('id'), date('Y-m-d', strtotime('1 day')), date('Y-m-d', strtotime($CURRENT_EVENT_KEY.' day')));
      foreach ($event_current_lists AS $key => $event) {
        if ($event['EventsEntry']['status'] == 3 || $event['EventsEntry']['status'] == 4) { //落選 or 見送りならば表示しない
          unset($event_current_lists[$key]);
        }
      }
      $this->set('event_current_lists', $event_current_lists);
  }
}
