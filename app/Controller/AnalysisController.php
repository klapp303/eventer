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
    
    public function index()
    {
        //ユーザ別イベント参加分析データを取得
        $json_data = $this->JsonData->find('first', array(
            'conditions' => array(
                'JsonData.title' => 'analysis_lists',
                'JsonData.user_id' => $this->Auth->user('id')
            )
        ));
        $event_lists = json_decode($json_data['JsonData']['json_data'], true);
        if ($this->Session->check('Auth.analysis')) {
            $this->Session->delete('Auth.analysis');
        }
        $this->Session->write('Auth.analysis', $event_lists);
        
        //イベント数
        $event_counts = $this->Analysis->countEventFromEventList($event_lists);
        $this->set(compact('event_counts'));
        
        //イベント参加データ
        $event_year_lists = $this->Analysis->formatEventListToArray($event_lists, 'year');
        $event_artist_lists = $this->Analysis->formatEventListToArray($event_lists, 'artist');
        $event_place_lists = $this->Analysis->formatEventListToArray($event_lists, 'place');
        $event_music_lists = $this->Analysis->formatEventListToArray($event_lists, 'music');
        $this->set(compact('event_year_lists', 'event_artist_lists', 'event_place_lists', 'event_music_lists'));
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
    
    public function detail()
    {
        //ユーザ別イベント参加分析データを取得
        if ($this->Session->check('Auth.analysis')) {
            $event_lists = $this->Session->read('Auth.analysis');
        } else {
            $json_data = $this->JsonData->find('first', array(
                'conditions' => array(
                    'JsonData.title' => 'analysis_lists',
                    'JsonData.user_id' => $this->Auth->user('id')
                )
            ));
            $event_lists = json_decode($json_data['JsonData']['json_data'], true);
            $this->Session->write('Auth.analysis', $event_lists);
        }
        
        //パラメータで詳細ページの表示を分ける
        if (isset($this->params->query['year']) == true) {
            $year = $this->params->query['year'];
            $mode = 'year';
            $format_event_lists = $this->Analysis->formatEventListToArray($event_lists, 'year');
            $event_lists = array();
            //任意の年のみを取得
            if (@$format_event_lists[$year . '年']) {
                $event_lists = $format_event_lists[$year . '年'];
                unset($event_lists['analysis']);
            }
            
        } elseif (isset($this->params->query['artist']) == true) {
            $artist = $this->params->query['artist'];
            $mode = 'artist';
            $format_event_lists = $this->Analysis->formatEventListToArray($event_lists, 'artist');
            $event_lists = array();
            foreach ($format_event_lists as $key => $val) {
                if ($val['analysis']['artist']['id'] == $artist) {
                    $event_lists = $format_event_lists[$key];
                    unset($event_lists['analysis']);
                }
            }
            
        } elseif (isset($this->params->query['place']) == true) {
            $place = $this->params->query['place'];
            $mode = 'place';
            $format_event_lists = $this->Analysis->formatEventListToArray($event_lists, 'place');
            $event_lists = array();
            foreach ($format_event_lists as $key => $val) {
                if ($key == $place) {
                    $event_lists = $format_event_lists[$key];
                    unset($event_lists['analysis']);
                }
            }
               
        } else {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        $this->set('mode', $mode);
        
        //イベント数
        $event_counts = $this->Analysis->countEventFromEventList($event_lists);
        $this->set(compact('event_counts'));
        
        //イベント参加データ
        if ($mode == 'year') {
            $event_artist_lists = $this->Analysis->formatEventListToArray($event_lists, 'artist');
            $event_place_lists = $this->Analysis->formatEventListToArray($event_lists, 'place');
            $event_music_lists = $this->Analysis->formatEventListToArray($event_lists, 'music');
        } elseif ($mode == 'artist') {
            $event_year_lists = $this->Analysis->formatEventListToArray($event_lists, 'year');
            $event_place_lists = $this->Analysis->formatEventListToArray($event_lists, 'place');
            $event_music_lists = $this->Analysis->formatEventListToArray($event_lists, 'music');
        } elseif ($mode == 'place') {
            $event_year_lists = $this->Analysis->formatEventListToArray($event_lists, 'year');
            $event_artist_lists = $this->Analysis->formatEventListToArray($event_lists, 'artist');
            $event_music_lists = $this->Analysis->formatEventListToArray($event_lists, 'music');
        }
        $this->set(compact('event_year_lists', 'event_artist_lists', 'event_place_lists', 'event_music_lists'));
        
        $this->render('index');
    }
}
