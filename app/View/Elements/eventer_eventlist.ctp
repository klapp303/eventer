<?php if (!isset($paginator)) {
    $paginator = true;
} ?>
<?php if (!$event_lists): ?>
<div class="intro">
  <p>該当するイベントはありません。</p>
</div>

<?php else: ?>
<table class="detail-list event-list">
  <tr><th class="tbl-date">開催日<?php echo ($paginator)? $this->Paginator->sort('EventsDetail.date', '▼') : ''; ?></th>
      <th>イベント名<?php echo ($paginator)? $this->Paginator->sort('Event.title', '▼') : ''; ?></th>
      <th class="tbl-genre">status</th>
      <th class="tbl-act">action</th></tr>
  
  <?php foreach ($event_lists as $event_list): ?>
  <tr><td class="tbl-date"><?php echo date('Y/m/d(' . $week_lists[date('w', strtotime($event_list['EventsDetail']['date']))] . ')', strtotime($event_list['EventsDetail']['date'])); ?></td>
      <td><?php echo $event_list['Event']['title']; ?>
          <?php if ($event_list['Event']['title'] != $event_list['EventsDetail']['title']): ?><br>
          <span class="title-sub"><?php echo $event_list['EventsDetail']['title']; ?></span>
          <?php endif; ?>
      </td>
      <td class="tbl-genre"><span class="icon-genre col-event_<?php echo $event_list['EventsDetail']['genre_id']; ?>"><?php echo $event_list['EventGenre']['title']; ?></span><br>
                            <?php foreach ($eventEntryStatus as $entry_status) {
                                if ($entry_status['status'] == $event_list['EventsDetail']['status']) {
                                    echo '<span class="icon-' . $entry_status['class'] . '">' . $entry_status['name'] . '</span>';
                                    break;
                                }
                            } ?></td>
      <td class="tbl-act"><span class="icon-button"><?php echo $this->Html->link('詳細', '/events/' . $event_list['EventsDetail']['id'], array('target' => '_blank')); ?></span>
                          <?php if ($event_list['EventsDetail']['user_id'] == $userData['id']): ?>
                          <br><span class="icon-button"><?php echo $this->Html->link('修正', '/events/edit/' . $event_list['Event']['id']); ?></span>
                          <span class="icon-button"><?php echo $this->Form->postLink('削除', array('action' => 'delete', $event_list['EventsDetail']['id']), null,
                            ($event_list['Event']['title'] != $event_list['EventsDetail']['title'])? $event_list['Event']['title'] . ' の' . PHP_EOL . $event_list['EventsDetail']['title'] . ' を本当に削除しますか' : $event_list['EventsDetail']['title'] . ' を本当に削除しますか'); ?></span>
                          <?php endif; ?></td></tr>
  <?php endforeach; ?>
</table>
<?php endif; ?>