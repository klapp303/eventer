<?php echo $this->Html->css('budgets', array('inline' => FALSE)); ?>
<h3>収支管理メニュー</h3>

  <ul class="list_budget">
    <li><span class="link-page"><?php echo $this->Html->link('⇨ あなたが作成したイベントの参加者を確認する', '/budgets/in_lists/'); ?></span></li>
    <li><span class="link-page"><?php echo $this->Html->link('⇨ あなたが参加しているイベントを確認する', '/budgets/out_lists/'); ?></span></li>
  </li>