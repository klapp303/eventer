<?php
/**
 * AppShell file
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
 * @since         CakePHP(tm) v 2.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * shell の呼び出し
 * cd /home/アカウント名/www/プロジェクト名/app/ ; /usr/local/bin/php /home/アカウント名/www/プロジェクト名/app/Console/cake.php Sample
 * $ php /var/www/lib/Cake/Console/cake.php App main /var/www/app/
 */

App::uses('CakeEmail', 'Network/Email');

/**
 * Application Shell
 *
 * Add your application-wide methods in the class below, your shells
 * will inherit them.
 *
 * @package       app.Console.Command
 */
class EmailShell extends AppShell {
  public $uses = array('Event', 'User', 'EventUser', 'Option'); //使用するModel

  function mmain() {
      $USER_CARBON_OPTION = $this->Option->find('first', array( //オプション値を取得
          'conditions' => array('title' => 'USER_CARBON_KEY')
      ));
      $USER_CARBON_KEY = $USER_CARBON_OPTION['Option']['key'];
      $user_lists = $this->User->find('list', array( //ユーザ一覧を取得
          'conditions' => array('id >' => $USER_CARBON_KEY),
          'fields' => 'id'
      ));
      
      foreach ($user_lists AS $user_list) {
        $join_lists = $this->EventUser->find('list', array(
            'conditions' => array('EventUser.user_id' => $user_list),
            'fields' => 'event_id'
        ));
        $event_lists = $this->Event->find('all', array(
            'conditions' => array(
                'and' => array( //本日のイベントのみ
                    array('or' => array(
                        array('Event.date' => date('Y-m-d')),
                        array('Event.entry_start' => date('Y-m-d')),
                        array('Event.entry_end' => date('Y-m-d')),
                        array('Event.announcement_date' => date('Y-m-d')),
                        array('Event.payment_end' => date('Y-m-d'))
                    )),
                    array('or' => array(
                        array('Event.user_id' => $user_list),
                        array('Event.id' => $join_lists)
                    ))
                )
            )
        ));
        //本日のイベントがあればメール送信
        if ($event_lists) {
          $user_data = $this->User->find('first', array(
              'conditions' => array('id' => $user_list)
          ));
          $email = new CakeEmail('gmail');
          $email->to($user_data['User']['username'])
                ->subject('【イベ幸委員会】'.date('m月d日').'のお知らせ')
                ->template('mail_info', 'eventer_mail')
                ->viewVars(array(
                    'name' => $user_data['User']['handlename'],
                    'event_lists' => $event_lists
                )); //mailに渡す変数
          $email->send();
        }
        //メール送信ここまで
      }
      //$this->out('test.');
  }
}