<?php echo $this->Html->css('artists', array('inline' => false)); ?>
<div class="intro intro_compare">
  <p>
    イベントによく参加しているアーティストの、イベント参加データ一覧です。<br>
    イベント申込数が、<?php echo $ARTIST_COMPARE_KEY; ?>件以上のアーティストのみ表示しています。
  </p>
</div>

<h3>イベント参加データ一覧</h3>
  <table class="detail-list">
    <?php $paginator_url = '/' . $this->params['controller'] . '/' . $this->params['action'] . '/'; ?>
    <tr><th class="tbl-artist_compare">アーティスト名<a href="<?php echo $paginator_url; ?>sort:kana/direction:asc">▼</a></th>
        <th>参加数<a href="<?php echo $paginator_url; ?>sort:count_join/direction:desc">▼</a></th>
        <th>申込数<a href="<?php echo $paginator_url; ?>sort:count_entry/direction:desc">▼</a></th>
        <th>当選率<a href="<?php echo $paginator_url; ?>sort:per_win/direction:desc">▼</a></th>
        <th>頻度<a href="<?php echo $paginator_url; ?>sort:span_rating/direction:asc">▼</a></th>
        <th>直近頻度<a href="<?php echo $paginator_url; ?>sort:span_tenth/direction:asc">▼</a></th>
        <th>前回<a href="<?php echo $paginator_url; ?>sort:span_current/direction:asc">▼</a></th></tr>
    
    <?php foreach ($event_reports as $event_report): ?>
    <tr><td class="tbl-artist_compare"><a href="/artists/artist_detail/<?php echo $event_report['id']; ?>" target="_blank"><?php echo $event_report['name']; ?></a></td>
        <td><?php echo $event_report['count_join']; ?> 件</td>
        <td><?php echo $event_report['count_entry']; ?> 件</td>
        <td><?php echo $event_report['per_win']; ?> %</td>
        <td><?php echo $event_report['span_rating']; ?> days</td>
        <td><?php echo $event_report['span_tenth']; ?> days</td>
        <td><?php echo $event_report['span_current']; ?> days</td></tr>
    <?php endforeach; ?>
  </table>

<div class="link-right">
  <span class="link-page"><?php echo $this->Html->link('⇨ アーティストの一覧に戻る', '/artists/artist_lists/'); ?></span>
</div>