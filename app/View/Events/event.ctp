<?php echo $this->Html->css('events', array('inline' => FALSE)); ?>
<h3>イベント詳細</h3>

  <table class="detail-list-min detail-list_event">
    <tr><th>イベント名</th>
        <th>会場</th></tr>
    <tr><td><?php echo $event_detail['Event']['title']; ?>
            <?php if ($event_detail['Event']['title'] != $event_detail['EventsDetail']['title']) { ?><br>
            <span class="title-sub"><?php echo $event_detail['EventsDetail']['title']; ?></span>
            <?php } ?>
        </td>
        <td><?php if ($event_detail['EventsDetail']['place_id'] < 2) { ?>
              <?php echo 'その他'; ?>
            <?php } elseif ($event_detail['Place']['name']) { ?>
              <?php echo $this->Html->link($event_detail['Place']['name'], '/places/place_detail/'.$event_detail['EventsDetail']['place_id']); ?><br>
              <span class="access_search"><?php echo $this->element('access_search', array('data' => $event_detail)); ?></span>
            <?php } ?></td></tr>
  </table>

  <table class="detail-list detail-list_event">
    <tr><th class="tbl-date">開催日時</th>
        <th class="tbl-ico">種類</th>
        <th class="tbl-ico-long">公開設定</th>
        <th>作成者</th>
        <th>別日程</th></tr>
    <tr><td class="tbl-date"><?php echo date('Y/m/d('.$week_lists[date('w', strtotime($event_detail['EventsDetail']['date']))].')', strtotime($event_detail['EventsDetail']['date'])); ?><br>
                             開場　<?php echo ($event_detail['EventsDetail']['time_open'])? date('H:i', strtotime($event_detail['EventsDetail']['time_open'])): ''; ?><br>
                             開演　<?php echo ($event_detail['EventsDetail']['time_start'])? date('H:i', strtotime($event_detail['EventsDetail']['time_start'])): ''; ?></td>
        <td class="tbl-ico"><span class="icon-genre col-event_<?php echo $event_detail['EventsDetail']['genre_id']; ?>"><?php echo $event_detail['EventGenre']['title']; ?></span></td>
        <td class="tbl-ico-long"><?php if ($event_detail['Event']['publish'] == 0) {echo '<span class="icon-false">非公開</span>';}
                                   elseif ($event_detail['Event']['publish'] == 1) {echo '<span class="icon-true">全体に公開</span>';}
                                   elseif ($event_detail['Event']['publish'] == 2) {echo '<span class="icon-true">参加者のみに公開</span>';} ?></td>
        <td><?php echo $event_detail['User']['handlename']; ?></td>
        <td><?php foreach ($other_lists AS $other_list) { ?>
            <?php echo $this->Html->link($other_list['EventsDetail']['title'], '/event/'.$other_list['EventsDetail']['id']); ?><br>
            <?php } ?></td></tr>
  </table>

<h3>エントリー一覧</h3>

  <table class="detail-list">
    <tr><th colspan="<?php echo count($entryDateColumn)+1; ?>">エントリー名</th>
        <th class="tbl-genre" rowspan="2">種類<br>
                                          状態</th>
        <th class="tbl-num" rowspan="2">価格<br>
                                        枚数</th>
        <?php if ($event_detail['EventsDetail']['user_id'] == $userData['id']) { ?>
        <th class="tbl-act" rowspan="2">action</th>
        <?php } ?></tr>
    <tr><th></th>
        <?php foreach ($entryDateColumn AS $key => $column) { ?>
        <th class="tbl-date-min"><?php echo $key; ?></th>
        <?php } ?></tr>
    
    <?php foreach ($entry_lists AS $entry_list) { ?>
    <tr><td colspan="<?php echo count($entryDateColumn)+1; ?>"><?php echo $entry_list['EventsEntry']['title']; ?></td>
        <td class="tbl-genre" rowspan="2"><span class="icon-genre col-entry_<?php echo $entry_list['EventsEntry']['entries_genre_id']; ?>"><?php echo $entry_list['EntryGenre']['title']; ?></span><br>
                                          <?php if ($entry_list['EventsEntry']['status'] == 0) {echo '<span class="icon-like">検討中</span>';}
                                            elseif ($entry_list['EventsEntry']['status'] == 1) {echo '<span class="icon-like">申込中</span>';}
                                            elseif ($entry_list['EventsEntry']['status'] == 2) {echo '<span class="icon-true">当選</span>';}
                                            elseif ($entry_list['EventsEntry']['status'] == 3) {echo '<span class="icon-false">落選</span>';}
                                            elseif ($entry_list['EventsEntry']['status'] == 4) {echo '<span class="icon-false">見送り</span>';} ?></td>
        <td class="tbl-num" rowspan="2"><?php echo $entry_list['EventsEntry']['price']; ?>円<br>
                                        <?php echo $entry_list['EventsEntry']['number']; ?>枚<br>
                                        <?php if ($entry_list['EventsEntry']['payment'] == 'credit') {echo '<span class="txt-min">クレジットカード</span>';}
                                          elseif ($entry_list['EventsEntry']['payment'] == 'conveni') {echo '<span class="txt-min">コンビニ支払</span>';}
                                          elseif ($entry_list['EventsEntry']['payment'] == 'delivery') {echo '<span class="txt-min">代金引換</span>';}
                                          elseif ($entry_list['EventsEntry']['payment'] == 'buy') {echo '<span class="txt-min">買取</span>';}
                                          elseif ($entry_list['EventsEntry']['payment'] == 'other') {echo '<span class="txt-min">その他</span>';} ?></td>
        <?php if ($entry_list['EventsEntry']['user_id'] == $userData['id']) { ?>
        <td class="tbl-act" rowspan="2"><span class="icon-button"><?php echo $this->Html->link('修正', '/events/entry_edit/'.$entry_list['EventsEntry']['id']); ?></span>
                                        <span class="icon-button"><?php echo $this->Form->postLink('削除', array('action' => 'entry_delete', $entry_list['EventsEntry']['id'], $entry_list['EventsEntry']['events_detail_id']), null, $entry_list['EventsEntry']['title'].' を本当に削除しますか'); ?></span>
        <?php } ?></td></tr>
    <tr><td></td>
        <?php $status = 0; ?>
        <?php foreach ($entryDateColumn AS $column) { ?>
        <?php $status++; ?>
        <td class="tbl-date-min <?php echo ($entry_list['EventsEntry']['date_closed'] >= $status)? 'date-closed': ''; ?>">
          <?php if ($entry_list['EventsEntry'][$column]) { ?>
            <?php echo date('m/d('.$week_lists[date('w', strtotime($entry_list['EventsEntry'][$column]))].')', strtotime($entry_list['EventsEntry'][$column])); ?><br>
            <?php echo date('H:i', strtotime($entry_list['EventsEntry'][$column])); ?>
          <?php } ?>
        </td>
        <?php } ?></tr>
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