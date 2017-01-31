<?php

App::uses('AppController', 'Controller');

class BudgetsController extends AppController
{
    public $uses = array('EventUser', 'EventsDetail', 'EventsEntry'); //使用するModel
    
    public $components = array('Paginator');
    
    public $paginate = array(
        'limit' => 20,
        'order' => array('id' => 'desc')
    );
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'eventer_fullwidth';
//        $this->Sample->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
        
        $this->set('BUDGET_LIMIT_KEY', $this->getOptionKey('BUDGET_LIMIT_KEY'));
    }
    
    public function index()
    {
        
    }
    
    public function unfixed_payment()
    {
        //breadcrumbの設定
        $this->set('sub_page', '未対応の支払い一覧');
        
        $this->set('column', 'payment');
        $BUDGET_LIMIT_KEY = $this->getOptionKey('BUDGET_LIMIT_KEY');
        $this->set('unfixed_lists', $this->EventsDetail->getUnfixedPayment($this->Auth->user('id'), 0, $BUDGET_LIMIT_KEY));
        
        $this->render('unfixed_lists');
    }
    
    public function unfixed_sales()
    {
        //breadcrumbの設定
        $this->set('sub_page', '未対応のチケット余り一覧');
        
        $this->set('column', 'sales');
        $BUDGET_LIMIT_KEY = $this->getOptionKey('BUDGET_LIMIT_KEY');
        $this->set('unfixed_lists', $this->EventsDetail->getUnfixedSales($this->Auth->user('id'), 0, $BUDGET_LIMIT_KEY));
        
        $this->render('unfixed_lists');
    }
    
    public function unfixed_collect()
    {
        //breadcrumbの設定
        $this->set('sub_page', '未対応の集金一覧');
        
        $this->set('column', 'collect');
        $BUDGET_LIMIT_KEY = $this->getOptionKey('BUDGET_LIMIT_KEY');
        $this->set('unfixed_lists', $this->EventsDetail->getUnfixedCollect($this->Auth->user('id'), 0, $BUDGET_LIMIT_KEY));
        
        $this->render('unfixed_lists');
    }
    
    public function fixed($id = false, $column = false, $join_flg = 0)
    {
        if (empty($id) || empty($column)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        
        if ($this->request->is('post')) {
            if ($join_flg == 0) {
                $this->EventsEntry->id = $id;
                if ($this->EventsEntry->savefield($column . '_status', 1)) {
                    $this->Session->setFlash('対応済みに変更しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash('変更できませんでした。', 'flashMessage');
                }
                
            //参加済のイベントの場合
            } else {
                $eventUserData = $this->EventUser->find('first', array(
                    'conditions' => array(
                        'EventUser.events_entry_id' => $id,
                        'EventUser.user_id' => $this->Auth->user('id')
                    )
                ));
                if ($eventUserData) {
                    $this->EventUser->id = $eventUserData['EventUser']['id'];
                    if ($this->EventUser->savefield($column . '_status', 1)) {
                        $this->Session->setFlash('対応済みに変更しました。', 'flashMessage');
                    } else {
                        $this->Session->setFlash('変更できませんでした。', 'flashMessage');
                    }
                } else {
                    $this->Session->setFlash('変更できませんでした。', 'flashMessage');
                }
            }
            
            $this->redirect('/budgets/unfixed_' . $column);
        }
    }
    
    public function reset_status($column = false)
    {
        if (empty($column)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        
        $this->set('reset_column', $column);
        $BUDGET_LIMIT_KEY = $this->getOptionKey('BUDGET_LIMIT_KEY');
        if ($column == 'payment') {
            //breadcrumbの設定
            $this->set('sub_page', '対応済みに確定した支払い一覧');
            $reset_lists = $this->EventsDetail->getUnfixedPayment($this->Auth->user('id'), 1, $BUDGET_LIMIT_KEY);
            
        } elseif ($column == 'sales') {
            //breadcrumbの設定
            $this->set('sub_page', '対応済みに確定したチケット余り一覧');
            $reset_lists = $this->EventsDetail->getUnfixedSales($this->Auth->user('id'), 1, $BUDGET_LIMIT_KEY);
            
        } elseif ($column == 'collect') {
            //breadcrumbの設定
            $this->set('sub_page', '対応済みに確定した集金一覧');
            $reset_lists = $this->EventsDetail->getUnfixedCollect($this->Auth->user('id'), 1, $BUDGET_LIMIT_KEY);
            
        } else {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        $this->set('unfixed_lists', $reset_lists);
        
        $this->render('unfixed_lists');
    }
    
    public function reset($id = false, $reset_column = false, $join_flg = 0)
    {
        if (empty($id) || empty($reset_column)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        
        if ($this->request->is('post')) {
            if ($join_flg == 0) {
                $this->EventsEntry->id = $id;
                if ($this->EventsEntry->savefield($reset_column . '_status', 0)) {
                    $this->Session->setFlash('対応済みを元に戻しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash('元に戻せませんでした。', 'flashMessage');
                }
                
            //参加済のイベントの場合
            } else {
                $eventUserData = $this->EventUser->find('first', array(
                    'conditions' => array(
                        'EventUser.events_entry_id' => $id,
                        'EventUser.user_id' => $this->Auth->user('id')
                    )
                ));
                if ($eventUserData) {
                    $this->EventUser->id = $eventUserData['EventUser']['id'];
                    if ($this->EventUser->savefield($reset_column . '_status', 0)) {
                        $this->Session->setFlash('対応済みを元に戻しました。', 'flashMessage');
                    } else {
                        $this->Session->setFlash('元に戻せませんでした。', 'flashMessage');
                    }
                } else {
                    $this->Session->setFlash('元に戻せませんでした。', 'flashMessage');
                }
            }
            
            $this->redirect('/budgets/reset_status/' . $reset_column);
        }
    }
}
