<?php
/**
 * cd /home/yumea/www/eventer/app; /usr/local/bin/php /home/yumea/www/eventer/app/Console/cake.php json
 */

//App::uses('CakeEmail', 'Network/Email'); //CakeEmailの利用、分けて記述

class JsonShell extends AppShell
{
    public $uses = array('JsonData', 'User', 'Analysis', 'Artist'); //使用するModel
    
    public function startup()
    {
        parent::startup();
    }
    
    public function main()
    {
        $this->out('function starts');
        
        $users = $this->User->find('list', array(
            'conditions' => array('User.id !=' => array(1, 2)),
            'fields' => 'User.id'
        ));
        foreach ($users as $user_id) {
            //ユーザ別イベント参加分析データを更新
            $analysis_lists = $this->Analysis->getEventData($user_id);
            $this->JsonData->saveDataJson($analysis_lists, 'analysis_lists', $user_id);
            //アーティスト別イベント参加データ一覧を更新
            $compare_lists = $this->Artist->getComparelist($user_id);
            $this->JsonData->saveDataJson($compare_lists, 'artists_compare_lists', $user_id);
        }
        
        $this->out('function completed');
    }
}
