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
}
