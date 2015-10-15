<?php echo $this->Html->script('jquery-hide', array('inline' => FALSE)); ?>
<?php echo $this->Html->css('events', array('inline' => FALSE)); ?>
<button class="js-show js-hide-button fr cf">未対応のみ</button>
<button class="js-hide js-show-button fr cf">過去すべて</button>
<h3>過去のイベント一覧</h3>

  <div class="tbl-past_event_lists">
  <?php echo $this->Paginator->numbers(array(
      'modulus' => 4, //現在ページから左右あわせてインクルードする個数
      'separator' => '|', //デフォルト値のセパレーター
      'first' => '＜', //先頭ページへのリンク
      'last' => '＞' //最終ページへのリンク
  )); ?>

  <table class="detail-list-min js-show">
    <tr><th class="tbl-date">開催日<?php echo $this->Paginator->sort('date', '▼'); ?></th>
        <th>イベント名<?php echo $this->Paginator->sort('title', '▼'); ?></th>
        <th class="tbl-ico">種類<br>
                            状態</th>
        <th class="tbl-action">action</th></tr>
    
    <?php foreach ($event_lists AS $event_list) { ?>
    <tr><td class="tbl-date"><?php echo $event_list['Event']['date']; ?></td>
        <td><?php echo $event_list['Event']['title']; ?></td>
        <td class="tbl-ico"><span class="icon-genre col-event_<?php echo $event_list['Event']['genre_id']; ?>"><?php echo $event_list['EventGenre']['title']; ?></span>
                            <br><?php if ($event_list['Event']['status'] == 0) {echo '<span class="icon-genre">未定</span>';}
                                  elseif ($event_list['Event']['status'] == 1) {echo '<span class="icon-like">申込中</span>';}
                                  elseif ($event_list['Event']['status'] == 2) {echo '<span class="icon-true">確定</span>';}
                                  elseif ($event_list['Event']['status'] == 3) {echo '<span class="icon-false">落選</span>';} ?></td>
        <td class="tbl-action"><span class="icon-button"><?php echo $this->Html->link('詳細', '/event/'.$event_list['Event']['id'], array('target' => '_blank')); ?></span>
            <?php if ($event_list['Event']['user_id'] == $this->Session->read('Auth.User.id')) { ?>
            <br><span class="icon-button"><?php echo $this->Html->link('修正', '/events/edit/'.$event_list['Event']['id']); ?></span>
            <span class="icon-button"><?php echo $this->Form->postLink('削除', array('action' => 'event_lists_delete', $event_list['Event']['id']), null, '本当に削除しますか'); ?></span>
            <?php } ?></td></tr>
    <?php } ?>
  </table>
  </div>

  <?php if (count($event_undecided_lists) > 0) { ?>
  <div class="tbl-past_event_lists js-hide">
  <table class="detail-list-min">
    <tr><th class="tbl-date">開催日</th>
        <th>イベント名</th>
        <th class="tbl-ico">種類<br>
                            状態</th>
        <th class="tbl-action">action</th></tr>
    
    <?php foreach ($event_undecided_lists AS $event_undecided_list) { ?>
    <tr><td class="tbl-date"><?php echo $event_undecided_list['Event']['date']; ?></td>
        <td><?php echo $event_undecided_list['Event']['title']; ?></td>
        <td class="tbl-ico"><span class="icon-genre col-event_<?php echo $event_undecided_list['Event']['genre_id']; ?>"><?php echo $event_undecided_list['EventGenre']['title']; ?></span>
                            <br><?php if ($event_undecided_list['Event']['status'] == 0) {echo '<span class="icon-genre">未定</span>';}
                                  elseif ($event_undecided_list['Event']['status'] == 1) {echo '<span class="icon-like">申込中</span>';}
                                  elseif ($event_undecided_list['Event']['status'] == 2) {echo '<span class="icon-true">確定</span>';}
                                  elseif ($event_undecided_list['Event']['status'] == 3) {echo '<span class="icon-false">落選</span>';} ?></td>
        <td class="tbl-action"><span class="icon-button"><?php echo $this->Html->link('詳細', '/event/'.$event_undecided_list['Event']['id'], array('target' => '_blank')); ?></span>
            <?php if ($event_undecided_list['Event']['user_id'] == $this->Session->read('Auth.User.id')) { ?>
            <br><span class="icon-button"><?php echo $this->Html->link('修正', '/events/edit/'.$event_undecided_list['Event']['id']); ?></span>
            <span class="icon-button"><?php echo $this->Form->postLink('削除', array('action' => 'event_lists_delete', $event_undecided_list['Event']['id']), null, '本当に削除しますか'); ?></span>
            <?php } ?></td></tr>
    <?php } ?>
  </table>
  </div>
  <?php } else { ?>
  <div class="intro_events js-hide">
    <P>
      未対応のイベントはありません。
    </P>
  </div>
  <?php } ?>