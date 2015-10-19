<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class TopController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('Event', 'EventUser'); //使用するModel

/**
 * Displays a view
 *
 * @return void
 * @throws NotFoundException When the view file could not be found
 *	or MissingViewException in debug mode.
 */

  public function beforeFilter() {
      parent::beforeFilter();
      $this->layout = 'eventer_fullwidth';
  }

  public function index() {
      $login_id = $this->Session->read('Auth.User.id'); //何度も使用するので予め取得しておく
      $join_lists = $this->EventUser->find('list', array( //参加済みイベントのidを取得
          'conditions' => array('user_id' => $login_id),
          'fields' => 'event_id'
      ));

      //本日の予定
      $event_today_lists = $this->Event->find('all', array(
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
      $this->set('event_today_lists', $event_today_lists);

      //直近の予定
      $event_current_lists = $this->Event->find('all', array(
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
      $this->set('event_current_lists', $event_current_lists);

      //未対応のイベント
      $event_undecided_lists = $this->Event->find('all', array(
          'conditions' => array(
              'and' => array(
                  'Event.date <' => date('Y-m-d'),
                  'Event.status <' => 2,
                  'Event.user_id' => $login_id
              )
          ),
          'order' => array('date' => 'asc')
      ));
      $this->set('event_undecided_lists', $event_undecided_lists);
      
      //未対応の収支
      $budget_undecided_lists = $this->EventUser->find('all', array(
          'conditions' => array(
              'EventDetail.user_id' => $login_id,
              'EventUser.payment' => 0,
              'EventDetail.deleted !=' => 1 //紐付くテーブルのSoftDeleteは無視されるので記述
          ),
          'order' => array('EventDetail.date' => 'asc')
      ));
      $budget_undecided_count = count($budget_undecided_lists);
      $this->set('budget_undecided_count', $budget_undecided_count);

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