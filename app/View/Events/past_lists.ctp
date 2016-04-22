<?php echo $this->Html->script('jquery-hide', array('inline' => FALSE)); ?>
<?php echo $this->Html->css('events', array('inline' => FALSE)); ?>
<button class="js-show js-hide-button fr cf">未対応のみを表示する</button>
<button class="js-hide js-show-button fr cf">過去すべてを表示する</button>
<h3>過去のイベント一覧</h3>

<div class="intro_events">
  <P>
    過去に行われたイベントの一覧になります。<br>
    <br>
    右上のボタンから当落見送りの結果が登録されていないイベントのみを<br>
    表示する事ができます。
  </P>
</div>

  <div class="tbl-event_lists js-show">
  <?php echo $this->Paginator->numbers(array(
      'modulus' => 4, //現在ページから左右あわせてインクルードする個数
      'separator' => '|', //デフォルト値のセパレーター
      'first' => '＜', //先頭ページへのリンク
      'last' => '＞' //最終ページへのリンク
  )); ?>

  <table class="detail-list event-list">
    <tr><th class="tbl-date">開催日<?php echo $this->Paginator->sort('EventsDetail.date', '▼'); ?></th>
        <th>イベント名<?php echo $this->Paginator->sort('Event.title', '▼'); ?></th>
        <th class="tbl-genre">種類<br>
                              状態</th>
        <th class="tbl-act">action</th></tr>
    
    <?php foreach ($event_lists AS $event_list) { ?>
    <tr><td class="tbl-date"><?php echo date('Y/m/d('.$week_lists[date('w', strtotime($event_list['EventsDetail']['date']))].')', strtotime($event_list['EventsDetail']['date'])); ?></td>
        <td><?php echo $event_list['Event']['title']; ?>
            <?php if ($event_list['Event']['title'] != $event_list['EventsDetail']['title']) { ?><br>
            <span class="title-sub"><?php echo $event_list['EventsDetail']['title']; ?></span>
            <?php } ?>
        </td>
        <td class="tbl-genre"><span class="icon-genre col-event_<?php echo $event_list['EventsDetail']['genre_id']; ?>"><?php echo $event_list['EventGenre']['title']; ?></span><br>
                              <?php if ($event_list['EventsDetail']['status'] == 0) {echo '<span class="icon-like">検討中</span>';}
                                elseif ($event_list['EventsDetail']['status'] == 1) {echo '<span class="icon-like">申込中</span>';}
                                elseif ($event_list['EventsDetail']['status'] == 2) {echo '<span class="icon-true">当選</span>';}
                                elseif ($event_list['EventsDetail']['status'] == 3) {echo '<span class="icon-false">落選</span>';}
                                elseif ($event_list['EventsDetail']['status'] == 4) {echo '<span class="icon-false">見送り</span>';} ?></td>
        <td class="tbl-act"><span class="icon-button"><?php echo $this->Html->link('詳細', '/event/'.$event_list['EventsDetail']['id'], array('target' => '_blank')); ?></span>
                            <?php if ($event_list['EventsDetail']['user_id'] == $userData['id']) { ?>
                            <br><span class="icon-button"><?php echo $this->Html->link('修正', '/events/edit/'.$event_list['Event']['id']); ?></span>
                            <span class="icon-button"><?php echo $this->Form->postLink('削除', array('action' => 'past_lists_delete', $event_list['EventsDetail']['id']), null, ($event_list['Event']['title'] != $event_list['EventsDetail']['title'])? $event_list['Event']['title'].' の
'.$event_list['EventsDetail']['title'].' を本当に削除しますか': $event_list['EventsDetail']['title'].' を本当に削除しますか'); ?></span>
            <?php } ?></td></tr>
    <?php } ?>
  </table>
  </div>

  <?php if (count($event_undecided_lists) > 0) { ?>
  <div class="tbl-event_lists js-hide">
  <table class="detail-list">
    <tr><th class="tbl-date">開催日</th>
        <th>イベント名</th>
        <th class="tbl-genre">種類<br>
                              状態</th>
        <th class="tbl-act">action</th></tr>
    
    <?php foreach ($event_undecided_lists AS $event_list) { ?>
    <tr><td class="tbl-date"><?php echo date('Y/m/d('.$week_lists[date('w', strtotime($event_list['EventsDetail']['date']))].')', strtotime($event_list['EventsDetail']['date'])); ?></td>
        <td><?php echo $event_list['Event']['title']; ?>
            <?php if ($event_list['Event']['title'] != $event_list['EventsDetail']['title']) { ?><br>
            <span class="title-sub"><?php echo $event_list['EventsDetail']['title']; ?></span>
            <?php } ?>
        </td>
        <td class="tbl-genre"><span class="icon-genre col-event_<?php echo $event_list['EventsDetail']['genre_id']; ?>"><?php echo $event_list['EventGenre']['title']; ?></span><br>
                              <?php if ($event_list['EventsDetail']['status'] == 0) {echo '<span class="icon-like">検討中</span>';}
                                elseif ($event_list['EventsDetail']['status'] == 1) {echo '<span class="icon-like">申込中</span>';}
                                elseif ($event_list['EventsDetail']['status'] == 2) {echo '<span class="icon-true">当選</span>';}
                                elseif ($event_list['EventsDetail']['status'] == 3) {echo '<span class="icon-false">落選</span>';}
                                elseif ($event_list['EventsDetail']['status'] == 4) {echo '<span class="icon-false">見送り</span>';} ?></td>
        <td class="tbl-act"><span class="icon-button"><?php echo $this->Html->link('詳細', '/event/'.$event_list['EventsDetail']['id'], array('target' => '_blank')); ?></span>
                            <?php if ($event_list['EventsDetail']['user_id'] == $userData['id']) { ?>
                            <br><span class="icon-button"><?php echo $this->Html->link('修正', '/events/edit/'.$event_list['Event']['id']); ?></span>
                            <span class="icon-button"><?php echo $this->Form->postLink('削除', array('action' => 'past_lists_delete', $event_list['EventsDetail']['id']), null, ($event_list['Event']['title'] != $event_list['EventsDetail']['title'])? $event_list['Event']['title'].' の
'.$event_list['EventsDetail']['title'].' を本当に削除しますか': $event_list['EventsDetail']['title'].' を本当に削除しますか'); ?></span>
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

<div class="link-page_events">
  <span class="link-page"><?php echo $this->Html->link('⇨ 公開されている全てのイベントはこちら', '/events/all_lists/'); ?></span>
</div>