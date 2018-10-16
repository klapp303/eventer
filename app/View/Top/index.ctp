<?php echo $this->Html->css('top', array('inline' => false)); ?>
<div class="intro intro_top">
  <table class="detail-list-min">
    <tr><td class="tbl-intro_top">未対応の支払いが</td><td class="tbl-num_top"><?php echo $this->Html->link($unfixed_payment_lists['count'] . '件', '/budgets/unfixed_payment/'); ?></td><td>あります。</td></tr>
    <tr><td class="tbl-intro_top">未対応のチケット余りが</td><td class="tbl-num_top"><?php echo $this->Html->link($unfixed_sales_lists['count'] . '件', '/budgets/unfixed_sales/'); ?></td><td>あります。</td></tr>
    <tr><td class="tbl-intro_top">未対応の集金が</td><td class="tbl-num_top"><?php echo $this->Html->link($unfixed_collect_lists['count'] . '件', '/budgets/unfixed_collect/'); ?></td><td>あります。</td></tr>
  </table>
</div>

<h3>本日の予定</h3>

  <?php if (count($event_today_lists) > 0): ?>
  <table class="detail-list-min event-list-v2">
    <tr><th>イベント名</th>
        <th class="tbl-date-min">開演日時</th>
        <th>予定</th>
        <th class="tbl-date-min">日時</th>
        <th class="tbl-genre">status</th>
        <th class="tbl-act-min">action</th></tr>
    
    <?php foreach ($event_today_lists as $event_list): ?>
    <tr><td class="title-main txt-b" colspan="4">
          <?php echo $event_list['Event']['title']; ?>
          <?php if ($event_list['Event']['title'] != $event_list['EventsDetail']['title']): ?><br>
          <span class="title-sub"><?php echo $event_list['EventsDetail']['title']; ?></span>
          <?php endif; ?>
        </td>
        <td class="tbl-genre" rowspan="2"><span class="icon-genre col-rule_<?php echo $event_list['EntryGenre']['entry_rule_id']; ?>"><?php echo $event_list['EntryGenre']['EntryRule']['title']; ?></span><br>
                                          <span class="icon-genre col-cost_<?php echo $event_list['EntryGenre']['entry_cost_id']; ?>"><?php echo $event_list['EntryGenre']['EntryCost']['title']; ?></span><br>
                                          <?php foreach ($eventEntryStatus as $entry_status) {
                                              if ($entry_status['status'] == $event_list['EventsEntry']['status']) {
                                                  echo '<span class="icon-' . $entry_status['class'] . '">' . $entry_status['name'] . '</span>';
                                                  break;
                                              }
                                          } ?></td>
        <td class="tbl-act-min" rowspan="2"><span class="icon-button"><?php echo $this->Html->link('詳細', '/events/' . $event_list['EventsDetail']['id']); ?></span></td></tr>
    <tr><td class="tbl-title-min"><span class="title-sub"><?php echo $event_list['EventsEntry']['title']; ?></span></td>
        <td class="tbl-date-min"><?php if ($event_list['EventsDetail']['date']): ?>
                                 <?php echo date('m/d(' . $week_lists[date('w', strtotime($event_list['EventsDetail']['date']))] . ')', strtotime($event_list['EventsDetail']['date'])); ?>
                                   <?php if ($event_list['EventsDetail']['time_start']): ?><br>
                                   <?php echo date('H:i', strtotime($event_list['EventsDetail']['time_start'])); ?>
                                   <?php endif; ?>
                                 <?php endif; ?></td>
        <td class="tbl-title-min"><span class="txt-min"><?php echo $event_list['EventsEntry']['date_status']; ?></span></td>
        <td class="tbl-date-min"><?php if ($event_list['EventsEntry']['date_status'] != '本日開演'): ?>
                                 <?php echo date('m/d(' . $week_lists[date('w', strtotime($event_list['EventsEntry'][$entryDateColumn[$event_list['EventsEntry']['date_status']]]))] . ')', strtotime($event_list['EventsEntry'][$entryDateColumn[$event_list['EventsEntry']['date_status']]])); ?><br>
                                 <?php echo date('H:i', strtotime($event_list['EventsEntry'][$entryDateColumn[$event_list['EventsEntry']['date_status']]])); ?>
                                 <?php endif; ?></td></tr>
    <?php endforeach; ?>
  </table>
  <?php else: ?>
  <div class="intro">
    <p>本日の予定はありません。</p>
  </div>
  <?php endif; ?>

<div class="fr">
  <span class="link-page"><?php echo $this->Form->postLink('⇨ 本日の予定をメールに送る', array('controller' => 'email', 'action' => 'schedule_send', $userData['id']), null, '本日の予定を登録されているメールアドレスに送りますか'); ?></span>
</div>

<h3>直近の予定</h3>

  <?php if (count($event_current_lists) > 0): ?>
  <table class="detail-list event-list-v2">
    <tr><th>イベント名</th>
        <th class="tbl-date-min">開演日時</th>
        <?php foreach ($entryDateColumn as $key => $column): ?>
        <th class="tbl-date-min"><?php echo $key; ?></th>
        <?php endforeach; ?>
        <th class="tbl-genre">status</th>
        <th class="tbl-act-min">action</th></tr>
    
    <?php foreach ($event_current_lists as $event_list): ?>
    <tr><td class="title-main txt-b" colspan="<?php echo count($entryDateColumn) +2; ?>">
          <?php echo $event_list['Event']['title']; ?>
          <?php if ($event_list['Event']['title'] != $event_list['EventsDetail']['title']): ?><br>
          <span class="title-sub"><?php echo $event_list['EventsDetail']['title']; ?></span>
          <?php endif; ?>
        </td>
        <td class="tbl-genre" rowspan="2"><span class="icon-genre col-rule_<?php echo $event_list['EntryGenre']['entry_rule_id']; ?>"><?php echo $event_list['EntryGenre']['EntryRule']['title']; ?></span><br>
                                          <span class="icon-genre col-cost_<?php echo $event_list['EntryGenre']['entry_cost_id']; ?>"><?php echo $event_list['EntryGenre']['EntryCost']['title']; ?></span><br>
                                          <?php foreach ($eventEntryStatus as $entry_status) {
                                              if ($entry_status['status'] == $event_list['EventsEntry']['status']) {
                                                  echo '<span class="icon-' . $entry_status['class'] . '">' . $entry_status['name'] . '</span>';
                                                  break;
                                              }
                                          } ?></td>
        <td class="tbl-act-min" rowspan="2"><span class="icon-button"><?php echo $this->Html->link('詳細', '/events/' . $event_list['EventsDetail']['id']); ?></span></td></tr>
    <tr><td class="tbl-title-min"><span class="title-sub"><?php echo $event_list['EventsEntry']['title']; ?></span></td>
        <td class="tbl-date-min"><?php if ($event_list['EventsDetail']['date']): ?>
                                 <?php echo date('m/d(' . $week_lists[date('w', strtotime($event_list['EventsDetail']['date']))] . ')', strtotime($event_list['EventsDetail']['date'])); ?>
                                   <?php if ($event_list['EventsDetail']['time_start']): ?><br>
                                   <?php echo date('H:i', strtotime($event_list['EventsDetail']['time_start'])); ?>
                                   <?php endif; ?>
                                 <?php endif; ?></td>
        <?php $status = 0; ?>
        <?php foreach ($entryDateColumn as $column): ?>
        <?php $status++; ?>
        <td class="tbl-date-min <?php echo ($event_list['EventsEntry']['date_closed'] >= $status)? 'date-closed' : ''; ?>">
          <?php if ($event_list['EventsEntry'][$column]): ?>
          <?php echo date('m/d(' . $week_lists[date('w', strtotime($event_list['EventsEntry'][$column]))] . ')', strtotime($event_list['EventsEntry'][$column])); ?><br>
          <?php echo date('H:i', strtotime($event_list['EventsEntry'][$column])); ?>
          <?php endif; ?>
        </td>
        <?php endforeach; ?></tr>
    <?php endforeach; ?>
  </table>
  <?php else: ?>
  <div class="intro">
    <p>直近の予定はありません。</p>
  </div>
  <?php endif; ?>