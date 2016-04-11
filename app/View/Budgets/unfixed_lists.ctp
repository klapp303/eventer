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
    
    <?php foreach ($unfixed_lists['list'] AS $entry_list) { ?>
    <tr><td colspan="3">
          <?php echo $entry_list['Event']['title']; ?>
          <?php if ($entry_list['Event']['title'] != $entry_list['EventsDetail']['title']) { ?><br>
          <span class="title-sub"><?php echo $entry_list['EventsDetail']['title']; ?></span>
          <?php } ?>
        </td>
        <td class="tbl-num_budgets" rowspan="2"><?php echo $entry_list['EventsEntry']['price']; ?>円<br>
                                        <?php echo $entry_list['EventsEntry']['number']; ?>枚<br>
                                        <?php if ($entry_list['EventsEntry']['payment'] == 'credit') {echo '<span class="txt-min">クレジットカード</span>';}
                                          elseif ($entry_list['EventsEntry']['payment'] == 'conveni') {echo '<span class="txt-min">コンビニ支払</span>';}
                                          elseif ($entry_list['EventsEntry']['payment'] == 'delivery') {echo '<span class="txt-min">代金引換</span>';}
                                          elseif ($entry_list['EventsEntry']['payment'] == 'buy') {echo '<span class="txt-min">買取</span>';}
                                          elseif ($entry_list['EventsEntry']['payment'] == 'other') {echo '<span class="txt-min">その他</span>';} ?></td>
        <td class="tbl-act" rowspan="2"><span class="icon-button"><?php echo $this->Html->link('詳細', '/event/'.$entry_list['EventsDetail']['id'], array('target' => '_blank')); ?></span>
                                        <?php if (preg_match('#/budgets/unfixed_entry#', $_SERVER['REQUEST_URI'])) {
                                          $update_column = 'payment_status';
                                        } elseif (preg_match('#/budgets/unfixed_ticket#', $_SERVER['REQUEST_URI'])) {
                                          $update_column = 'sales_status';
                                        } elseif (preg_match('#/budgets/unfixed_collect#', $_SERVER['REQUEST_URI'])) {
                                          $update_column = 'collect_status';
                                        } ?>
                                        <span class="icon-button"><?php echo $this->Form->postLink('確定', array('action' => 'fixed', $entry_list['EventsEntry']['id'], $update_column), null, '対応済みに変更しますか'); ?></span></td></tr>
    <tr><td><?php echo $entry_list['EventsEntry']['title']; ?></td>
        <td class="tbl-date-min"><?php if ($entry_list['EventsDetail']['date']) { ?>
                                   <?php echo date('m/d('.$week_lists[date('w', strtotime($entry_list['EventsDetail']['date']))].')', strtotime($entry_list['EventsDetail']['date'])); ?>
                                   <?php if ($entry_list['EventsDetail']['time_start']) { ?><br>
                                     <?php echo date('H:i', strtotime($entry_list['EventsDetail']['time_start'])); ?>
                                   <?php } ?>
                                 <?php } ?></td>
        <td class="tbl-date-min">
          <?php if ($entry_list['EventsEntry']['date_payment']) { ?>
            <?php echo date('m/d('.$week_lists[date('w', strtotime($entry_list['EventsEntry']['date_payment']))].')', strtotime($entry_list['EventsEntry']['date_payment'])); ?><br>
            <?php echo date('H:i', strtotime($entry_list['EventsEntry']['date_payment'])); ?>
          <?php } ?>
        </td></tr>
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