<?php

App::uses('AppController', 'Controller');

class BudgetsController extends AppController
{
    public $uses = array('EventsDetail', 'EventsEntry'/* , 'EventUser' */); //使用するModel
    
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
        $this->set('column', 'payment');
        $BUDGET_LIMIT_KEY = $this->getOptionKey('BUDGET_LIMIT_KEY');
        $this->set('unfixed_lists', $this->EventsDetail->getUnfixedPayment($this->Auth->user('id'), 0, $BUDGET_LIMIT_KEY));
        
        $this->render('unfixed_lists');
    }
    
    public function unfixed_sales()
    {
        $this->set('column', 'sales');
        $BUDGET_LIMIT_KEY = $this->getOptionKey('BUDGET_LIMIT_KEY');
        $this->set('unfixed_lists', $this->EventsDetail->getUnfixedSales($this->Auth->user('id'), 0, $BUDGET_LIMIT_KEY));
        
        $this->render('unfixed_lists');
    }
    
    public function unfixed_collect()
    {
        $this->set('column', 'collect');
        $BUDGET_LIMIT_KEY = $this->getOptionKey('BUDGET_LIMIT_KEY');
        $this->set('unfixed_lists', $this->EventsDetail->getUnfixedCollect($this->Auth->user('id'), 0, $BUDGET_LIMIT_KEY));
        
        $this->render('unfixed_lists');
    }
    
    public function fixed($id = false, $column = false)
    {
        if (empty($id) || empty($column)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        
        if ($this->request->is('post')) {
            $this->EventsEntry->id = $id;
            if ($this->EventsEntry->savefield($column . '_status', 1)) {
                $this->Session->setFlash('対応済みに変更しました。', 'flashMessage');
            } else {
                $this->Session->setFlash('変更できませんでした。', 'flashMessage');
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
            $reset_lists = $this->EventsDetail->getUnfixedPayment($this->Auth->user('id'), 1, $BUDGET_LIMIT_KEY);
        } elseif ($column == 'sales') {
            $reset_lists = $this->EventsDetail->getUnfixedSales($this->Auth->user('id'), 1, $BUDGET_LIMIT_KEY);
        } elseif ($column == 'collect') {
            $reset_lists = $this->EventsDetail->getUnfixedCollect($this->Auth->user('id'), 1, $BUDGET_LIMIT_KEY);
        } else {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        $this->set('unfixed_lists', $reset_lists);
        
        $this->render('unfixed_lists');
    }
    
    public function reset($id = false, $reset_column = false)
    {
        if (empty($id) || empty($reset_column)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        
        if ($this->request->is('post')) {
            $this->EventsEntry->id = $id;
            if ($this->EventsEntry->savefield($reset_column . '_status', 0)) {
                $this->Session->setFlash('対応済みを元に戻しました。', 'flashMessage');
            } else {
                $this->Session->setFlash('元に戻せませんでした。', 'flashMessage');
            }
            
            $this->redirect('/budgets/reset_status/' . $reset_column);
        }
    }
    
//    public function in_lists()
//    {
//        $login_id = $this->Session->read('Auth.User.id'); //何度も使用するので予め取得しておく
//        $this->EventUser->recursive = 2; //EventUser→Event→EventGenreの2階層下までassociate
////        $sample_lists = $this->Sample->find('all', array(
////            'order' => array('date' => 'desc')
////        ));
//        $this->Paginator->settings = array(
//            'conditions' => array(
//                'EventDetail.user_id' => $login_id,
//                'EventUser.payment' => 0,
//                'EventDetail.deleted !=' => 1 //紐付くテーブルのSoftDeleteは無視されるので記述
//            ),
//            'order' => array('EventDetail.date' => 'asc')
//        );
//        $in_lists = $this->Paginator->paginate('EventUser');
//        $this->set('in_lists', $in_lists);
//        
//        if (isset($this->request->params['id']) == true) { //パラメータにidがあれば詳細ページを表示
//            $in_detail = $this->EventUser->find('first', array(
//                'conditions' => array('EventUser.id' => $this->request->params['id'])
//            ));
//            if (!empty($in_detail)) { //データが存在する場合
//                $this->set('in_detail', $in_detail);
//                
//                $this->render('in_lists');
//                
//            } else { //データが存在しない場合
//                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
//            }
//        }
//    }
    
//    public function out_lists()
//    {
//        $login_id = $this->Session->read('Auth.User.id'); //何度も使用するので予め取得しておく
//        $this->EventUser->recursive = 2; //EventUser→Event→EventGenreの2階層下までassociate
////        $sample_lists = $this->Sample->find('all', array(
////            'order' => array('date' => 'desc')
////        ));
//        $this->Paginator->settings = array(
//            'conditions' => array(
//                'EventUser.user_id' => $login_id,
//                'EventUser.payment' => 0,
//                'EventDetail.deleted !=' => 1 //紐付くテーブルのSoftDeleteは無視されるので記述
//            ),
//            'order' => array('EventDetail.date' => 'asc')
//        );
//        $out_lists = $this->Paginator->paginate('EventUser');
//        $this->set('out_lists', $out_lists);
//        
//        if (isset($this->request->params['id']) == true) { //パラメータにidがあれば詳細ページを表示
//            $out_detail = $this->EventUser->find('first', array(
//                'conditions' => array('EventUser.id' => $this->request->params['id'])
//            ));
//            if (!empty($out_detail)) { //データが存在する場合
//                $this->set('out_detail', $out_detail);
//                
//                $this->render('out_lists');
//                
//            } else { //データが存在しない場合
//                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
//            }
//        }
//    }
    
//    public function edit($id = null)
//    {
//        if (empty($id)) {
//            throw new NotFoundException(__('存在しないデータです。'));
//        }
//        
//        if ($this->request->is('post')) {
//            $this->EventUser->id = $id;
//            $this->EventUser->saveField('payment', 1);
//            
//            $this->redirect('/budgets/in_lists/');
//        }
//    }
    
//    public function delete($id = null)
//    {
//        if (empty($id)) {
//            throw new NotFoundException(__('存在しないデータです。'));
//        }
//        
//        if ($this->request->is('post')) {
////            $this->Eventuser->Behaviors->enable('SoftDelete');
//            if ($this->EventUser->delete($id)) {
//                $this->Session->setFlash('削除しました。', 'flashMessage');
//            } else {
//                $this->Session->setFlash('削除できませんでした。', 'flashMessage');
//            }
//            
//            $this->redirect('/budgets/in_lists/');
//        }
//    }
}
