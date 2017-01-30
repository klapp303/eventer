<?php

App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email'); //CakeEmaiilの利用、分けて記述

class AppController extends Controller
{
    public $uses = array('EventsEntry', 'Option'); //使用するModel
    
    public $components = array(
        'Session', //Paginateのため記述
        'Flash', //ここからログイン認証用
        'Auth' => array(
            'loginRedirect' => array(
                'controller' => 'top',
                'action' => 'index'
            ),
            'logoutRedirect' => array(
                'controller' => 'users',
                'action' => 'login'
            ),
            'authenticate' => array(
                'Form' => array('passwordHasher' => 'Blowfish')
            )
        ),
        'DebugKit.Toolbar' //ページ右上の開発用デバッグツール
    );
    
    public function beforeFilter()
    {
//        $this->Auth->allow('index'); //認証なしのページを設定
        
        $this->set('userData', $this->Auth->user());
        
        //メインメニューやパンくずのために定義しておく
        $array_menu = array(
            1 => array(
                'title' => 'about イベ幸',
                'link' => '/pages/'
            ),
            2 => array(
                'title' => 'イベント管理',
                'link' => '/events/'
            ),
            3 => array(
                'title' => '収支管理',
                'link' => '/budgets/'
            ),
            4 => array(
                'title' => '会場一覧',
                'link' => '/places/place_lists/'
            ),
            5 => array(
                'title' => '出演者タグ',
                'link' => '/artists/artist_lists/'
            ),
            6 => array(
                'title' => 'ログアウト',
                'link' => '/users/logout/'
            )
        );
        $this->set('array_menu', $array_menu);
        
        $this->set('week_lists', array('日', '月', '火', '水', '木', '金', '土'));
        //エントリーの日付カラムを定義しておく
        $this->set('entryDateColumn', $this->EventsEntry->getDateColumn());
        //イベントのstatusを定義しておく
        $this->set('eventEntryStatus', $this->EventsEntry->getEntryStatus());
        //イベントのpaymentを定義しておく
        $this->set('eventPaymentStatus', $this->EventsEntry->getPaymentStatus());
        
        //paginatorのオプションを定義しておく
        $paginator_option = array(
            'modulus' => 4, //現在ページから左右あわせてインクルードする個数
            'separator' => ' | ', //デフォルト値のセパレーター
            'first' => '＜', //先頭ページへのリンク
            'last' => '＞' //最終ページへのリンク
        );
        $this->set('paginator_option', $paginator_option);
    }
    
    public function getOptionKey($key_title = false, $key = 0)
    {
        $option = $this->Option->find('first', array( //オプション値を取得
            'conditions' => array('Option.title' => $key_title),
            'fields' => 'Option.key'
        ));
        if ($option) {
            $key = $option['Option']['key'];
        }
        
        return $key;
    }
}
