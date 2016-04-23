<?php echo $this->Html->css('pages', array('inline' => FALSE)); ?>
<h3>イベ幸って？</h3>

<div class="intro_pages">
  <p>
    ライブやイベントによく行く作者が、申し込みや入金を忘れないようにと<br>
    イベントの日程やチケット、申込方法を一元管理するために作ったWebアプリです。<br>
    <br>
    各イベントの申込開始日時から終了日時、当落発表、入金締切、<br>
    そして開催日時場所など忘れがちな情報をまとめて登録管理する事ができます。
  </p>
</div>

<div class="link-page_pages">
  <span class="link-page"><?php echo $this->Html->link('⇨ 詳しい機能と使い方を確認する', '/pages/about/'); ?></span>
</div>

<h3>オプション一覧</h3>

  <ul class="list_option">
    <li><span class="link-page"><?php echo $this->Html->link('⇨ イベント種類の一覧を確認する', '/pages/event_genres/'); ?></span></li>
    <li><span class="link-page"><?php echo $this->Html->link('⇨ エントリー方法の一覧を確認する', '/pages/entry_genres/'); ?></span></li>
    <li><span class="link-page"><?php echo $this->Html->link('⇨ お知らせ、更新履歴を確認する', '/pages/history/'); ?></span></li>
    <li><span class="link-page"><?php echo $this->Html->link('⇨ 問い合わせ、制作者について', '/pages/author/'); ?></span></li>
  </ul>