<?php

App::uses('AppController', 'Controller');

class AnalysisController extends AppController
{
    public $uses = array('JsonData', 'Analysis'); //使用するModel
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'eventer_normal';
    }
    
    public function index($mode = false)
    {
        //ユーザ別イベント参加分析データを取得
        $json_data = $this->JsonData->find('first', array(
            'conditions' => array(
                'JsonData.title' => 'analysis_lists',
                'JsonData.user_id' => $this->Auth->user('id')
            )
        ));
        $event_lists = json_decode($json_data['JsonData']['json_data'], true);
        
        //イベント数
        $count_event = 0;
        $count_entry = 0;
        $count_join = 0;
        foreach ($event_lists as $event) {
            $count_event++;
            if ($event['EventsDetail']['status'] == 0) { //検討中
                
            }
            if ($event['EventsDetail']['status'] == 1) { //申込中
                $count_entry++;
            }
            if ($event['EventsDetail']['status'] == 2) { //当選
                $count_entry++;
                $count_join++;
            }
            if ($event['EventsDetail']['status'] == 3) { //落選
                $count_entry++;
            }
            if ($event['EventsDetail']['status'] == 4) { //見送り
                
            }
        }
        $this->set(compact('count_event', 'count_entry', 'count_join'));
        
        //イベント参加データ
        $event_year_lists = $this->Analysis->formatEventListToArray($event_lists, 'year');
        $event_artist_lists = $this->Analysis->formatEventListToArray($event_lists, 'artist');
        $event_place_lists = $this->Analysis->formatEventListToArray($event_lists, 'place');
        $event_music_lists = $this->Analysis->formatEventListToArray($event_lists, 'music');
        $this->set(compact('event_lists', 'event_year_lists', 'event_artist_lists', 'event_place_lists', 'event_music_lists'));
    }
    
    public function update()
    {
        //ユーザ別イベント参加分析データを更新
        $analysis_lists = $this->Analysis->getEventData();
        if ($this->JsonData->saveDataJson($analysis_lists, 'analysis_lists')) {
            $this->Session->setFlash('イベント参加データを更新しました。', 'flashMessage');
        } else {
            $this->Session->setFlash('イベント参加データを更新できませんでした。', 'flashMessage');
        }
        
        $this->redirect('/analysis/index');
    }
}
