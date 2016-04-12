<?php echo $this->Html->css('budgets', array('inline' => FALSE)); ?>
<h3><?php if (preg_match('#/budgets/unfixed_entry#', $_SERVER['REQUEST_URI'])) {
      echo '未対応の支払い一覧';
    } elseif (preg_match('#/budgets/unfixed_ticket#', $_SERVER['REQUEST_URI'])) {
      echo 'チケット余り一覧';
    } elseif (preg_match('#/budgets/unfixed_collect#', $_SERVER['REQUEST_URI'])) {
      echo '未対応の集金一覧';
    } else {
      echo '未対応の一覧';
    } ?></h3>

<?php if ($unfixed_lists['count'] > 0) { ?>
  <table class="detail-list-min">
    <tr><th>イベント名</th>
        <th class="tbl-date-min">開演日時</th>
        <th class="tbl-date-min">入金締切</th>
        <th class="tbl-num_budgets">価格<br>
                                    枚数</th>
        <th class="tbl-act">action</th></tr>
    
    <?php foreach ($unfixed_lists['list'] AS $event_list) { ?>
    <tr><td colspan="5">
          <?php echo $event_list['Event']['title']; ?>
          <?php if ($event_list['Event']['title'] != $event_list['EventsDetail']['title']) { ?><br>
          <span class="title-sub"><?php echo $event_list['EventsDetail']['title']; ?></span>
          <?php } ?>
        </td></tr>
    <?php foreach ($event_list['EventsEntry'] AS $entry_list) { ?>
    <tr><td><?php echo $entry_list['title']; ?></td>
        <td class="tbl-date-min"><?php if ($event_list['EventsDetail']['date']) { ?>
                                   <?php echo date('m/d('.$week_lists[date('w', strtotime($event_list['EventsDetail']['date']))].')', strtotime($event_list['EventsDetail']['date'])); ?>
                                   <?php if ($event_list['EventsDetail']['time_start']) { ?><br>
                                     <?php echo date('H:i', strtotime($event_list['EventsDetail']['time_start'])); ?>
                                   <?php } ?>
                                 <?php } ?></td>
        <td class="tbl-date-min"><?php if ($entry_list['date_payment']) { ?>
                                   <?php echo date('m/d('.$week_lists[date('w', strtotime($entry_list['date_payment']))].')', strtotime($entry_list['date_payment'])); ?><br>
                                   <?php echo date('H:i', strtotime($entry_list['date_payment'])); ?>
                                 <?php } ?></td>
        <td class="tbl-num_budgets"><?php echo $entry_list['price']; ?>円<br>
                                        <?php echo $entry_list['number']; ?>枚<br>
                                        <?php if ($entry_list['payment'] == 'credit') {echo '<span class="txt-min">クレジットカード</span>';}
                                          elseif ($entry_list['payment'] == 'conveni') {echo '<span class="txt-min">コンビニ支払</span>';}
                                          elseif ($entry_list['payment'] == 'delivery') {echo '<span class="txt-min">代金引換</span>';}
                                          elseif ($entry_list['payment'] == 'buy') {echo '<span class="txt-min">買取</span>';}
                                          elseif ($entry_list['payment'] == 'other') {echo '<span class="txt-min">その他</span>';} ?></td>
        <td class="tbl-act"><span class="icon-button"><?php echo $this->Html->link('詳細', '/event/'.$event_list['EventsDetail']['id'], array('target' => '_blank')); ?></span>
                                        <?php if (preg_match('#/budgets/unfixed_entry#', $_SERVER['REQUEST_URI'])) {
                                          $update_column = 'payment_status';
                                        } elseif (preg_match('#/budgets/unfixed_ticket#', $_SERVER['REQUEST_URI'])) {
                                          $update_column = 'sales_status';
                                        } elseif (preg_match('#/budgets/unfixed_collect#', $_SERVER['REQUEST_URI'])) {
                                          $update_column = 'collect_status';
                                        } ?>
                                        <span class="icon-button"><?php echo $this->Form->postLink('確定', array('action' => 'fixed', $entry_list['id'], $update_column), null, '対応済みに変更しますか'); ?></span></td></tr>
    <?php } ?>
    <?php } ?>
  </table>
<?php } else { ?>
<div class="intro_budgets">
  <p>
    現在、
    <?php if (preg_match('#/budgets/unfixed_entry#', $_SERVER['REQUEST_URI'])) {
      echo '未対応の支払い';
    } elseif (preg_match('#/budgets/unfixed_ticket#', $_SERVER['REQUEST_URI'])) {
      echo 'チケット余り';
    } elseif (preg_match('#/budgets/unfixed_collect#', $_SERVER['REQUEST_URI'])) {
      echo '未対応の集金';
    } ?>
    はありません。
  </p>
</div>
<?php } ?>