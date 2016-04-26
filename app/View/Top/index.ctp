<?php echo $this->Html->css('top', array('inline' => FALSE)); ?>
<div class="intro_top">
  <table class="detail-list-min">
    <tr><td class="tbl-intro_top">未対応の支払いが</td><td class="tbl-num_top"><?php echo $this->Html->link($unfixed_payment_lists['count'].'件', '/budgets/unfixed_payment/'); ?></td><td>あります。</td></tr>
    <tr><td class="tbl-intro_top">未対応のチケット余りが</td><td class="tbl-num_top"><?php echo $this->Html->link($unfixed_sales_lists['count'].'件', '/budgets/unfixed_sales/'); ?></td><td>あります。</td></tr>
    <tr><td class="tbl-intro_top">未対応の集金が</td><td class="tbl-num_top"><?php echo $this->Html->link($unfixed_collect_lists['count'].'件', '/budgets/unfixed_collect/'); ?></td><td>あります。</td></tr>
  </table>
</div>

<h3>本日の予定</h3>

  <?php if (count($event_today_lists) > 0) { ?>
  <table class="detail-list-min event-list-v2">
    <tr><th>イベント名</th>
        <th class="tbl-date-min">開演日時</th>
        <th>予定</th>
        <th class="tbl-date-min">日時</th>
        <th class="tbl-genre">種類<br>
                              状態</th>
        <th class="tbl-act-min">action</th></tr>
    
    <?php foreach ($event_today_lists AS $event_list) { ?>
    <tr><td class="title-main" colspan="4">
          <?php echo $event_list['Event']['title']; ?>
          <?php if ($event_list['Event']['title'] != $event_list['EventsDetail']['title']) { ?><br>
          <span class="title-sub"><?php echo $event_list['EventsDetail']['title']; ?></span>
          <?php } ?>
        </td>
        <td class="tbl-genre" rowspan="2"><span class="icon-genre col-entry_<?php echo $event_list['EventsEntry']['entries_genre_id']; ?>"><?php echo $event_list['EntryGenre']['title']; ?></span><br>
                                          <?php if ($event_list['EventsEntry']['status'] == 0) {echo '<span class="icon-like">検討中</span>';}
                                            elseif ($event_list['EventsEntry']['status'] == 1) {echo '<span class="icon-like">申込中</span>';}
                                            elseif ($event_list['EventsEntry']['status'] == 2) {echo '<span class="icon-true">当選</span>';}
                                            elseif ($event_list['EventsEntry']['status'] == 3) {echo '<span class="icon-false">落選</span>';}
                                            elseif ($event_list['EventsEntry']['status'] == 4) {echo '<span class="icon-false">見送り</span>';} ?></td>
        <td class="tbl-act-min" rowspan="2"><span class="icon-button"><?php echo $this->Html->link('詳細', '/event/'.$event_list['EventsDetail']['id']); ?></span></td></tr>
    <tr><td class="tbl-title-min"><span class="title-sub"><?php echo $event_list['EventsEntry']['title']; ?></span></td>
        <td class="tbl-date-min"><?php if ($event_list['EventsDetail']['date']) { ?>
                                   <?php echo date('m/d('.$week_lists[date('w', strtotime($event_list['EventsDetail']['date']))].')', strtotime($event_list['EventsDetail']['date'])); ?>
                                   <?php if ($event_list['EventsDetail']['time_start']) { ?><br>
                                     <?php echo date('H:i', strtotime($event_list['EventsDetail']['time_start'])); ?>
                                   <?php } ?>
                                 <?php } ?></td>
        <td class="tbl-title-min"><span class="txt-min"><?php echo $event_list['EventsEntry']['date_status']; ?></span></td>
        <td class="tbl-date-min"><?php if ($event_list['EventsEntry']['date_status'] != '本日開演') { ?>
                                   <?php echo date('m/d('.$week_lists[date('w', strtotime($event_list['EventsEntry'][$entryDateColumn[$event_list['EventsEntry']['date_status']]]))].')', strtotime($event_list['EventsEntry'][$entryDateColumn[$event_list['EventsEntry']['date_status']]])); ?><br>
                                   <?php echo date('H:i', strtotime($event_list['EventsEntry'][$entryDateColumn[$event_list['EventsEntry']['date_status']]])); ?>
                                 <?php } ?></td></tr>
    <?php } ?>
  </table>
  <?php } else { ?>
  <div class="intro_top">
    <p>
      本日の予定はありません。
    </p>
  </div>
  <?php } ?>

<div class="fr">
  <span class="link-page"><?php echo $this->Form->postLink('⇨ 本日の予定をメールに送る', array('controller' => 'email', 'action' => 'schedule_send', $userData['id']), null, '本日の予定を登録されているメールアドレスに送りますか'); ?></span>
</div>

<h3>直近の予定</h3>

  <?php if (count($event_current_lists) > 0) { ?>
  <table class="detail-list event-list-v2">
    <tr><th>イベント名</th>
        <th class="tbl-date-min">開演日時</th>
        <?php foreach ($entryDateColumn AS $key => $column) { ?>
        <th class="tbl-date-min"><?php echo $key; ?></th>
        <?php } ?>
        <th class="tbl-genre">種類<br>
                              状態</th>
        <th class="tbl-act-min">action</th></tr>
    
    <?php foreach ($event_current_lists AS $event_list) { ?>
    <tr><td class="title-main" colspan="<?php echo count($entryDateColumn)+2; ?>">
          <?php echo $event_list['Event']['title']; ?>
          <?php if ($event_list['Event']['title'] != $event_list['EventsDetail']['title']) { ?><br>
          <span class="title-sub"><?php echo $event_list['EventsDetail']['title']; ?></span>
          <?php } ?>
        </td>
        <td class="tbl-genre" rowspan="2"><span class="icon-genre col-entry_<?php echo $event_list['EventsEntry']['entries_genre_id']; ?>"><?php echo $event_list['EntryGenre']['title']; ?></span><br>
                                          <?php if ($event_list['EventsEntry']['status'] == 0) {echo '<span class="icon-like">検討中</span>';}
                                            elseif ($event_list['EventsEntry']['status'] == 1) {echo '<span class="icon-like">申込中</span>';}
                                            elseif ($event_list['EventsEntry']['status'] == 2) {echo '<span class="icon-true">当選</span>';}
                                            elseif ($event_list['EventsEntry']['status'] == 3) {echo '<span class="icon-false">落選</span>';}
                                            elseif ($event_list['EventsEntry']['status'] == 4) {echo '<span class="icon-false">見送り</span>';} ?></td>
        <td class="tbl-act-min" rowspan="2"><span class="icon-button"><?php echo $this->Html->link('詳細', '/event/'.$event_list['EventsDetail']['id']); ?></span></td></tr>
    <tr><td class="tbl-title-min"><span class="title-sub"><?php echo $event_list['EventsEntry']['title']; ?></span></td>
        <td class="tbl-date-min"><?php if ($event_list['EventsDetail']['date']) { ?>
                                   <?php echo date('m/d('.$week_lists[date('w', strtotime($event_list['EventsDetail']['date']))].')', strtotime($event_list['EventsDetail']['date'])); ?>
                                   <?php if ($event_list['EventsDetail']['time_start']) { ?><br>
                                     <?php echo date('H:i', strtotime($event_list['EventsDetail']['time_start'])); ?>
                                   <?php } ?>
                                 <?php } ?></td>
        <?php $status = 0; ?>
        <?php foreach ($entryDateColumn AS $column) { ?>
        <?php $status++; ?>
        <td class="tbl-date-min <?php echo ($event_list['EventsEntry']['date_closed'] >= $status)? 'date-closed': ''; ?>">
          <?php if ($event_list['EventsEntry'][$column]) { ?>
            <?php echo date('m/d('.$week_lists[date('w', strtotime($event_list['EventsEntry'][$column]))].')', strtotime($event_list['EventsEntry'][$column])); ?><br>
            <?php echo date('H:i', strtotime($event_list['EventsEntry'][$column])); ?>
          <?php } ?>
        </td>
        <?php } ?></tr>
    <?php } ?>
  </table>
  <?php } else { ?>
  <div class="intro_top">
    <p>
      直近の予定はありません。
    </p>
  </div>
  <?php } ?>