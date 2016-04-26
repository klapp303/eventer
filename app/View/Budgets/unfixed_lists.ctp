<?php echo $this->Html->css('budgets', array('inline' => FALSE)); ?>
<h3><?php if (@$column == 'paymnet') {
      echo '未対応の支払い一覧';
    } elseif (@$column == 'sales') {
      echo 'チケット余り一覧';
    } elseif (@$column == 'collect') {
      echo '未対応の集金一覧';
    } else {
      echo '対応済みに確定したエントリー一覧';
    } ?></h3>

<div class="intro_budgets">
  <?php if (@$column == 'payment') { ?>
  <p>
    当選しているイベントでクレジットカード決済ではないエントリー一覧になります。<br>
    金額が 0円 のもの、開催日が過ぎたものは基本的に表示されません。<br>
    開催日を過ぎたイベントは決裁方法が買取のエントリーのみ表示されます。<br>
    <br>
    確定ボタンを押す事で支払済みとして扱われ一覧には表示されなくなります。
  </p>
  <?php } elseif (@$column == 'sales') { ?>
  <p>
    イベント全体で複数枚の当選がある場合に表示されます。<br>
    開催日を過ぎたイベントは表示されません。<br>
    <br>
    確定ボタンを押す事で引取先が確定したと扱われ、<br>
    イベント全体の当選枚数が 1枚 以下になれば一覧には表示されなくなります。
  </p>
  <?php } elseif (@$column == 'collect') { ?>
  <p>
    チケット余りのあるイベントで引取先が確定している場合に表示されます。<br>
    金額が 0円 のものは表示されません。<br>
    <br>
    確定ボタンを押す事で集金済みとして扱われ一覧には表示されなくなります。
  </p>
  <?php } else { ?>
  <p>
    対応済みに確定したエントリー一覧になります。
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
    
    <?php foreach ($unfixed_lists['list'] AS $event_list) { ?>
    <tr><td class="title-main" colspan="5">
          <?php echo $event_list['Event']['title']; ?>
          <?php if ($event_list['Event']['title'] != $event_list['EventsDetail']['title']) { ?><br>
          <span class="title-sub"><?php echo $event_list['EventsDetail']['title']; ?></span>
          <?php } ?>
        </td></tr>
    <?php $i = 1; ?>
    <?php foreach ($event_list['EventsEntry'] AS $entry_list) { ?>
    <tr><td class="tbl-title-min <?php echo ($i == count($event_list['EventsEntry']))? '': 'border-non'; ?>">
          <span class="title-sub"><?php echo $entry_list['title']; ?></span>
        </td>
        <td class="tbl-date-min <?php echo ($i == count($event_list['EventsEntry']))? '': 'border-non'; ?>">
          <?php if ($event_list['EventsDetail']['date']) { ?>
            <?php echo date('m/d('.$week_lists[date('w', strtotime($event_list['EventsDetail']['date']))].')', strtotime($event_list['EventsDetail']['date'])); ?>
            <?php if ($event_list['EventsDetail']['time_start']) { ?><br>
              <?php echo date('H:i', strtotime($event_list['EventsDetail']['time_start'])); ?>
            <?php } ?>
          <?php } ?>
        </td>
        <td class="tbl-date-min <?php echo ($i == count($event_list['EventsEntry']))? '': 'border-non'; ?>">
          <?php if ($entry_list['date_payment']) { ?>
            <?php echo date('m/d('.$week_lists[date('w', strtotime($entry_list['date_payment']))].')', strtotime($entry_list['date_payment'])); ?><br>
            <?php echo date('H:i', strtotime($entry_list['date_payment'])); ?>
          <?php } ?>
        </td>
        <td class="tbl-num_budgets <?php echo ($i == count($event_list['EventsEntry']))? '': 'border-non'; ?>">
          <?php echo $entry_list['price']; ?>円<br>
          <?php echo $entry_list['number']; ?>枚<br>
          <?php if ($entry_list['payment'] == 'credit') {echo '<span class="txt-min">クレジットカード</span>';}
            elseif ($entry_list['payment'] == 'conveni') {echo '<span class="txt-min">コンビニ支払</span>';}
            elseif ($entry_list['payment'] == 'delivery') {echo '<span class="txt-min">代金引換</span>';}
            elseif ($entry_list['payment'] == 'buy') {echo '<span class="txt-min">買取</span>';}
            elseif ($entry_list['payment'] == 'other') {echo '<span class="txt-min">その他</span>';} ?>
        </td>
        <td class="tbl-act <?php echo ($i == count($event_list['EventsEntry']))? '': 'border-non-act'; ?>">
          <span class="icon-button"><?php echo $this->Html->link('詳細', '/event/'.$event_list['EventsDetail']['id'], array('target' => '_blank')); ?></span>
          <?php if (@$column) { ?>
            <span class="icon-button"><?php echo $this->Form->postLink('確定', array('action' => 'fixed', $entry_list['id'], $column.'_status'), null, '対応済みに変更しますか'); ?></span>
          <?php } elseif (@$reset_column) { ?>
            <span class="icon-button"><?php echo $this->Form->postLink('戻す', array('action' => 'reset', $entry_list['id'], $reset_column.'_status'), null, '対応済みを元に戻しますか'); ?></span>
          <?php } ?>
        </td></tr>
    <?php $i++; ?>
    <?php } ?>
    <?php } ?>
  </table>
<?php } else { ?>
<div class="intro_budgets">
  <p>
    現在、
    <?php if (@$column == 'payment') {
      echo '未対応の支払い';
    } elseif (@$column == 'sales') {
      echo 'チケット余り';
    } elseif (@$column == 'collect') {
      echo '未対応の集金';
    } else {
      echo '対応済み確定を元に戻せるエントリー';
    } ?>
    はありません。
  </p>
</div>
<?php } ?>

<div class="link-page_budgets">
  <?php if (@$column) { ?>
  <span class="link-page"><?php echo $this->Html->link('⇨ 対応済みに確定したイベントを戻す', '/budgets/reset_status/'.$column); ?></span>
  <?php } elseif (@$reset_column) { ?>
  <span class="link-page"><?php echo $this->Html->link('⇨ 未対応の一覧に戻る', '/budgets/unfixed_'.$reset_column); ?></span>
  <?php } ?>
</div>