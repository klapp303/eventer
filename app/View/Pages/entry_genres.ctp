<?php echo $this->Html->css('pages', array('inline' => FALSE)); ?>
<div class="intro_pages">
  <p>
    プルダウンの選択肢とか細かいところに影響するかもなので、<br>
    登録変更削除のフォームはとりあえず保留。
  </p>
</div>

<h3>申込方法一覧</h3>

  <table class="detail-list">
    <tr><th class="tbl-ico">申込方法名</th><th>説明</th></tr>
    <?php foreach ($entry_genre_lists AS $entry_genre_list) { ?>
    <tr><td class="tbl-ico"><span class="icon-genre col-event_<?php echo $entry_genre_list['EntryGenre']['id']; ?>"><?php echo $entry_genre_list['EntryGenre']['title']; ?></span></td>
        <td><?php echo $entry_genre_list['EntryGenre']['description']; ?></td></tr>
    <?php } ?>
  </table>