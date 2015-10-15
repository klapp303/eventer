<?php echo $this->Html->css('pages', array('inline' => FALSE)); ?>
<h3>オプション一覧</h3>

  <ul class="list_option">
    <li><span class="link-page"><?php echo $this->Html->link('⇨ イベント種類の一覧を確認する', '/pages/event_genres/'); ?></span></li>
    <li><span class="link-page"><?php echo $this->Html->link('⇨ 申込方法の一覧を確認する', '/pages/entry_genres/'); ?></span></li>
    <li></li>
    <li><span class="link-page"><?php echo $this->Html->link('⇨ 未対応、対応予定の一覧を確認する', '/pages/issue_lists/'); ?></span></li>
    <li><span class="link-page"><?php echo $this->Html->link('⇨ 不具合、要望を問い合わせる', '#'); ?></span></li>
  </li>