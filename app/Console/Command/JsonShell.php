<?php
/**
 * cd /home/yumea/www/eventer/app; /usr/local/bin/php /home/yumea/www/eventer/app/Console/cake.php json
 */

//App::uses('CakeEmail', 'Network/Email'); //CakeEmailの利用、分けて記述

class JsonShell extends AppShell
{
    public $uses = array('JsonData', 'User'); //使用するModel
    
    public function startup()
    {
        parent::startup();
    }
    
    public function main()
    {
        $this->out('function starts');
        
        //イベント参加データ一覧を更新
        $users = $this->User->find('list', array(
            'conditions' => array('User.id !=' => array(1, 2)),
            'fields' => 'User.id'
        ));
        foreach ($users as $user_id) {
            $this->JsonData->saveComparelistJson($user_id);
        }
        
        $this->out('function completed');
    }
}
