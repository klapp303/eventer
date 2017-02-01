<?php echo $this->Html->css('events', array('inline' => false)); ?>
<?php echo $this->element('searchbox', array(
    'action' => $this->action,
    'placeholder' => 'イベント名 を入力'
)); ?>
<h3><?php echo $sub_page; ?></h3>

<?php if (@$description) { ?>
  <div class="intro_events">
    <P><?php echo $description; ?></P>
  </div>
<?php } ?>

  <?php if (count($event_lists) > 0) { ?>
    <?php echo $this->Paginator->numbers($paginator_option); ?>
    
    <table class="detail-list event-list">
      <tr><th class="tbl-date">開催日<?php echo $this->Paginator->sort('EventsDetail.date', '▼'); ?></th>
          <th>イベント名<?php echo $this->Paginator->sort('Event.title', '▼'); ?></th>
          <th class="tbl-genre">種類<br>
                                作成者</th>
          <th class="tbl-act">action</th></tr>
      
      <?php foreach ($event_lists as $event_list) { ?>
        <tr><td class="tbl-date"><?php echo date('Y/m/d(' . $week_lists[date('w', strtotime($event_list['EventsDetail']['date']))] . ')', strtotime($event_list['EventsDetail']['date'])); ?></td>
            <td><?php echo $event_list['Event']['title']; ?>
                <?php if ($event_list['Event']['title'] != $event_list['EventsDetail']['title']) { ?><br>
                  <span class="title-sub"><?php echo $event_list['EventsDetail']['title']; ?></span>
                <?php } ?>
            </td>
            <td class="tbl-genre"><span class="icon-genre col-event_<?php echo $event_list['EventsDetail']['genre_id']; ?>"><?php echo $event_list['EventGenre']['title']; ?></span><br>
                                  <span class="name-min_events"><?php echo $event_list['User']['handlename']; ?></span></td>
            <td class="tbl-act"><span class="icon-button"><?php echo $this->Html->link('詳細', '/events/' . $event_list['EventsDetail']['id'], array('target' => '_blank')); ?></span>
                                <?php if ($event_list['EventsDetail']['user_id'] == $userData['id']) { ?>
                                  <br><span class="icon-button"><?php echo $this->Html->link('修正', '/events/edit/' . $event_list['Event']['id']); ?></span>
                                  <span class="icon-button"><?php echo $this->Form->postLink('削除', array('action' => 'all_lists_delete', $event_list['EventsDetail']['id']), null, ($event_list['Event']['title'] != $event_list['EventsDetail']['title'])? $event_list['Event']['title'] . ' の
' . $event_list['EventsDetail']['title'] . ' を本当に削除しますか' : $event_list['EventsDetail']['title'] . ' を本当に削除しますか'); ?></span>
                                <?php } ?></td></tr>
      <?php } ?>
    </table>
    
    <?php echo $this->Paginator->numbers($paginator_option); ?>
  <?php } else { ?>
    <div class="intro_events">
      <P>
        該当するイベントはありません。
      </P>
    </div>
  <?php } ?>

<?php if (@$page_link) { ?>
  <div class="link-page_events">
    <?php foreach ($page_link as $val) { ?>
      <span class="link-page"><?php echo $this->Html->link('⇨ ' . $val['title'], $val['url']); ?></span>
    <?php } ?>
  </div>
<?php } ?>