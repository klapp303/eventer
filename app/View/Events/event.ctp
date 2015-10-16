<?php echo $this->Html->css('events', array('inline' => FALSE)); ?>
<span class="fr cf"><?php echo $this->element('access_search', array('data' => $event_detail)); ?></span>

<h3>イベント詳細</h3>

  <table class="detail-list detail-list_event">
    <tr><th>イベント名</th><th>会場</th><th class="tbl-date-long">開催日時</th></tr>
    <tr><td><?php echo $event_detail['Event']['title']; ?></td>
        <td><?php if ($event_detail['Event']['place_id'] < 2) { ?>
              <?php echo 'その他'; ?>
            <?php } elseif ($event_detail['EventPlace']['name']) { ?>
              <?php echo $this->Html->link($event_detail['EventPlace']['name'], '/places/place_detail/'.$event_detail['Event']['place_id']); ?>
            <?php } ?></td>
        <td class="tbl-date-long"><?php echo $event_detail['Event']['date']; ?><br><?php echo $event_detail['Event']['time_start']; ?></td></tr>
  </table>
  
  <table class="detail-list detail-list_event">
    <tr><th class="tbl-ico">種類</th><th class="tbl-ico">状態</th>
        <th class="tbl-date-long">申込開始日</th><th class="tbl-date-long">申込終了日</th><th class="tbl-date-long">結果発表日</th><th class="tbl-date-long">入金締切日</th></tr>
    <tr><td class="tbl-ico"><span class="icon-genre col-event_<?php echo $event_detail['Event']['genre_id']; ?>"><?php echo $event_detail['EventGenre']['title']; ?></span></td>
        <td class="tbl-ico"><?php if ($event_detail['Event']['status'] == 0) {echo '<span class="icon-genre">未定</span>';}
                              elseif ($event_detail['Event']['status'] == 1) {echo '<span class="icon-like">申込中</span>';}
                              elseif ($event_detail['Event']['status'] == 2) {echo '<span class="icon-true">確定</span>';}
                              elseif ($event_detail['Event']['status'] == 3) {echo '<span class="icon-false">落選</span>';} ?></td>
        <td class="tbl-date-long"><?php echo $event_detail['Event']['entry_start']; ?></td>
        <td class="tbl-date-long"><?php echo $event_detail['Event']['entry_end']; ?></td>
        <td class="tbl-date-long"><?php echo $event_detail['Event']['announcement_date']; ?></td>
        <td class="tbl-date-long"><?php echo $event_detail['Event']['payment_end']; ?></td></tr>
  </table>
  
  <table class="detail-list detail-list_event">
    <tr><th>作成者</th><th class="tbl-ico-long">公開設定</th><th class="tbl-ico-long">申込方法</th><th class="tbl-num">金額</th><th class="tbl-num">枚数</th></tr>
    <tr><td><?php echo $event_detail['UserName']['handlename']; ?></td>
        <td class="tbl-ico-long"><?php if ($event_detail['Event']['publish'] == 0) {echo '<span class="icon-false">参加者のみ</span>';}
                              elseif ($event_detail['Event']['publish'] == 1) {echo '<span class="icon-true">全てのユーザ</span>';} ?></td>
        <td class="tbl-ico-long"><span class="icon-genre col-entry_<?php echo $event_detail['Event']['entry_id']; ?>"><?php echo $event_detail['EntryGenre']['title']; ?></span></td>
        <td class="tbl-num"><?php echo $event_detail['Event']['amount']; ?>円</td>
        <td class="tbl-num"><?php echo $event_detail['Event']['number']; ?>枚</td></tr>
  </table>
  
  <table class="detail-list detail-list_event">
    <tr><th>参加者</th></tr>
    <tr><td><?php $user_lists = $event_detail['UserList']; ?>
            <?php foreach ($user_lists AS $user_list) { ?>
            <span class="tbl-user_long"><?php echo $user_list['UserProfile']['handlename']; ?></span>
            <?php } ?></td></tr>
  </table>