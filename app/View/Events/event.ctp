<?php echo $this->Html->css('events', array('inline' => FALSE)); ?>
<span class="fr cf"><?php echo $this->element('access_search', array('data' => $event_detail)); ?></span>

<h3>イベント詳細</h3>

  <table class="detail-list detail-list_event">
    <tr><th>イベント名</th><th>会場</th><th class="tbl-date-long">開催日時</th></tr>
    <tr><td><?php echo $event_detail['EventsDetail']['title']; ?></td>
        <td><?php if ($event_detail['EventsDetail']['place_id'] < 2) { ?>
              <?php echo 'その他'; ?>
            <?php } elseif ($event_detail['Place']['name']) { ?>
              <?php echo $this->Html->link($event_detail['Place']['name'], '/places/place_detail/'.$event_detail['EventsDetail']['place_id']); ?>
            <?php } ?></td>
        <td class="tbl-date-long"><?php echo $event_detail['EventsDetail']['date']; ?><br>
                                  開場　<?php echo $event_detail['EventsDetail']['time_open']; ?><br>
                                  開演　<?php echo $event_detail['EventsDetail']['time_start']; ?></td></tr>
  </table>
  
  <table class="detail-list detail-list_event">
    <tr><th class="tbl-ico">種類</th><th class="tbl-ico-long">公開設定</th><th>作成者</th></tr>
    <tr><td class="tbl-ico"><span class="icon-genre col-event_<?php echo $event_detail['EventsDetail']['genre_id']; ?>"><?php echo $event_detail['EventGenre']['title']; ?></span></td>
        <td class="tbl-ico-long"><?php if ($event_detail['Event']['publish'] == 0) {echo '<span class="icon-false">非公開</span>';}
                                   elseif ($event_detail['Event']['publish'] == 1) {echo '<span class="icon-true">参加者のみ</span>';}
                                   elseif ($event_detail['Event']['publish'] == 2) {echo '<span class="icon-true">全てのユーザ</span>';} ?></td>
        <td class="tbl-date-long"><?php echo $event_detail['User']['handlename']; ?></td></tr>
  </table>

<h3>エントリー一覧</h3>

  <table class="detail-list">
    <tr><th class="tbl-date">申込開始日</th>
        <th>エントリー名</th>
        <th class="tbl-ico">種類<br>
                            状態</th>
        <th class="tbl-num">価格<br>
                            枚数</th>
        <?php if ($event_detail['EventsDetail']['user_id'] == $userData['id']) { ?>
        <th class="tbl-action">action</th>
        <?php } ?></tr>
    
    <?php foreach ($entry_lists AS $entry_list) { ?>
    <tr><td class="tbl-date"><?php echo $entry_list['EventsEntry']['date_start']; ?></td>
        <td><?php echo $entry_list['EventsEntry']['title']; ?></td>
        <td class="tbl-ico"><?php echo $entry_list['EntryGenre']['title']; ?><br>
                            <?php if ($entry_list['EventsEntry']['status'] == 0) {echo '検討中';}
                              elseif ($entry_list['EventsEntry']['status'] == 1) {echo '申込中';}
                              elseif ($entry_list['EventsEntry']['status'] == 2) {echo '当選';}
                              elseif ($entry_list['EventsEntry']['status'] == 3) {echo '落選';}
                              elseif ($entry_list['EventsEntry']['status'] == 4) {echo '見送り';} ?></td>
        <td class="tbl-num"><?php echo $entry_list['EventsEntry']['price']; ?>円<br>
                            <?php echo $entry_list['EventsEntry']['number']; ?>枚</td>
        <?php if ($entry_list['EventsEntry']['user_id'] == $userData['id']) { ?>
        <td class="tbl-act"><span class="icon-button"><?php echo $this->Html->link('修正', '/events/entry_edit/'.$entry_list['EventsEntry']['id']); ?></span>
                            <span class="icon-button"><?php echo $this->Form->postLink('削除', array('action' => 'entry_delete', $entry_list['EventsEntry']['id'], $entry_list['EventsEntry']['events_detail_id']), null, $entry_list['EventsEntry']['title'].' を本当に削除しますか'); ?></span>
        <?php } ?></td></tr>
    <?php } ?>
  </table>

<div class="link-page_events">
  <span class="link-page"><?php echo $this->Html->link('⇨ 新しいエントリーの登録はこちら', '/events/entry_add/'.$event_detail['EventsDetail']['id']); ?></span>
</div>

<!--h3>参加者</h3>

  <table class="detail-list detail-list_event">
    <tr><th>参加者</th></tr>
    <tr><td><?php /*$user_lists = $event_detail['UserList']; ?>
            <?php foreach ($user_lists AS $user_list) { ?>
            <span class="tbl-user_long"><?php echo $user_list['UserProfile']['handlename']; ?></span>
            <?php } */?></td></tr>
  </table-->