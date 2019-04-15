<?php

App::uses('AppController', 'Controller');

class AnalysisController extends AppController
{
    public $uses = array('JsonData', 'Analysis', 'Favorite', 'Option'); //使用するModel
    
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
            $this->redirect('/analysis');
        }
        $this->set(compact('year', 'MIN_YEAR_KEY'));
        
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
        $pre['percent']['live'] = sprintf('%.1f', $event_counts['percent']['live'] - $pre_event_counts['percent']['live']);
        $pre['percent']['release'] = sprintf('%.1f', $event_counts['percent']['release'] - $pre_event_counts['percent']['release']);
        $pre['percent']['talk'] = sprintf('%.1f', $event_counts['percent']['talk'] - $pre_event_counts['percent']['talk']);
        $this->set(compact('pre'));
        
        //アーティスト別イベント参加データ
        $event_artist_lists = $this->Analysis->formatEventListToArray($event_lists, 'artist');
//        echo'<pre>';print_r($event_artist_lists);echo'</pre>';exit;
        $favorite_lists = $this->Favorite->find('list', array(
            'conditions' => array(
                'Favorite.user_id' => $this->Auth->user('id'),
                'Favorite.deleted' => 0
            ),
            'fields' => 'artist_id'
        ));
        $this->set(compact('event_artist_lists', 'favorite_lists'));
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
        
        $this->redirect('/analysis');
    }
    
    public function artist()
    {
        //ユーザ別イベント参加分析データを取得
        $json_data = $this->JsonData->find('all', array(
            'conditions' => array(
                'JsonData.title' => 'analysis_lists',
                'JsonData.user_id' => $this->Auth->user('id')
            )
        ));
        $event_lists = array();
        foreach ($json_data as $data) {
            $event_lists[$data['JsonData']['year']] = json_decode($data['JsonData']['json_data'], true);
        }
        
        //パラメータがなければアーティストの全体分析ページ
        if (isset($this->params->query['artist']) == false) {
            //TODO
            exit;
            
        //パラメータがあれば各アーティストの詳細分析ページ
        } else {
            //任意のアーティストに紐付くイベントのみを抽出
            $artist_id = $this->params->query['artist'];
            $format_event_lists = array();
            foreach ($event_lists as $year => $list) {
                $format_data = $this->Analysis->formatEventListToArray($list, 'artist', array('status' => array(0, 1, 2, 3, 4)));
                foreach ($format_data as $data) {
                    if ($data['analysis']['artist']['id'] == $artist_id) {
                        $format_event_lists[$year] = $data;
                    }
                }
            }
            
            //年別イベント参加データを取得
            $artist_event_counts = array();
            foreach ($format_event_lists as $year => $list) {
                //アーティスト名を取得しておく
                $artist = $list['analysis']['artist']['name'];
                unset($list['analysis']);
                $event_counts = $this->Analysis->countEventFromEventList($list);
                $artist_event_counts[$year] = $event_counts;
            }
            //全体イベント参加データを生成
            $event_counts = array(
                'event' => 0, 'entry' => 0, 'join' => 0,
                'live' => 0, 'release' => 0, 'talk' => 0
            );
            foreach ($artist_event_counts as $count) {
                $event_counts['event'] = $event_counts['event'] + $count['event'];
                $event_counts['entry'] = $event_counts['entry'] + $count['entry'];
                $event_counts['join'] = $event_counts['join'] + $count['join'];
                $event_counts['live'] = $event_counts['live'] + $count['live'];
                $event_counts['release'] = $event_counts['release'] + $count['release'];
                $event_counts['talk'] = $event_counts['talk'] + $count['talk'];
            }
            $event_counts['percent']['live'] = @round($event_counts['live'] / $event_counts['join'] *100, 1);
            $event_counts['percent']['release'] = @round($event_counts['release'] / $event_counts['join'] *100, 1);
            $event_counts['percent']['talk'] = @round($event_counts['talk'] / $event_counts['join'] *100, 1);
            $this->set(compact('artist', 'event_counts', 'artist_event_counts'));
        }
    }
    
    public function total()
    {
        //ユーザ別イベント参加分析データを取得
        $json_data = $this->JsonData->find('all', array(
            'conditions' => array(
                'JsonData.title' => 'analysis_lists',
                'JsonData.user_id' => $this->Auth->user('id')
            )
        ));
        $event_lists = array();
        foreach ($json_data as $data) {
            $event_lists[$data['JsonData']['year']] = json_decode($data['JsonData']['json_data'], true);
        }
        //年度別データをトータルデータに整形
        $total_event_lists = [];
        foreach ($event_lists as $val) {
            foreach ($val as $val2) {
                $total_event_lists[] = $val2;
            }
        }
        
        //イベント数
        $event_counts = $this->Analysis->countEventFromEventList($total_event_lists);
        $event_counts['percent']['live'] = @round($event_counts['live'] / $event_counts['join'] *100, 1);
        $event_counts['percent']['release'] = @round($event_counts['release'] / $event_counts['join'] *100, 1);
        $event_counts['percent']['talk'] = @round($event_counts['talk'] / $event_counts['join'] *100, 1);
        $this->set(compact('event_counts'));
        
        //アーティスト別イベント参加データ
        $event_artist_lists = $this->Analysis->formatEventListToArray($total_event_lists, 'artist');
//        echo'<pre>';print_r($event_artist_lists);echo'</pre>';exit;
        $favorite_lists = $this->Favorite->find('list', array(
            'conditions' => array(
                'Favorite.user_id' => $this->Auth->user('id'),
                'Favorite.deleted' => 0
            ),
            'fields' => 'artist_id'
        ));
        $this->set(compact('event_artist_lists', 'favorite_lists'));
        
        //楽曲別イベント参加データ
        $event_music_lists = $this->Analysis->formatEventListToArray($total_event_lists, 'music');
        $this->set(compact('event_music_lists'));
        
        //会場別イベント参加データ
        $event_place_lists = $this->Analysis->formatEventListToArray($total_event_lists, 'place');
        foreach ($event_place_lists as $key => $val) {
            if (strpos($key, 'その他') !== false) {
                unset($event_place_lists[$key]);
            }
        }
        $this->set(compact('event_place_lists'));
    }
}
