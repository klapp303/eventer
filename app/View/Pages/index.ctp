<?php echo $this->Html->css('pages', array('inline' => FALSE)); ?>
<h3>オプション一覧</h3>

  <ul class="list_option">
    <li><span class="link-page"><?php echo $this->Html->link('⇨ イベント種類の一覧を確認する', '/pages/event_genres/'); ?></span></li>
    <li><span class="link-page"><?php echo $this->Html->link('⇨ エントリー方法の一覧を確認する', '/pages/entry_genres/'); ?></span></li>
    <li></li>
    <li><span class="link-page"><?php echo $this->Html->link('⇨ お知らせ、更新履歴を確認する', '/pages/history/'); ?></span></li>
    <li><span class="link-page"><?php echo $this->Html->link('⇨ 問い合わせ、制作者について', '/pages/author/'); ?></span></li>
  </ul>