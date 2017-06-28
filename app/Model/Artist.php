<?php

App::uses('AppModel', 'Model');

class Artist extends AppModel
{
    public $useTable = 'artists';
    
    public $actsAs = array('SoftDelete'/* , 'Search.Searchable' */);
    
//    public $belongsTo = array(
//        'SamplesGenre' => array(
//            'className' => 'SamplesGenre', //関連付けるModel
//            'foreignKey' => 'genre_id', //関連付けるためのfield、関連付け先は上記Modelのid
//            'fields' => 'title' //関連付け先Modelの使用field
//        )
//    );
    
    public $validate = array(
        'name' => array(
            'rule_1' => array(
                'rule' => 'notBlank',
                'required' => 'create'
            ),
//            'rule_2' => array(
//                'rule' => array('maxLength', 25),
//                'message' => '名前は25文字以内です'
//            )
        ),
        'kana' => array(
            'rule_1' => array(
                'rule' => 'notBlank',
                'required' => 'create'
            ),
//            'rule_2' => array(
//                'rule' => array('maxLength', 25),
//                'message' => 'カナは25文字以内です'
//            ),
            'rule_3' => array(
                'rule' => 'isKanaLetter',
                'message' => '全角カナのみで入力してください'
            )
        ),
    );
    
//    public $filtetArgs = array(
//        'id' => array('type' => 'value'),
//        'title' => array('type' => 'value')
//    );
    
    public function getArrayLinkUrls($data = null)
    {
        $array_data = explode(',', $data);
        $array_link_urls = [];
        foreach ($array_data as $val) {
            $array_link_urls[] = array(
                'link_url' => $val
            );
        }
        
        return $array_link_urls;
    }
    
    public function getArrayRelatedArtists($data = null)
    {
        $array_data = explode(',', $data);
        $array_related_artists = [];
        foreach ($array_data as $val) {
            /* 関連アーティスト名の取得ここから */
            $related_artist = $this->find('first', array('conditions' => array('Artist.id' => $val)));
            if ($related_artist) {
                $name = $related_artist['Artist']['name'];
            } else {
                continue;
            }
            /* 関連アーティスト名の取得ここまで */
            $array_related_artists[] = array(
                'artist_id' => $val,
                'name' => $name
            );
        }
        
        return $array_related_artists;
    }
    
    public function getEventsConditionsFromArtist($id = false, $search_conditions = false, $date_all = false)
    {
        //ユーザIDを取得
        $user_id = AuthComponent::user(['id']);
        $this->loadModel('Option');
        $GUEST_USER_KEY = $this->Option->getOptionKey('GUEST_USER_KEY');
        $MIN_YEAR_KEY = $this->Option->getOptionKey('MIN_YEAR_KEY');
        
        //アーティストIDを取得
        $artists_id = array($id);
        //関連アーティストも取得しておく
        $this->loadModel('Artist');
        $related_artist_lists = $this->Artist->find('list', array(
            'conditions' => array(
                'Artist.related_artists_id !=' => null
            ),
            'fields' => array('Artist.related_artists_id')
        ));
        foreach ($related_artist_lists as $key => $val) {
            $array_related_id = $this->Artist->getArrayRelatedArtists($val);
            foreach ($array_related_id as $related_id) {
                if ($related_id['artist_id'] == $id) {
                    $artists_id[] = $key;
                    continue;
                }
            }
        }
        
        //アーティストIDから登録されたイベントを取得
        $this->loadModel('EventArtist');
        $event_artists_lists = $this->EventArtist->find('list', array(
            'conditions' => array('EventArtist.artist_id' => $artists_id),
            'fields' => array('EventArtist.events_detail_id')
        ));
        //参加済のイベント一覧を取得しておく
        $this->loadModel('EventUser');
        $join_lists = $this->EventUser->getJoinEntries($user_id);
        //エントリーのみの一覧を取得しておく
        $this->loadModel('EventsEntry');
        $entry_only_lists = $this->EventsEntry->getOnlyEntries($user_id);
        
        if (!$search_conditions) {
            $search_conditions = array();
        }
        
        //過去のイベントも取得する場合
        if ($date_all) {
            $date_conditions = $MIN_YEAR_KEY . '-01-01';
        //過去のイベントは取得しない場合
        } else {
            $date_conditions = date('Y-m-d');
        }
        
        //events_details用のconditionsを整形
        $conditions = array(
            array(
                'and' => $search_conditions
//                'or' => array(
//                    'Event.title LIKE' => '%' . $search_word . '%',
//                    'EventsDetail.title LIKE' => '%' . $search_word . '%'
//                )
            ),
            'EventsDetail.date >=' => $date_conditions,
            'EventsDetail.id' => $event_artists_lists, //eventsページの一覧からアーティストで更に絞り込み
            'or' => array(
                array('EventsDetail.user_id' => $user_id),
                array('EventsDetail.id' => $join_lists['events_detail_id']),
                array('EventsDetail.id' => $entry_only_lists['events_detail_id']),
                array('Event.publish' => 1) //公開ステータスを追加
            ),
            'EventsDetail.user_id !=' => $GUEST_USER_KEY
        );
        
        return $conditions;
    }
}
