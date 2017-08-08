<?php

App::uses('AppModel', 'Model');

class EventArtist extends AppModel
{
    public $useTable = 'event_artists';
    
//    public $actsAs = array('SoftDelete'); //関連テーブルのデータを取得されるので物理削除する
    
    public $belongsTo = array(
        'ArtistProfile' => array(
            'className' => 'Artist', //関連付けるModel
            'foreignKey' => 'artist_id', //関連付けるためのfield、関連付け先は上記Modelのid
            'fields' => 'name' //関連付け先Modelの使用field
        ),
        'Event' => array(
            'className' => 'Event', //関連付けるModel
            'foreignKey' => 'event_id', //関連付けるためのfield、関連付け先は上記Modelのid
//            'fields' => 'title' //関連付け先Modelの使用field
        ),
        'EventsDetail' => array(
            'className' => 'EventsDetail', //関連付けるModel
            'foreignKey' => 'events_detail_id', //関連付けるためのfield、関連付け先は上記Modelのid
//            'fields' => 'title' //関連付け先Modelの使用field
        )
    );
    
//    public $validate = array(
//        'title' => array(
//            'rule' => 'notBlank',
//            'required' => 'create'
//        ),
//        'amount' => array(
//            'rule' => 'numeric',
//            'required' => false,
//            'allowEmpty' => true,
//            'message' => '値段を正しく入力してください。'
//        )
//    );
    
//    public $filtetArgs = array(
//        'id' => array('type' => 'value'),
//        'title' => array('type' => 'value')
//    );
    
    public function checkExistData($events_detail_id = null, $artist_id = null)
    {
        if (!$events_detail_id || !$artist_id) {
            return 'error';
        }
        
        //登録済みのキャスト一覧を取得
        $cast_lists = $this->find('list', array(
            'conditions' => array('EventArtist.events_detail_id' => $events_detail_id),
            'fields' => array('EventArtist.artist_id')
        ));
        
        //キャスト一覧にアーティストIDがあるかを判定
        if (in_array($artist_id, $cast_lists)) {
            return true;
            
        } else {
            return false;
        }
    }
}
