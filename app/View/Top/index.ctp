<?php echo $this->Html->css('top', array('inline' => FALSE)); ?>
<h3>本日の予定</h3>

  <?php if (count($event_today_lists) > 0) { ?>
  <table class="detail-list-min">
    <tr><th>イベント名</th>
        <th class="tbl-date-min">開演日時</th>
        <th>予定</th>
        <th class="tbl-date-min">日時</th>
        <th class="tbl-genre">種類<br>
                              状態</th>
        <th class="tbl-act-min">action</th></tr>
    
    <?php foreach ($event_today_lists AS $event_list) { ?>
    <tr><td colspan="4">
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
    <tr><td><?php echo $event_list['EventsEntry']['title']; ?></td>
        <td class="tbl-date-min"><?php if ($event_list['EventsDetail']['date']) { ?>
                                   <?php echo date('m/d('.$week_lists[date('w', strtotime($event_list['EventsDetail']['date']))].')', strtotime($event_list['EventsDetail']['date'])); ?>
                                   <?php if ($event_list['EventsDetail']['time_start']) { ?><br>
                                     <?php echo date('H:i', strtotime($event_list['EventsDetail']['time_start'])); ?>
                                   <?php } ?>
                                 <?php } ?></td>
        <td><?php echo $event_list['EventsEntry']['date_status']; ?></td>
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

<h3>直近の予定</h3>

  <?php if (count($event_current_lists) > 0) { ?>
  <table class="detail-list">
    <tr><th>イベント名</th>
        <th class="tbl-date-min">開演日時</th>
        <?php foreach ($entryDateColumn AS $key => $column) { ?>
        <th class="tbl-date-min"><?php echo $key; ?></th>
        <?php } ?>
        <th class="tbl-genre">種類<br>
                              状態</th>
        <th class="tbl-act-min">action</th></tr>
    
    <?php foreach ($event_current_lists AS $event_list) { ?>
    <tr><td colspan="<?php echo count($entryDateColumn)+2; ?>">
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
    <tr><td><?php echo $event_list['EventsEntry']['title']; ?></td>
        <td class="tbl-date-min"><?php if ($event_list['EventsDetail']['date']) { ?>
                                   <?php echo date('m/d('.$week_lists[date('w', strtotime($event_list['EventsDetail']['date']))].')', strtotime($event_list['EventsDetail']['date'])); ?>
                                   <?php if ($event_list['EventsDetail']['time_start']) { ?><br>
                                     <?php echo date('H:i', strtotime($event_list['EventsDetail']['time_start'])); ?>
                                   <?php } ?>
                                 <?php } ?></td>
        <?php foreach ($entryDateColumn AS $column) { ?>
        <td class="tbl-date-min"><?php if ($event_list['EventsEntry'][$column]) { ?>
                                   <?php echo date('m/d('.$week_lists[date('w', strtotime($event_list['EventsEntry'][$column]))].')', strtotime($event_list['EventsEntry'][$column])); ?><br>
                                   <?php echo date('H:i', strtotime($event_list['EventsEntry'][$column])); ?>
                                 <?php } ?></td>
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

<!--h3>管理未対応のイベント、収支</h3>

  </*?php $event_undecided_count = count($event_undecided_lists);*/ ?>
  <div class="intro_top">
    <p>
      未対応のイベントが <?php /*echo $this->Html->link($event_undecided_count.'件', '/events/past_lists/');*/ ?> あります。
    </p>
    <p>
      未対応の収支が <?php /*echo $this->Html->link($budget_undecided_count.'件', '/budgets/in_lists/');*/ ?> あります。
    </p>
  </div-->