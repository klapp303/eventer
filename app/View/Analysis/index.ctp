<?php echo $this->Html->css('analysis', array('inline' => false)); ?>
<?php if (@!$mode): ?>
<button type="button" class="update-button_analysis fr cf" onclick="analysis_lists_update()">データを更新する</button>
<script>
    function analysis_lists_update() {
        if (confirm('イベント参加データを更新しますか？') == true) {
            location.href = "/analysis/update/";
        }
    }
</script>
<?php endif; ?>

<div class="intro">
  <p class="txt-min">※各イベント参加数には当選した将来のイベントも含まれます。</p>
</div>

<table class="detail-list-min">
  <tr><td>イベント登録数</td><td><?php echo $event_counts['event']; ?> 件</td></tr>
  <tr><td>イベント応募数</td><td><?php echo $event_counts['entry']; ?> 件</td></tr>
  <tr><td>イベント参加数</td><td><?php echo $event_counts['join']; ?> 件</td></tr>
</table>

<?php if (@$mode != 'year'): ?>
<h3>年別イベント参加データ</h3>

  <table class="detail-list-min">
    <tr><th>年</th>
        <th class="tbl-count_analysis">参加数</th>
        <th class="tbl-act-min">action</th></tr>
    
    <?php foreach ($event_year_lists as $year => $events): ?>
    <tr><td><?php echo $events['analysis']['year']; ?></td>
        <td class="tbl-count_analysis"><?php echo $events['analysis']['count']; ?></td>
        <td class="tbl-act-min"><span class="icon-button"><?php echo $this->Html->link('詳細', '/analysis/detail?year=' . str_replace('年', '', $events['analysis']['year'])); ?></span></td></tr>
    <?php endforeach; ?>
  </table>
<?php endif; ?>

<?php if (@$mode != 'artist'): ?>
<h3>アーティスト別イベント参加データ</h3>

  <table class="detail-list-min">
    <tr><th></th>
        <th>アーティスト</th>
        <th class="tbl-count_analysis">参加数</th>
        <th class="tbl-count_analysis">割合</th>
        <th class="tbl-act-min">action</th></tr>
    
    <?php
    $i = 1;
    $rank = 1;
    $count_pre = false;
    $max = 5; //表示順位
    $limit = 10; //表示最大数
    ?>
    <?php foreach ($event_artist_lists as $artist => $events): ?>
      <?php //max以降の同列順位を表記するため
      if ($i < $max) {
          $display = true;
      } elseif ($i == $max) {
          $display = true;
          $count_min = $events['analysis']['count'];
      } else {
          if ($events['analysis']['count'] == $count_min && $i <= $limit) {
              $display = true;
          } else {
              $display = false;
          }
      }
      ?>
      <?php if ($display): ?>
        <?php //同列順位を表記するため
        if ($events['analysis']['count'] != $count_pre) {
            $rank = $i;
        }
        ?>
      <tr><td class="tbl-num"><?php echo $rank; ?></td>
          <td><?php echo $events['analysis']['artist']['name']; ?></td>
          <td class="tbl-count_analysis"><?php echo $events['analysis']['count']; ?></td>
          <td class="tbl-count_analysis"><?php echo (round($events['analysis']['count'] / $event_counts['join'], 3) *100); ?> %</td>
          <td class="tbl-act-min"><span class="icon-button"><?php echo $this->Html->link('詳細', '/analysis/detail?artist=' . $events['analysis']['artist']['id']); ?></span></td></tr>
      <?php endif; ?>
    <?php
    $count_pre = $events['analysis']['count'];
    $i++;
    ?>
    <?php endforeach; ?>
  </table>
<?php endif; ?>

<?php if (@$mode != 'place'): ?>
<h3>会場別イベント参加データ</h3>

  <table class="detail-list-min">
    <tr><th></th>
        <th>会場</th>
        <th class="tbl-count_analysis">参加数</th>
        <th class="tbl-count_analysis">割合</th>
        <th class="tbl-act-min">action</th></tr>
    
    <?php
    $i = 1;
    $rank = 1;
    $count_pre = false;
    $max = 5; //表示順位
    $limit = 10; //表示最大数
    ?>
    <?php foreach ($event_place_lists as $place => $events): ?>
      <?php //max以降の同列順位を表記するため
      if (strpos($events['analysis']['place'], 'その他') !== false) {
          $display = false;
      } elseif ($i < $max) {
          $display = true;
      } elseif ($i == $max) {
          $display = true;
          $count_min = $events['analysis']['count'];
      } else {
          if ($events['analysis']['count'] == $count_min && $i <= $limit) {
              $display = true;
          } else {
              $display = false;
          }
      }
      ?>
      <?php if ($display): ?>
        <?php //同列順位を表記するため
        if ($events['analysis']['count'] != $count_pre) {
            $rank = $i;
        }
        ?>
      <tr><td class="tbl-num"><?php echo $rank; ?></td>
          <td><?php echo $events['analysis']['place']; ?></td>
          <td class="tbl-count_analysis"><?php echo $events['analysis']['count']; ?></td>
          <td class="tbl-count_analysis"><?php echo (round($events['analysis']['count'] / $event_counts['join'], 3) *100); ?> %</td>
          <td class="tbl-act-min"><span class="icon-button"><?php echo $this->Html->link('詳細', '/analysis/detail?place=' . $events['analysis']['place']); ?></span></td></tr>
      <?php endif; ?>
    <?php
    if (strpos($events['analysis']['place'], 'その他') === false) {
        $count_pre = $events['analysis']['count'];
        $i++;
    }
    ?>
    <?php endforeach; ?>
  </table>
<?php endif; ?>

<h3>楽曲別イベント参加データ</h3>

  <table class="detail-list">
    <tr><th></th>
        <th>楽曲</th>
        <th>アーティスト</th>
        <th class="tbl-count_analysis">参加数</th>
        <th class="tbl-count_analysis">割合</th></tr>
    
    <?php
    $i = 1;
    $rank = 1;
    $count_pre = false;
    $max = 5; //表示順位
    $limit = 10; //表示最大数
    ?>
    <?php foreach ($event_music_lists as $music => $events): ?>
      <?php //max以降の同列順位を表記するため
      if ($i < $max) {
          $display = true;
      } elseif ($i == $max) {
          $display = true;
          $count_min = $events['analysis']['count'];
      } else {
          if ($events['analysis']['count'] == $count_min && $i <= $limit) {
              $display = true;
          } else {
              $display = false;
          }
      }
      ?>
      <?php if ($display): ?>
        <?php //同列順位を表記するため
        if ($events['analysis']['count'] != $count_pre) {
            $rank = $i;
        }
        ?>
      <tr><td class="tbl-num"><?php echo $rank; ?></td>
          <td><?php echo $events['analysis']['music']['title']; ?></td>
          <td><?php echo $events['analysis']['music']['artist']; ?></td>
          <td class="tbl-count_analysis"><?php echo $events['analysis']['count']; ?></td>
          <td class="tbl-count_analysis"><?php echo (round($events['analysis']['count'] / $event_counts['join'], 3) *100); ?> %</td></tr>
      <?php endif; ?>
    <?php
    $count_pre = $events['analysis']['count'];
    $i++;
    ?>
    <?php endforeach; ?>
  </table>

<?php if (@$mode): ?>
<div class="link-right">
  <span class="link-page"><?php echo $this->Html->link('⇨ イベント参加データの分析一覧に戻る', '/analysis/'); ?></span>
</div>
<?php endif; ?>