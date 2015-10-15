<?php echo $this->Html->css('pages', array('inline' => FALSE)); ?>
<div class="intro_pages">
  <p>
    プルダウンの選択肢とか細かいところに影響するかもなので、<br>
    登録変更削除のフォームはとりあえず保留。
  </p>
</div>

<h3>イベント種類一覧</h3>

  <table class="detail-list">
    <tr><th class="tbl-ico">ジャンル名</th><th>説明</th></tr>
    <?php foreach ($event_genre_lists AS $event_genre_list) { ?>
    <tr><td class="tbl-ico"><span class="icon-genre col-event_<?php echo $event_genre_list['EventGenre']['id']; ?>"><?php echo $event_genre_list['EventGenre']['title']; ?></span></td>
        <td><?php echo $event_genre_list['EventGenre']['description']; ?></td></tr>
    <?php } ?>
  </table>