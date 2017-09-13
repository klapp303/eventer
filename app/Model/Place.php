<?php

App::uses('AppModel', 'Model');

class Place extends AppModel
{
    public $useTable = 'places';
    
    public $actsAs = array('SoftDelete'/* , 'Search.Searchable' */);
    
    public $order = array('Place.sort' => 'asc', 'Place.id' => 'asc');
    
    public $belongsTo = array(
        'Prefecture' => array(
            'className' => 'Prefecture', //関連付けるModel
            'foreignKey' => 'prefecture_id', //関連付けるためのfield、関連付け先は上記Modelのid
            'fields' => array('name', 'state') //関連付け先Modelの使用field
        )
    );
    
//    public $virtualFields = array(
//        //キャパから座席数を計算
//        'seats' => 'ROUND(capacity *0.75)'
//    );
    
    public $validate = array(
        'name' => array(
            'rule_1' => array(
                'rule' => 'notBlank',
                'required' => 'create'
            ),
            'rule_2' => array(
                'rule' => array('maxLength', 25),
                'message' => '会場名は25文字以内です'
            )
        ),
        'access' => array(
            'rule' => 'notBlank',
            'required' => 'create'
        ),
        'capacity' => array(
            'rule' => 'numeric',
            'required' => false,
            'allowEmpty' => true,
            'message' => '人数を正しく入力してください。'
        ),
        'sort' => array(
            'rule' => 'numeric',
            'required' => false,
            'allowEmpty' => true,
            'message' => 'ソートキーは数値で入力してください。'
        )
    );
    
//    public $filtetArgs = array(
//        'id' => array('type' => 'value'),
//        'title' => array('type' => 'value')
//    );
    
    public function getNumberSeats($place_id = null)
    {
        //キャパから座席数を計算
        $place_data = $this->find('first', array(
            'conditions' => array('Place.id' => $place_id)
        ));
        if (@!$place_data['Place']['capacity']) {
            return false;
        } else {
            $capacity = $place_data['Place']['capacity'];
        }
        
        //キャパが～1000の場合（ライブハウス、イベントスペース等）
        //参考：渋谷duo 700人、品川ステラボール 900人
        if ($capacity <= 1000) {
            $data = $capacity;
            
        } else {
            //キャパが～5000の場合（主にライブホール）
            //参考：渋谷O-EAST 1300人、舞浜アンフィシアター 2100人、東京国際フォーラム 5000人
            if ($capacity <= 5000) {
                $data = $capacity *0.9;
                
            //キャパが～20000の場合（主に競技場）
            //参考：有明コロシアム 10000人、武道館 14000人、横浜アリーナ 17000人
            } elseif ($capacity < 20000) {
                $data = $capacity *0.8;
                
            //キャパが20000～の場合（主にドーム、球場）
            //参考：西京極球場 20000人、さいたまスーパーアリーナ 27000人、東京ドーム 55000人
            } else {
                $data = $capacity *0.7;
            }
            
            $data = round($data, -2);
        }
        
        return $data;
    }
}
