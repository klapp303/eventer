<p>
  プルダウンの選択肢とか細かいところに影響するかもなので、<br>
  登録変更削除のフォームはとりあえず保留。
</p>

<h3>イベント種類一覧</h3>

  <table class="detail-list">
    <tr><th class="tbl-ico">ジャンル名</th><th>説明</th></tr>
    <?php for($i = 0; $i < $event_genre_counts; $i++){ ?>
    <tr><td class="tbl-ico"><span class="icon-genre col-event_<?php echo $event_genre_lists[$i]['EventGenre']['id']; ?>"><?php echo $event_genre_lists[$i]['EventGenre']['title']; ?></span></td>
        <td><?php echo $event_genre_lists[$i]['EventGenre']['description']; ?></td></tr>
    <?php } ?>
  </table>