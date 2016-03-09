<?php

App::uses('AppController', 'Controller');

class TopController extends AppController {

	public $uses = array('Event'/*, 'EventUser'*/); //使用するModel

  public function beforeFilter() {
      parent::beforeFilter();
      $this->layout = 'eventer_fullwidth';
  }

  public function index() {
      $login_id = $this->Session->read('Auth.User.id'); //何度も使用するので予め取得しておく
      /*$join_lists = $this->EventUser->find('list', array( //参加済みイベントのidを取得
          'conditions' => array('user_id' => $login_id),
          'fields' => 'event_id'
      ));*/
  
      //本日の予定
      /*$event_today_lists = $this->Event->find('all', array(
          'conditions' => array(
              'and' => array(
                  array(
                      'or' => array(
                          array('Event.date' => date('Y-m-d')),
                          array('Event.entry_start' => date('Y-m-d')),
                          array('Event.entry_end' => date('Y-m-d')),
                          array('Event.announcement_date' => date('Y-m-d')),
                          array('Event.payment_end' => date('Y-m-d'))
                      ),
                  ),
                  array(
                      'or' => array(
                          array('Event.user_id' => $login_id),
                          array('Event.id' => $join_lists)
                      )
                  )
              )
          ),
          'order' => array('date' => 'asc')
      ));
      $this->set('event_today_lists', $event_today_lists);*/
  
      //直近の予定
      /*$event_current_lists = $this->Event->find('all', array(
          'conditions' => array(
              'and' => array(
                  array(
                      'or' => array(
                          array(
                              'and' => array(
                                  array('Event.date >' => date('Y-m-d')),
                                  array('Event.date <=' => date('Y-m-d', strtotime('+1 month'))),
                              )
                          ),
                          array(
                              'and' => array(
                                  array('Event.entry_start >' => date('Y-m-d')),
                                  array('Event.entry_start <=' => date('Y-m-d', strtotime('+1 month'))),
                              )
                          ),
                          array(
                              'and' => array(
                                  array('Event.entry_end >' => date('Y-m-d')),
                                  array('Event.entry_end <=' => date('Y-m-d', strtotime('+1 month'))),
                              )
                          ),
                          array(
                              'and' => array(
                                  array('Event.announcement_date >' => date('Y-m-d')),
                                  array('Event.announcement_date <=' => date('Y-m-d', strtotime('+1 month'))),
                              )
                          ),
                          array(
                              'and' => array(
                                  array('Event.payment_end >' => date('Y-m-d')),
                                  array('Event.payment_end <=' => date('Y-m-d', strtotime('+1 month'))),
                              )
                          )
                      )
                  ),
                  array(
                      'or' => array(
                          array('Event.user_id' => $login_id),
                          array('Event.id' => $join_lists)
                      )
                  )
              )
          ),
          'order' => array('date' => 'asc')
      ));
      $this->set('event_current_lists', $event_current_lists);*/
  
      //未対応のイベント
      /*$event_undecided_lists = $this->Event->find('all', array(
          'conditions' => array(
              'and' => array(
                  'Event.date <' => date('Y-m-d'),
                  'Event.status <' => 2,
                  'Event.user_id' => $login_id
              )
          ),
          'order' => array('date' => 'asc')
      ));
      $this->set('event_undecided_lists', $event_undecided_lists);*/
      
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