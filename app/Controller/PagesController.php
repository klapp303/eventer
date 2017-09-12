<?php

App::uses('AppController', 'Controller');

class PagesController extends AppController
{
    public $uses = array('User', 'EventGenre', 'EntryGenre', 'Page'); //使用するModel
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'eventer_normal';
        $this->Auth->allow('about');
    }
    
    public function index()
    {
        
    }
    
    public function about()
    {
        //breadcrumbの設定
        $this->set('sub_page', '詳しい機能と使い方');
        
        if (!$this->Auth->user()) {
            $this->layout = 'eventer_simple';
            
            //ゲストアカウント情報を取得
            $guest_user = $this->User->find('first', array(
                'conditions' => array('User.id' => $this->Option->getOptionKey('GUEST_USER_KEY'))
            ));
            $this->set('guest_name', $guest_user['User']['username']);
            $this->set('guest_password', 'password');
        }
    }
    
    public function event_genres()
    {
        //breadcrumbの設定
        $this->set('sub_page', 'イベント種類一覧');
        
        $event_genre_lists = $this->EventGenre->find('all', array(
            'order' => array('EventGenre.id' => 'asc')
        ));
        $this->set('event_genre_lists', $event_genre_lists);
    }
    
    public function entry_genres()
    {
        //breadcrumbの設定
        $this->set('sub_page', 'エントリー方法一覧');
        
        $entry_genre_lists = $this->EntryGenre->find('all', array(
            'order' => array('EntryGenre.id' => 'asc')
        ));
        $this->set('entry_genre_lists', $entry_genre_lists);
    }
    
    public function history()
    {
        //breadcrumbの設定
        $this->set('sub_page', 'お知らせ、更新履歴');
        
        $this->set('array_history', $this->Page->getArrayHistory());
    }
    
    public function faq()
    {
        //breadcrumbの設定
        $this->set('sub_page', 'よくあるご質問（FAQ）');
        
        $this->set('array_faq', $this->Page->getArrayFaq());
    }
    
    public function author()
    {
        //breadcrumbの設定
        $this->set('sub_page', 'お問い合わせ、製作者について');
    }
}
