<?php

App::uses('AppController', 'Controller');

class AnalysisController extends AppController
{
    public $uses = array('JsonData', 'Analysis', 'Option'); //使用するModel
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'eventer_normal';
    }
    
    public function index($year = false)
    {
        $MIN_YEAR_KEY = $this->Option->getOptionKey('MIN_YEAR_KEY');
        if (!$year) {
            $year = date('Y');
        }
        if ($year < $MIN_YEAR_KEY || $year > date('Y')) {
            $this->redirect('/analysis/index');
        }
        
        //ユーザ別イベント参加データを取得
        $json_data = $this->JsonData->find('first', array(
            'conditions' => array(
                'JsonData.title' => 'analysis_lists',
                'JsonData.user_id' => $this->Auth->user('id'),
                'JsonData.year' => $year
            )
        ));
        if (!$json_data) {
            $this->Session->setFlash('イベント参加データが見つかりませんでした。', 'flashMessage');
            $event_lists = array();
        } else {
            $event_lists = json_decode($json_data['JsonData']['json_data'], true);
        }
//        if ($this->Session->check('Auth.analysis')) {
//            $this->Session->delete('Auth.analysis');
//        }
//        $this->Session->write('Auth.analysis', $event_lists);
//        echo'<pre>';print_r($event_lists);echo'</pre>';exit;
        //イベント数
        $event_counts = $this->Analysis->countEventFromEventList($event_lists);
        $event_counts['percent']['live'] = @round($event_counts['live'] / $event_counts['join'] *100, 1);
        $event_counts['percent']['release'] = @round($event_counts['release'] / $event_counts['join'] *100, 1);
        $event_counts['percent']['talk'] = @round($event_counts['talk'] / $event_counts['join'] *100, 1);
        $this->set(compact('event_counts'));
        
        //前年のイベント参加データも取得しておく
        $json_data = $this->JsonData->find('first', array(
            'conditions' => array(
                'JsonData.title' => 'analysis_lists',
                'JsonData.user_id' => $this->Auth->user('id'),
                'JsonData.year' => $year -1
            )
        ));
        if (!$json_data) {
            $pre_event_lists = array();
        } else {
            $pre_event_lists = json_decode($json_data['JsonData']['json_data'], true);
        }
        $pre_event_counts = $this->Analysis->countEventFromEventList($pre_event_lists);
        $pre_event_counts['percent']['live'] = @round($pre_event_counts['live'] / $pre_event_counts['join'] *100, 1);
        $pre_event_counts['percent']['release'] = @round($pre_event_counts['release'] / $pre_event_counts['join'] *100, 1);
        $pre_event_counts['percent']['talk'] = @round($pre_event_counts['talk'] / $pre_event_counts['join'] *100, 1);
        
        //前年比
        $pre = array();
        $pre['event'] = $event_counts['event'] - $pre_event_counts['event'];
        $pre['entry'] = $event_counts['entry'] - $pre_event_counts['entry'];
        $pre['join'] = $event_counts['join'] - $pre_event_counts['join'];
        $pre['live'] = $event_counts['live'] - $pre_event_counts['live'];
        $pre['release'] = $event_counts['release'] - $pre_event_counts['release'];
        $pre['talk'] = $event_counts['talk'] - $pre_event_counts['talk'];
        $pre['percent']['live'] = $event_counts['percent']['live'] - $pre_event_counts['percent']['live'];
        $pre['percent']['release'] = $event_counts['percent']['release'] - $pre_event_counts['percent']['release'];
        $pre['percent']['talk'] = $event_counts['percent']['talk'] - $pre_event_counts['percent']['talk'];
        $this->set(compact('pre'));
        
        //TODO 各イベント参加データβ版
        $event_year_lists = $this->Analysis->formatEventListToArray($event_lists, 'year');
        $event_artist_lists = $this->Analysis->formatEventListToArray($event_lists, 'artist');
        $event_place_lists = $this->Analysis->formatEventListToArray($event_lists, 'place');
        $event_music_lists = $this->Analysis->formatEventListToArray($event_lists, 'music');
        $this->set(compact('event_year_lists', 'event_artist_lists', 'event_place_lists', 'event_music_lists'));
    }
    
    public function update()
    {
        //年ごとにデータを分ける
        $MIN_YEAR_KEY = $this->Option->getOptionKey('MIN_YEAR_KEY');
        $error_flg = 0;
        for ($year = $MIN_YEAR_KEY; $year <= date('Y'); $year++) {
            //ユーザ別イベント参加分析データを更新
            $analysis_lists = $this->Analysis->getEventData(false, $year);
            if (!$this->JsonData->saveDataJson($analysis_lists, 'analysis_lists', false, $year)) {
                $error_flg = 1;
            }
        }
        if ($error_flg == 0) {
            $this->Session->setFlash('イベント参加データを更新しました。', 'flashMessage');
        } else {
            $this->Session->setFlash('イベント参加データを正常に更新できませんでした。', 'flashMessage');
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
            $format_event_lists = $this->Analysis->formatEventListToArray($event_lists, 'year', array('status' => array(0, 1, 2, 3, 4)));
            $event_lists = array();
            //任意の年のみを取得
            if (@$format_event_lists[$year . '年']) {
                $event_lists = $format_event_lists[$year . '年'];
                unset($event_lists['analysis']);
            }
            
        } elseif (isset($this->params->query['artist']) == true) {
            $artist = $this->params->query['artist'];
            $mode = 'artist';
            $format_event_lists = $this->Analysis->formatEventListToArray($event_lists, 'artist', array('status' => array(0, 1, 2, 3, 4)));
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
            $format_event_lists = $this->Analysis->formatEventListToArray($event_lists, 'place', array('status' => array(0, 1, 2, 3, 4)));
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
            $event_music_lists = $this->Analysis->formatEventListToArray($event_lists, 'music', array('artist' => $artist));
        } elseif ($mode == 'place') {
            $event_year_lists = $this->Analysis->formatEventListToArray($event_lists, 'year');
            $event_artist_lists = $this->Analysis->formatEventListToArray($event_lists, 'artist');
            $event_music_lists = $this->Analysis->formatEventListToArray($event_lists, 'music');
        }
        $this->set(compact('event_year_lists', 'event_artist_lists', 'event_place_lists', 'event_music_lists'));
        
        $this->render('index');
    }
}
