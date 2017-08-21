<?php
/**
 * cd /home/yumea/www/eventer/app; /usr/local/bin/php /home/yumea/www/eventer/app/Console/cake.php json
 */

//App::uses('CakeEmail', 'Network/Email'); //CakeEmailの利用、分けて記述

class JsonShell extends AppShell
{
    public $uses = array('JsonData'); //使用するModel
    
    public function startup()
    {
        parent::startup();
    }
    
    public function main()
    {
        $this->out('function starts');
        
        //イベント参加データ一覧を更新
        $this->JsonData->saveComparelistJson();
        
        $this->out('function completed');
    }
}
