<?php echo $this->Html->css('top', array('inline' => FALSE)); ?>
<h3>本日の予定</h3>

  <?php if (count($event_today_lists) > 0) { ?>
  <table class="detail-list-min">
    <tr><th class="tbl-date">開催日</th><th>イベント名</th><th class="tbl-ico">種類<br>状態</th>
        <th class="tbl-today">予定</th><th class="tbl-action">action</th></tr>
    
    <?php foreach ($event_today_lists AS $event_today_list) { ?>
    <tr><td class="tbl-date"><?php echo $event_today_list['Event']['date']; ?></td>
        <td><?php echo $event_today_list['Event']['title']; ?></td>
        <td class="tbl-ico"><span class="icon-genre col-event_<?php echo $event_today_list['Event']['genre_id']; ?>"><?php echo $event_today_list['EventGenre']['title']; ?></span>
                            <br><?php if ($event_today_list['Event']['status'] == 0) {echo '<span class="icon-genre">未定</span>';}
                                  elseif ($event_today_list['Event']['status'] == 1) {echo '<span class="icon-like">申込中</span>';}
                                  elseif ($event_today_list['Event']['status'] == 2) {echo '<span class="icon-true">確定</span>';}
                                  elseif ($event_today_list['Event']['status'] == 3) {echo '<span class="icon-false">落選</span>';} ?></td>
        <td class="tbl-today"><?php if ($event_today_list['Event']['date'] == date('Y-m-d')) {echo '開催';}
                                elseif ($event_today_list['Event']['payment_end'] == date('Y-m-d')) {echo '入金締切';}
                                elseif ($event_today_list['Event']['announcement_date'] == date('Y-m-d')) {echo '結果発表';}
                                elseif ($event_today_list['Event']['entry_end'] == date('Y-m-d')) {echo '申込締切';}
                                elseif ($event_today_list['Event']['entry_start'] == date('Y-m-d')) {echo '申込開始';} ?></td>         
        <td class="tbl-action"><span class="icon-button"><?php echo $this->Html->link('詳細', '/event/'.$event_today_list['Event']['id'], array('target' => '_blank')); ?></span></td></tr>
    <?php } ?>
  </table>
  <?php } else { ?>
  <div class="intro_top">
    <p>
      本日の予定はありません。
    </p>
  </div>
  <?php } ?>

<h3>直近の予定</h3>

  <?php if (count($event_current_lists) > 0) { ?>
  <table class="detail-list">
    <tr><th class="tbl-date">開催日</th><th>イベント名</th><th class="tbl-ico">種類<br>状態</th><th class="tbl-num">金額<br>枚数</th>
        <th class="tbl-current">予定</th><th class="tbl-action">action</th></tr>
    
    <?php foreach ($event_current_lists AS $event_current_list) { ?>
    <tr><td class="tbl-date"><?php echo $event_current_list['Event']['date']; ?></td>
        <td><?php echo $event_current_list['Event']['title']; ?></td>
        <td class="tbl-ico"><span class="icon-genre col-event_<?php echo $event_current_list['Event']['genre_id']; ?>"><?php echo $event_current_list['EventGenre']['title']; ?></span>
                            <br><?php if ($event_current_list['Event']['status'] == 0) {echo '<span class="icon-genre">未定</span>';}
                                  elseif ($event_current_list['Event']['status'] == 1) {echo '<span class="icon-like">申込中</span>';}
                                  elseif ($event_current_list['Event']['status'] == 2) {echo '<span class="icon-true">確定</span>';}
                                  elseif ($event_current_list['Event']['status'] == 3) {echo '<span class="icon-false">落選</span>';} ?></td>
        <td class="tbl-num"><?php echo $event_current_list['Event']['amount']; ?>円<br>
                            <?php echo $event_current_list['Event']['number']; ?>枚</td>
        <td class="tbl-current"><?php if ($event_current_list['Event']['entry_start'] > date('Y-m-d') AND $event_current_list['Event']['entry_start'] <= date('Y-m-d', strtotime('+1 month'))) {echo '申込開始<br>'.$event_current_list['Event']['entry_start'];}
                                elseif ($event_current_list['Event']['entry_end'] > date('Y-m-d') AND $event_current_list['Event']['entry_end'] <= date('Y-m-d', strtotime('+1 month'))) {echo '申込締切<br>'.$event_current_list['Event']['entry_end'];}
                                elseif ($event_current_list['Event']['announcement_date'] > date('Y-m-d') AND $event_current_list['Event']['announcement_date'] <= date('Y-m-d', strtotime('+1 month'))) {echo '結果発表<br>'.$event_current_list['Event']['announcement_date'];}
                                elseif ($event_current_list['Event']['payment_end'] > date('Y-m-d') AND $event_current_list['Event']['payment_end'] <= date('Y-m-d', strtotime('+1 month'))) {echo '入金締切<br>'.$event_current_list['Event']['payment_end'];}
                                elseif ($event_current_list['Event']['date'] > date('Y-m-d') AND $event_current_list['Event']['date'] <= date('Y-m-d', strtotime('+1 month'))) {echo '開催<br>'.$event_current_list['Event']['date'];} ?></td>
        <td class="tbl-action"><span class="icon-button"><?php echo $this->Html->link('詳細', '/event/'.$event_current_list['Event']['id'], array('target' => '_blank')); ?></span></td></tr>
    <?php } ?>
  </table>
  <?php } else { ?>
  <div class="intro_top">
    <p>
      直近の予定はありません。
    </p>
  </div>
  <?php } ?>

<h3>未対応のイベント</h3>

  <?php $event_undecided_count = count($event_undecided_lists); ?>
  <?php if (count($event_undecided_count) == 0) { ?>
  <div class="intro_top">
    <p>
      未対応のイベントはありません。
    </p>
  </div>
  <?php } else { ?>
  <div class="intro_top">
    <p>
      未対応のイベントが <?php echo $this->Html->link($event_undecided_count.'件', '/events/event_lists/'); ?> あります。
    </p>
  </div>
  <?php } ?>