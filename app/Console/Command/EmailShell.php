<?php

App::uses('CakeEmail', 'Network/Email'); //CakeEmaiilの利用、分けて記述

class EmailShell extends AppShell
{
    public $uses = array('User', 'EventsEntry'); //使用するModel
    
    public function startup()
    {
        parent::startup();
    }
    
    public function main()
    {
        $this->out('function starts');
        
        $user_lists = $this->User->find('all', array(
            'conditions' => array(
                'User.id !=' => 1,
                'User.mail' => 1
            )
        ));
        
        foreach ($user_lists as $user) {
            //3日分の予定を取得
            $event_lists = $this->EventsEntry->searchEntryDate($user['User']['id'], date('Y-m-d'), date('Y-m-d', strtotime('2 day')));
            foreach ($event_lists as $key => &$event) {
                if ($event['EventsEntry']['status'] == 3 || $event['EventsEntry']['status'] == 4) { //落選 or 見送りならば除外する
                    unset($event_lists[$key]);
                }
                $event['EventsEntry']['date_status'] = null;
                $entryDateColumn = $this->EventsEntry->getDateColumn($sort = 'reverse');
                foreach ($entryDateColumn as $key => $column) {
                    if ($event['EventsEntry'][$column] >= date('Y-m-d 00:00:00') && $event['EventsEntry'][$column] <= date('Y-m-d 23:59:59', strtotime('2 day'))) {
                        $event['EventsEntry']['date_status'] = $key;
                    }
                }
                if ($event['EventsEntry']['date_event'] >= date('Y-m-d 00:00:00') && $event['EventsEntry']['date_event'] <= date('Y-m-d 23:59:59', strtotime('2 day'))) {
                    $event['EventsEntry']['date_status'] = '近日開催';
                }
                if (date('Y-m-d', strtotime($event['EventsEntry']['date_event'])) == date('Y-m-d')) {
                    $event['EventsEntry']['date_status'] = '本日開演';
                }
            }
            unset($event);
            
            //イベントの予定があればメールを送信
            if ($event_lists) {
//                $user = $this->User->find('first', array('conditions' => array('User.id' => $id)));
                $email = new CakeEmail('gmail');
                $email->to($user['User']['username'])
                    ->subject('【イベ幸】イベント予定のお知らせ')
                    ->template('event_schedule', 'eventer_mail')
                    ->viewVars(array(
                        'name' => ($user['User']['handlename']) ? $user['User']['handlename'] : $user['User']['username'],
                        'event_lists' => $event_lists,
                        'entryDateColumn' => $entryDateColumn
                    )); //mailに渡す変数
                if ($email->send()) {
//                    $this->out('send email');
                } else {
                    $this->out('function not complete #user_id = ' . $user['User']['id']);
                }
            } else {
//                $this->out('no objects');
            }
        }
        
        $this->out('function completed');
    }
}
