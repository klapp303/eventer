<?php echo $this->Html->css('events', array('inline' => false)); ?>
<h3>イベント詳細</h3>

  <table class="detail-list-min detail-list_event">
    <tr><th>イベント名</th>
        <th>会場</th></tr>
    <tr><td><?php echo $event_detail['Event']['title']; ?>
            <?php if ($event_detail['Event']['title'] != $event_detail['EventsDetail']['title']): ?><br>
            <span class="title-sub"><?php echo $event_detail['EventsDetail']['title']; ?></span>
            <?php endif; ?>
        </td>
        <td><?php if ($event_detail['EventsDetail']['place_id'] <= $PLACE_OTHER_KEY): ?>
            <?php echo $event_detail['Place']['name']; ?>
            <?php elseif ($event_detail['Place']['name']): ?>
            <?php echo $this->Html->link($event_detail['Place']['name'], '/places/place_detail/' . $event_detail['EventsDetail']['place_id']); ?>
            　<span class="txt-min">（<?php echo $event_detail['Place']['Prefecture']['name']; ?>）</span><br>
            <span class="access_search"><?php echo $this->element('access_search', array('data' => $event_detail)); ?></span>
            <?php endif; ?></td></tr>
  </table>

  <table class="detail-list detail-list_event">
    <tr><th class="tbl-date">開催日時</th>
        <th class="tbl-ico">status</th>
        <th class="tbl-ico-long">公開設定</th>
        <th>作成者</th>
        <th>別日程</th></tr>
    <tr><td class="tbl-date"><?php echo date('Y/m/d(' . $week_lists[date('w', strtotime($event_detail['EventsDetail']['date']))] . ')', strtotime($event_detail['EventsDetail']['date'])); ?><br>
                             開場　<?php echo ($event_detail['EventsDetail']['time_open'])? date('H:i', strtotime($event_detail['EventsDetail']['time_open'])) : ''; ?><br>
                             開演　<?php echo ($event_detail['EventsDetail']['time_start'])? date('H:i', strtotime($event_detail['EventsDetail']['time_start'])) : ''; ?></td>
        <td class="tbl-ico"><span class="icon-genre col-event_<?php echo $event_detail['EventsDetail']['genre_id']; ?>"><?php echo $event_detail['EventGenre']['title']; ?></span><br>
                            <?php if ($event_detail_status == 4): ?>
                            <span class="icon-false">見送り</span>
                            <?php else: ?>
                            <span class="icon-safe">登録中</span>
                            <?php endif; ?></td>
        <td class="tbl-ico-long"><?php if ($event_detail['Event']['publish'] == 0): ?>
                                 <span class="icon-false">非公開</span>
                                 <?php elseif ($event_detail['Event']['publish'] == 1): ?>
                                 <span class="icon-true">全体に公開</span>
                                 <?php elseif ($event_detail['Event']['publish'] == 2): ?>
                                 <span class="icon-true">参加者のみに公開</span>
                                 <?php endif; ?></td>
        <td><?php echo $event_detail['User']['handlename']; ?></td>
        <td><?php foreach ($other_lists as $other_list): ?>
            <?php echo $this->Html->link($other_list['EventsDetail']['title'], '/events/' . $other_list['EventsDetail']['id']); ?><br>
            <?php endforeach; ?></td></tr>
  </table>

<h3>エントリー一覧</h3>

  <table class="detail-list">
    <tr><th colspan="<?php echo count($entryDateColumn) +1; ?>">エントリー名</th>
        <th class="tbl-genre" rowspan="2">status</th>
        <th class="tbl-num" rowspan="2">価格<br>
                                        席数<br>
                                        枚数</th>
        <th rowspan="2">申込者</th>
        <?php // if ($event_detail['EventsDetail']['user_id'] == $userData['id']): ?>
        <th class="tbl-act" rowspan="2">action</th>
        <?php // endif; ?></tr>
    <tr><th></th>
        <?php foreach ($entryDateColumn as $key => $column): ?>
        <th class="tbl-date-min"><?php echo $key; ?></th>
        <?php endforeach; ?></tr>
    
    <?php foreach ($entry_lists as $entry_list): ?>
    <tr><td colspan="<?php echo count($entryDateColumn) +1; ?>"><?php echo $entry_list['EventsEntry']['title']; ?></td>
        <td class="tbl-genre" rowspan="2"><span class="icon-genre col-rule_<?php echo $entry_list['EntryGenre']['entry_rule_id']; ?>"><?php echo $entry_list['EntryGenre']['EntryRule']['title']; ?></span><br>
                                          <span class="icon-genre col-cost_<?php echo $entry_list['EntryGenre']['entry_cost_id']; ?>"><?php echo $entry_list['EntryGenre']['EntryCost']['title']; ?></span><br>
                                          <?php foreach ($eventEntryStatus as $entry_status) {
                                              if ($entry_status['status'] == $entry_list['EventsEntry']['status']) {
                                                  echo '<span class="icon-' . $entry_status['class'] . '">' . $entry_status['name'] . '</span>';
                                                  break;
                                              }
                                          } ?></td>
        <td class="tbl-num" rowspan="2"><?php echo $entry_list['EventsEntry']['price']; ?>円<br>
                                        <?php echo $entry_list['EventsEntry']['seat']; ?>席<br>
                                        <?php echo $entry_list['EventsEntry']['number']; ?>枚<br>
                                        <?php foreach ($eventPaymentStatus as $payment_status) {
                                            if ($payment_status['status'] == $entry_list['EventsEntry']['payment']) {
                                                echo '<span class="txt-min">' . $payment_status['name'] . '</span>';
                                                break;
                                            }
                                        } ?></td>
        <td rowspan="2"><span class="txt-min"><?php echo $entry_list['User']['handlename']; ?></span></td>
        <?php if ($entry_list['EventsEntry']['user_id'] == $userData['id']): ?>
        <td class="tbl-act" rowspan="2"><span class="icon-button"><?php echo $this->Html->link('修正', '/events/entry_edit/' . $entry_list['EventsEntry']['id']); ?></span>
                                        <span class="icon-button"><?php echo $this->Form->postLink('削除', array('action' => 'entry_delete', $entry_list['EventsEntry']['id'], $entry_list['EventsEntry']['events_detail_id']), null, $entry_list['EventsEntry']['title'] . ' を本当に削除しますか'); ?></span>
        <?php endif; ?></td></tr>
    <tr><td></td>
        <?php $status = 0; ?>
        <?php foreach ($entryDateColumn as $column): ?>
        <?php $status++; ?>
        <td class="tbl-date-min <?php echo ($entry_list['EventsEntry']['date_closed'] >= $status)? 'date-closed' : ''; ?>">
          <?php if ($entry_list['EventsEntry'][$column]): ?>
          <?php echo date('m/d(' . $week_lists[date('w', strtotime($entry_list['EventsEntry'][$column]))] . ')', strtotime($entry_list['EventsEntry'][$column])); ?><br>
          <?php echo date('H:i', strtotime($entry_list['EventsEntry'][$column])); ?>
          <?php endif; ?>
        </td>
        <?php endforeach; ?></tr>
    <?php endforeach; ?>
  </table>

<?php // if ($event_detail['EventsDetail']['user_id'] == $userData['id']): ?>
<div class="link-right">
  <span class="link-page"><?php echo $this->Html->link('⇨ 新しいエントリーの登録はこちら', '/events/entry_add/' . $event_detail['EventsDetail']['id']); ?></span>
</div>
<?php // endif; ?>

<?php if (empty($entry_lists)): ?>
<div class="link-right">
  <?php if ($event_detail_status == 4): ?>
  <span class="link-page"><?php echo $this->Form->postLink('⇨ 見送ったイベントを戻す', array('action' => 'event_skip', $event_detail['EventsDetail']['id']), null, 'イベントのstatusを戻しますか'); ?></span>
  <?php else: ?>
  <span class="link-page"><?php echo $this->Form->postLink('⇨ イベントを見送る', array('action' => 'event_skip', $event_detail['EventsDetail']['id']), null, 'イベントのstatusを見送りに変更しますか'); ?></span>
  <?php endif; ?>
</div>
<?php endif; ?>

<h3>出演者</h3>

  <table class="detail-list detail-list_event">
    <tr><td><div class="list-name-tag">
              <?php foreach ($cast_lists as $cast_list): ?>
              <span class="name-tag-long">
                <?php echo $this->Html->link($cast_list['ArtistProfile']['name'], '/artists/artist_detail/' . $cast_list['EventArtist']['artist_id']); ?>
              </span>
              <?php endforeach; ?>
            </div></td></tr>
  </table>

<?php if ($event_detail['EventsDetail']['user_id'] == $userData['id']): ?>
<div class="link-right">
  <span class="link-page"><?php echo $this->Html->link('⇨ 出演者の管理はこちら', '/events/cast/' . $event_detail['EventsDetail']['id']); ?></span>
</div>
<?php endif; ?>

<h3>セットリスト</h3>

  <table class="detail-list detail-list_event">
    <?php $i = 1; ?>
    <?php foreach ($setlist as $music): ?>
    <tr><td class="tbl-num"><?php echo sprintf('%02d', $i); ?>.</td>
        <td class="tbl-title_setlist"><?php echo $music['EventSetlist']['title']; ?></td>
        <td class="tbl-name_setlist"><?php echo $music['ArtistProfile']['name']; ?></td></tr>
    <?php $i++; ?>
    <?php endforeach; ?>
  </table>

<?php if ($event_detail['EventsDetail']['user_id'] == $userData['id']): ?>
<div class="link-right">
  <span class="link-page"><?php echo $this->Html->link('⇨ セットリストの管理はこちら', '/events/setlist/' . $event_detail['EventsDetail']['id']); ?></span>
</div>
<?php endif; ?>

<!--h3>参加者</h3>

  <table class="detail-list detail-list_event">
    <tr><td><div class="list-name-tag">
              <?php // $user_lists = $event_detail['UserList']; ?>
              <?php // foreach ($user_lists as $user_list): ?>
              <span class="name-tag-long"><?php // echo $user_list['UserProfile']['handlename']; ?></span>
              <?php // endforeach; ?>
            </div></td></tr>
  </table-->
