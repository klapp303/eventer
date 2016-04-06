<?php echo $this->Html->css('pages', array('inline' => FALSE)); ?>
<div class="intro_pages">
  <p>
    イベントのジャンル説明一覧です。<br>
    ざっくりとした分類ですので、細かい判断は登録者が行ってください。<br>
    <br>
    登録状況に応じて、ジャンルを追加する予定。
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