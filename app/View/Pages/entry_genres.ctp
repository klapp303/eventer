<?php echo $this->Html->css('pages', array('inline' => FALSE)); ?>
<div class="intro_pages">
  <p>
    イベントへのエントリー方法（申込方法）のジャンル説明一覧です。<br>
    <br>
    FCやメディア先行は優先度が高く、各チケットサイトは先着順かもしれない等、<br>
    各自の判断材料の参考にしてください。
  </p>
</div>

<h3>エントリー方法一覧</h3>

  <table class="detail-list">
    <tr><th class="tbl-genre">エントリー名</th><th>説明</th></tr>
    <?php foreach ($entry_genre_lists AS $entry_genre_list) { ?>
    <tr><td class="tbl-genre"><span class="icon-genre col-entry_<?php echo $entry_genre_list['EntryGenre']['id']; ?>"><?php echo $entry_genre_list['EntryGenre']['title']; ?></span></td>
        <td><?php echo $entry_genre_list['EntryGenre']['description']; ?></td></tr>
    <?php } ?>
  </table>