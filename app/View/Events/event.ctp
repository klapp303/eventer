<h3>イベント詳細</h3>

  <table class="detail-list">
    <tr><th>イベント名</th><th>開催日時</th><th>開催場所</th></tr>
    <tr><td><?php echo $event_detail['Event']['title']; ?></td>
        <td><?php echo $event_detail['Event']['date']; ?><br><?php echo $event_detail['Event']['time_start']; ?>～</td>
        <td><?php echo $event_detail['Event']['place_id']; ?></td></tr>
  </table>
  
  <table class="detail-list">
    <tr><th class="tbl-ico">種類</th><th class="tbl-ico">状態</th><th class="tbl-ico">申込方法</th><th>申込開始日</th><th>申込終了日</th><th>結果発表日</th><th>入金締切日</th></tr>
    <tr><td class="tbl-ico"><span class="icon-genre col-event_<?php echo $event_detail['Event']['genre_id']; ?>"><?php echo $event_detail['EventGenre']['title']; ?></span></td>
        <td class="tbl-ico"><?php if ($event_detail['Event']['status'] == 0) {echo '<span class="icon-false">未定</span>';}
                              elseif ($event_detail['Event']['status'] == 1) {echo '<span class="icon-true">申込中</span>';}
                              elseif ($event_detail['Event']['status'] == 2) {echo '<span class="icon-true">確定</span>';} ?></td>
        <td class="tbl-ico"><span class="icon-genre"><?php echo $event_detail['EntryGenre']['title']; ?></span></td>
        <td><?php echo $event_detail['Event']['entry_start']; ?></td>
        <td><?php echo $event_detail['Event']['entry_end']; ?></td>
        <td><?php echo $event_detail['Event']['announcement_date']; ?></td>
        <td><?php echo $event_detail['Event']['payment_end']; ?></td></tr>
  </table>
  
  <table class="detail-list">
    <tr><th class="tbl-num">金額</th><th class="tbl-num">枚数</th><th>作成者</th><th>参加者</th></tr>
    <tr><td class="tbl-num"><?php echo $event_detail['Event']['amount']; ?>円</td>
        <td class="tbl-num"><?php echo $event_detail['Event']['number']; ?>枚</td>
        <td><?php echo $event_detail['UserName']['handlename']; ?></td>
        <td>表示できたらそれはとっても嬉しいなって</td></tr>
  </table>