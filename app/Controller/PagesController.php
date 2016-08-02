<?php

App::uses('AppController', 'Controller');

class PagesController extends AppController
{
    public $uses = array('User', 'EventGenre', 'EntryGenre', 'Page'); //使用するModel
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'eventer_fullwidth';
        $this->Auth->allow('about');
    }
    
    public function index()
    {
        
    }
    
    public function about()
    {
        if (!$this->Auth->user()) {
            $this->layout = 'eventer_normal';
            
            //ゲストアカウント情報を取得
            $guest_user = $this->User->find('first', array(
                'conditions' => array('User.id' => 2)
            ));
            $this->set('guest_name', $guest_user['User']['username']);
            $this->set('guest_password', 'password');
        }
    }
    
    public function event_genres()
    {
        $event_genre_lists = $this->EventGenre->find('all', array(
            'order' => array('EventGenre.id' => 'asc')
        ));
        $this->set('event_genre_lists', $event_genre_lists);
    }
    
    public function entry_genres()
    {
        $entry_genre_lists = $this->EntryGenre->find('all', array(
            'order' => array('EntryGenre.id' => 'asc')
        ));
        $this->set('entry_genre_lists', $entry_genre_lists);
    }
    
    public function history()
    {
        $this->set('array_history', $this->Page->getArrayHistory());
    }
    
    public function faq()
    {
        $this->set('array_faq', $this->Page->getArrayFaq());
    }
    
    public function author()
    {
        
    }
}
