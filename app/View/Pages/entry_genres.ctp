<p>
  プルダウンの選択肢とか細かいところに影響するかもなので、<br>
  登録変更削除のフォームはとりあえず保留。
</p>

<h3>申込方法一覧</h3>

  <table class="detail-list">
    <tr><th class="tbl-ico">申込方法名</th><th>説明</th></tr>
    <?php for($i = 0; $i < $entry_genre_counts; $i++){ ?>
    <tr><td class="tbl-ico"><span class="icon-genre col-event_<?php echo $entry_genre_lists[$i]['EntryGenre']['id']; ?>"><?php echo $entry_genre_lists[$i]['EntryGenre']['title']; ?></span></td>
        <td><?php echo $entry_genre_lists[$i]['EntryGenre']['description']; ?></td></tr>
    <?php } ?>
  </table>