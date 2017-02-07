<?php echo $this->Html->css('budgets', array('inline' => false)); ?>
<h3><?php echo $sub_page; ?></h3>

<div class="intro intro_budgets">
  <?php if (@$column == 'payment') { ?>
    <p>
      当選しているイベントでクレジットカード決済ではないエントリー一覧になります。<br>
      金額が 0円 のもの、開催日が過ぎたものは基本的に表示されません。<br>
      開催日を過ぎたイベントは決済方法が買取のエントリーのみ表示されます。<br>
      <br>
      確定ボタンを押す事で支払済みとして扱われ一覧には表示されなくなります。
    </p>
  <?php } elseif (@$column == 'sales') { ?>
    <p>
      当選しているイベントで複数枚ある場合、またイベントが被る場合に表示されます。<br>
      開催日を過ぎたイベントは表示されません。<br>
      <br>
      確定ボタンを押す事で引取先が確定したと扱われ一覧には表示されなくなります。
    </p>
  <?php } elseif (@$column == 'collect') { ?>
    <p>
      当選しているイベントで引取先が確定している場合に表示されます。<br>
      金額が 0円 のものは表示されません。<br>
      <br>
      確定ボタンを押す事で集金済みとして扱われ一覧には表示されなくなります。
    </p>
  <?php } else { ?>
    <p>
      <?php echo $sub_page; ?>になります。
      <?php if (@$reset_column) { ?><br>
        <br>
        戻すボタンを押す事で確定した処理を元に戻す事ができます。
      <?php } ?>
    </p>
  <?php } ?>
</div>

<?php if ($unfixed_lists['count'] > 0) { ?>
  <table class="detail-list-min event-list-v2">
    <tr><th>イベント名</th>
        <th class="tbl-date-min">開演日時</th>
        <th class="tbl-date-min">入金締切</th>
        <th class="tbl-num_budgets">価格<br>
                                    枚数</th>
        <th class="tbl-act">action</th></tr>
    
    <?php foreach ($unfixed_lists['list'] as $event_list) { ?>
      <tr><td class="title-main" colspan="5">
            <?php echo $event_list['Event']['title']; ?>
            <?php if ($event_list['Event']['title'] != $event_list['EventsDetail']['title']) { ?><br>
              <span class="title-sub"><?php echo $event_list['EventsDetail']['title']; ?></span>
            <?php } ?>
          </td></tr>
      <?php $i = 1; ?>
      <?php foreach ($event_list['EventsEntry'] as $entry_list) { ?>
        <tr><td class="tbl-title-min <?php echo ($i == count($event_list['EventsEntry']))? '' : 'border-non'; ?>">
              <span class="title-sub"><?php echo $entry_list['title']; ?></span>
            </td>
            <td class="tbl-date-min <?php echo ($i == count($event_list['EventsEntry']))? '' : 'border-non'; ?>">
              <?php if ($event_list['EventsDetail']['date']) { ?>
                <?php echo date('m/d(' . $week_lists[date('w', strtotime($event_list['EventsDetail']['date']))] . ')', strtotime($event_list['EventsDetail']['date'])); ?>
                <?php if ($event_list['EventsDetail']['time_start']) { ?><br>
                  <?php echo date('H:i', strtotime($event_list['EventsDetail']['time_start'])); ?>
                <?php } ?>
              <?php } ?>
            </td>
            <td class="tbl-date-min <?php echo ($i == count($event_list['EventsEntry']))? '' : 'border-non'; ?>">
              <?php if ($entry_list['date_payment']) { ?>
                <?php echo date('m/d(' . $week_lists[date('w', strtotime($entry_list['date_payment']))] . ')', strtotime($entry_list['date_payment'])); ?><br>
                <?php echo date('H:i', strtotime($entry_list['date_payment'])); ?>
              <?php } ?>
            </td>
            <td class="tbl-num_budgets <?php echo ($i == count($event_list['EventsEntry']))? '' : 'border-non'; ?>">
              <?php echo $entry_list['price']; ?>円<br>
              <?php echo $entry_list['number']; ?>枚<br>
              <?php foreach ($eventPaymentStatus as $payment_status) {
                  if ($payment_status['status'] == $entry_list['payment']) {
                      echo '<span class="txt-min">' . $payment_status['name'] . '</span>';
                      break;
                  }
              } ?>
            </td>
            <td class="tbl-act <?php echo ($i == count($event_list['EventsEntry']))? '' : 'border-non-act'; ?>">
              <span class="icon-button"><?php echo $this->Html->link('詳細', '/events/' . $event_list['EventsDetail']['id'], array('target' => '_blank')); ?></span>
              <?php $join_flg = ($entry_list['user_id'] == $userData['id'])? 0 : 1; ?>
              <?php if (@$column) { ?>
                <span class="icon-button"><?php echo $this->Form->postLink('確定', array('action' => 'fixed', $entry_list['id'], $column, $join_flg), null, '対応済みに変更しますか'); ?></span>
              <?php } elseif (@$reset_column) { ?>
                <span class="icon-button"><?php echo $this->Form->postLink('戻す', array('action' => 'reset', $entry_list['id'], $reset_column, $join_flg), null, '対応済みを元に戻しますか'); ?></span>
              <?php } ?>
            </td></tr>
        <?php $i++; ?>
      <?php } ?>
    <?php } ?>
  </table>
<?php } else { ?>
  <div class="intro">
    <p>現在、<?php echo (@$reset_column)? '元に戻せる' : '該当する'; ?>イベントはありません。</p>
  </div>
<?php } ?>

<?php if ($unfixed_lists['count'] > $BUDGET_LIMIT_KEY) { ?>
  <span class="txt-min txt_unfixed_lists">※最大<?php echo $BUDGET_LIMIT_KEY; ?>件まで表示されます。</span>
<?php } ?>

<div class="link-right">
  <?php if (@$column) { ?>
    <span class="link-page"><?php echo $this->Html->link('⇨ 対応済みに確定したイベントを戻す', '/budgets/reset_status/' . $column); ?></span>
  <?php } elseif (@$reset_column) { ?>
    <span class="link-page"><?php echo $this->Html->link('⇨ 未対応の一覧に戻る', '/budgets/unfixed_' . $reset_column); ?></span>
  <?php } ?>
</div>