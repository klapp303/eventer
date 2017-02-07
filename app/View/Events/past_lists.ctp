<?php echo $this->Html->script('jquery-hide', array('inline' => false)); ?>
<?php echo $this->Html->css('events', array('inline' => false)); ?>
<?php echo $this->element('searchbox', array(
    'action' => 'past_lists',
    'placeholder' => 'イベント名 を入力'
)); ?>
<button class="js-show js-hide-button fr cf">未対応のみを表示する</button>
<button class="js-hide js-show-button fr cf">過去すべてを表示する</button>
<h3><?php echo $sub_page; ?></h3>

<div class="intro intro_events">
  <P>
    過去に行われたイベントの一覧になります。<br>
    <br>
    右上のボタンから当落見送りの結果が登録されていないイベントのみを<br>
    表示する事ができます。
  </P>
</div>

  <div class="js-show">
  <?php echo $this->Paginator->numbers($paginator_option); ?>

  <table class="detail-list event-list">
    <tr><th class="tbl-date">開催日<?php echo $this->Paginator->sort('EventsDetail.date', '▼'); ?></th>
        <th>イベント名<?php echo $this->Paginator->sort('Event.title', '▼'); ?></th>
        <th class="tbl-genre">種類<br>
                              状態</th>
        <th class="tbl-act">action</th></tr>
    
    <?php foreach ($event_lists as $event_list) { ?>
      <tr><td class="tbl-date"><?php echo date('Y/m/d(' . $week_lists[date('w', strtotime($event_list['EventsDetail']['date']))] . ')', strtotime($event_list['EventsDetail']['date'])); ?></td>
          <td><?php echo $event_list['Event']['title']; ?>
              <?php if ($event_list['Event']['title'] != $event_list['EventsDetail']['title']) { ?><br>
                <span class="title-sub"><?php echo $event_list['EventsDetail']['title']; ?></span>
              <?php } ?>
          </td>
          <td class="tbl-genre"><span class="icon-genre col-event_<?php echo $event_list['EventsDetail']['genre_id']; ?>"><?php echo $event_list['EventGenre']['title']; ?></span><br>
                                <?php foreach ($eventEntryStatus as $entry_status) {
                                    if ($entry_status['status'] == $event_list['EventsDetail']['status']) {
                                        echo '<span class="icon-' . $entry_status['class'] . '">' . $entry_status['name'] . '</span>';
                                        break;
                                    }
                                } ?></td>
          <td class="tbl-act"><span class="icon-button"><?php echo $this->Html->link('詳細', '/events/' . $event_list['EventsDetail']['id'], array('target' => '_blank')); ?></span>
                              <?php if ($event_list['EventsDetail']['user_id'] == $userData['id']) { ?>
                                <br><span class="icon-button"><?php echo $this->Html->link('修正', '/events/edit/' . $event_list['Event']['id']); ?></span>
                                <span class="icon-button"><?php echo $this->Form->postLink('削除', array('action' => 'past_lists_delete', $event_list['EventsDetail']['id']), null, ($event_list['Event']['title'] != $event_list['EventsDetail']['title'])? $event_list['Event']['title'] . ' の
' . $event_list['EventsDetail']['title'] . ' を本当に削除しますか' : $event_list['EventsDetail']['title'] . ' を本当に削除しますか'); ?></span>
                              <?php } ?></td></tr>
    <?php } ?>
  </table>
  
  <?php echo $this->Paginator->numbers($paginator_option); ?>
  </div>

  <?php if (count($event_undecided_lists) > 0) { ?>
    <div class="tbl-event_lists js-hide">
    <table class="detail-list event-list">
      <tr><th class="tbl-date">開催日</th>
          <th>イベント名</th>
          <th class="tbl-genre">種類<br>
                                状態</th>
          <th class="tbl-act">action</th></tr>
      
      <?php foreach ($event_undecided_lists as $event_list) { ?>
        <tr><td class="tbl-date"><?php echo date('Y/m/d(' . $week_lists[date('w', strtotime($event_list['EventsDetail']['date']))] . ')', strtotime($event_list['EventsDetail']['date'])); ?></td>
            <td><?php echo $event_list['Event']['title']; ?>
                <?php if ($event_list['Event']['title'] != $event_list['EventsDetail']['title']) { ?><br>
                  <span class="title-sub"><?php echo $event_list['EventsDetail']['title']; ?></span>
                <?php } ?>
            </td>
            <td class="tbl-genre"><span class="icon-genre col-event_<?php echo $event_list['EventsDetail']['genre_id']; ?>"><?php echo $event_list['EventGenre']['title']; ?></span><br>
                                  <?php foreach ($eventEntryStatus as $entry_status) {
                                      if ($entry_status['status'] == $event_list['EventsDetail']['status']) {
                                          echo '<span class="icon-' . $entry_status['class'] . '">' . $entry_status['name'] . '</span>';
                                          break;
                                      }
                                  } ?></td>
            <td class="tbl-act"><span class="icon-button"><?php echo $this->Html->link('詳細', '/events/' . $event_list['EventsDetail']['id'], array('target' => '_blank')); ?></span>
                                <?php if ($event_list['EventsDetail']['user_id'] == $userData['id']) { ?>
                                  <br><span class="icon-button"><?php echo $this->Html->link('修正', '/events/edit/' . $event_list['Event']['id']); ?></span>
                                  <span class="icon-button"><?php echo $this->Form->postLink('削除', array('action' => 'past_lists_delete', $event_list['EventsDetail']['id']), null, ($event_list['Event']['title'] != $event_list['EventsDetail']['title'])? $event_list['Event']['title'] . ' の
' . $event_list['EventsDetail']['title'] . ' を本当に削除しますか' : $event_list['EventsDetail']['title'] . ' を本当に削除しますか'); ?></span>
                                <?php } ?></td></tr>
      <?php } ?>
    </table>
    </div>
  <?php } else { ?>
    <div class="intro js-hide">
      <P>
        未対応のイベントはありません。
      </P>
    </div>
  <?php } ?>

<div class="link-page_events">
  <span class="link-page"><?php echo $this->Html->link('⇨ 公開されている全てのイベントはこちら', '/events/all_lists/'); ?></span>
</div>