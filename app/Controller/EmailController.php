<?php

App::uses('AppController', 'Controller');

class EmailController extends AppController
{
    public $uses = array('User', 'EventsEntry'); //使用するModel
    
    public function beforeFilter()
    {
        parent::beforeFilter();
//        $this->layout = 'eventer_normal';
    }
    
//    public function index()
//    {
//        
//    }
    
    public function schedule_send($id = null)
    {
        if ($this->request->is('post')) {
            if ($id <= $this->Option->getOptionKey('USER_CARBON_KEY')) {
                $this->Session->setFlash('メール送信のできないユーザです。', 'flashMessage');
                
                $this->redirect('/');
            }
            
            //本日の予定を取得
            $event_lists = $this->EventsEntry->searchEntryDate($id, date('Y-m-d'), date('Y-m-d'));
            foreach ($event_lists as $key => &$event) {
                if ($event['EventsEntry']['status'] == 3 || $event['EventsEntry']['status'] == 4) { //落選 or 見送りならば除外する
                    unset($event_lists[$key]);
                }
                $event['EventsEntry']['date_status'] = null;
                $entryDateColumn = $this->EventsEntry->getDateColumn();
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
                $user = $this->User->find('first', array('conditions' => array('User.id' => $id)));
                $email = new CakeEmail('sakura');
                $email->to($user['User']['username'])
                        ->subject('【イベ幸】イベント予定のお知らせ')
                        ->template('event_schedule', 'eventer_mail')
                        ->viewVars(array(
                            'name' => ($user['User']['handlename']) ? $user['User']['handlename'] : $user['User']['username'],
                            'event_lists' => $event_lists,
                            'entryDateColumn' => $entryDateColumn
                )); //mailに渡す変数
                if ($email->send()) {
                    $this->Session->setFlash('メールに予定を送信しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash('メールを送信できませんでした。', 'flashMessage');
                }
            } else {
                $this->Session->setFlash('現在は予定がありません。', 'flashMessage');
            }
            
            $this->redirect('/');
        }
    }
}
