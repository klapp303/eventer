<?php

App::uses('AppController', 'Controller');

class PagesController extends AppController
{
    public $uses = array('User', 'EventGenre', 'EntryGenre'); //使用するModel
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'eventer_fullwidth';
    }
    
    public function index()
    {
        
    }
    
    public function about()
    {
        
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
        
    }
    
    public function faq()
    {
        
    }
    
    public function author()
    {
        
    }
}
