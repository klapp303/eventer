<?php echo $this->Html->css('analysis', array('inline' => false)); ?>
<div class="intro">
  <p class="txt-min">
    ※各イベント参加数には当選した将来のイベントも含まれます。<br>
    　（）内は前年比。
  </p>
</div>

<h3><?php echo $artist; ?> イベント参加データ</h3>

  <table class="detail-list-min">
    <tr><td>イベント登録数</td>
        <td><?php echo $event_counts['event']; ?> 件</td></tr>
    <tr><td>イベント応募数</td>
        <td><?php echo $event_counts['entry']; ?> 件</td></tr>
    <tr><td>イベント参加数</td>
        <td><?php echo $event_counts['join']; ?> 件</td></tr>
    <tr><td>　　内ライブ</td>
        <td><?php echo $event_counts['live']; ?> 件</td>
        <td><?php echo $event_counts['percent']['live']; ?>%</td></tr>
    <tr><td>　　内リリイベ</td>
        <td><?php echo $event_counts['release']; ?> 件</td>
        <td><?php echo $event_counts['percent']['release']; ?>%</td></tr>
    <tr><td>　　内トーク</td>
        <td><?php echo $event_counts['talk']; ?> 件</td>
        <td><?php echo $event_counts['percent']['talk']; ?>%</td></tr>
  </table>

<h3>年別参加データ</h3>

  <table class="detail-list-min">
    <tr><th>年</th>
        <th class="tbl-count_analysis">登録数</th>
        <th class="tbl-count_analysis">応募数</th>
        <th class="tbl-count_analysis">参加数</th>
        <th class="tbl-count_analysis">内ライブ</th>
        <th class="tbl-count_analysis">内リリイベ</th>
        <th class="tbl-count_analysis">内トーク</th>
        <th class="tbl-act-min">action</th></tr>
    
    <?php
    
    ?>
    <?php foreach ($artist_event_counts as $year => $count): ?>
      <tr><td class="tbl-num"><?php echo $year; ?>年</td>
          <td class="tbl-count_analysis"><?php echo $count['event']; ?> 件</td>
          <td class="tbl-count_analysis"><?php echo $count['entry']; ?> 件</td>
          <td class="tbl-count_analysis"><?php echo $count['join']; ?> 件</td>
          <td class="tbl-count_analysis"><?php echo $count['live']; ?> 件</td>
          <td class="tbl-count_analysis"><?php echo $count['release']; ?> 件</td>
          <td class="tbl-count_analysis"><?php echo $count['talk']; ?> 件</td>
          <td class="tbl-act-min"><span class="icon-button"><?php echo $this->Html->link('詳細', '/analysis/index/' . $year); ?></span></td></tr>
    <?php endforeach; ?>
  </table>

<div class="intro">
  <p class="txt-min">
    ※各アーティストの名義が出演者にあるイベントのみ加算しています。<br>
    　ユニットやソロはそれぞれ別計算になります。<br>
    　出演者が複数のイベントもあるため、合計値が100%を超える場合もあります。
  </p>
</div>

<div class="link-right">
  <span class="link-page"><?php echo $this->Html->link('⇨ イベント参加データの分析TOPに戻る', '/analysis/'); ?></span>
</div>