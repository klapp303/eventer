<?php echo $this->Html->css('analysis', array('inline' => false)); ?>
<button type="button" class="update-button_analysis fr cf" onclick="analysis_lists_update()">データを更新する</button>
<script>
    function analysis_lists_update() {
        if (confirm('イベント参加データを更新しますか？') == true) {
            location.href = "/analysis/update/";
        }
    }
</script>

<div class="intro">
  <p class="txt-min">
    ※各イベント参加数には当選した将来のイベントも含まれます。
  </p>
</div>

<h3>全イベント参加データ</h3>

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

<h3>アーティスト別参加データ</h3>

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
    <?php foreach ($event_artist_lists as $events): ?>
      <?php
      //参加数が1以下のデータは除外する
      if ($events['analysis']['count'] <=1) {
          $display = false;
      //max以降の同列順位を表示するため
      } elseif ($i < $max) {
          $display = true;
      } elseif ($i == $max) {
          $display = true;
          $count_min = $events['analysis']['count'];
      } else {
          if ($events['analysis']['count'] == $count_min && $i <= $limit) {
              $display = true;
          //max以降の順位でもfavoriteは表示する
          } elseif (in_array($events['analysis']['artist']['id'], $favorite_lists)) {
              $display = true;
          } else {
              $display = false;
          }
      }
      ?>
      <?php if ($display): ?>
        <?php //同列順位を表示するため
        if ($events['analysis']['count'] != $count_pre) {
            $rank = $i;
        }
        ?>
      <tr><td class="tbl-num"><?php echo $rank; ?></td>
          <td><?php echo $events['analysis']['artist']['name']; ?></td>
          <td class="tbl-count_analysis"><?php echo $events['analysis']['count']; ?></td>
          <td class="tbl-count_analysis"><?php echo (round($events['analysis']['count'] / $event_counts['join'], 3) *100); ?> %</td>
          <td class="tbl-act-min"><span class="icon-button"><?php echo $this->Html->link('詳細', '/analysis/artist?artist=' . $events['analysis']['artist']['id']); ?></span></td></tr>
      <?php endif; ?>
    <?php
    $count_pre = $events['analysis']['count'];
    $i++;
    ?>
    <?php endforeach; ?>
  </table>

<div class="intro">
  <p class="txt-min">
    ※各アーティストの名義が出演者にあるイベントのみ加算しています。<br>
    　ユニットやソロはそれぞれ別計算になります。<br>
    　出演者が複数のイベントもあるため、合計値が100%を超える場合もあります。
  </p>
</div>

<h3>楽曲別参加データ</h3>

  <table class="detail-list-min">
    <tr><th></th>
        <th>楽曲</th>
        <th>アーティスト</th>
        <th class="tbl-count_analysis">回数</th></tr>
    
    <?php
    $i = 1;
    $rank = 1;
    $count_pre = false;
    $max = 20; //表示順位
    $limit = 30; //表示最大数
    ?>
    <?php foreach ($event_music_lists as $events): ?>
      <?php
      //参加数が1以下のデータは除外する
      if ($events['analysis']['count'] <=1) {
          $display = false;
      //max以降の同列順位を表示するため
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
        <?php //同列順位を表示するため
        if ($events['analysis']['count'] != $count_pre) {
            $rank = $i;
        }
        ?>
      <tr><td class="tbl-num"><?php echo $rank; ?></td>
          <td><?php echo $events['analysis']['music']['title']; ?></td>
          <td><?php echo $events['analysis']['music']['artist']; ?></td>
          <td class="tbl-count_analysis"><?php echo $events['analysis']['count']; ?></td></tr>
      <?php endif; ?>
    <?php
    $count_pre = $events['analysis']['count'];
    $i++;
    ?>
    <?php endforeach; ?>
  </table>

<h3>会場別参加データ</h3>

  <table class="detail-list-min">
    <tr><th></th>
        <th>会場</th>
        <th class="tbl-count_analysis">参加数</th>
        <th class="tbl-count_analysis">割合</th></tr>
    
    <?php
    $i = 1;
    $rank = 1;
    $count_pre = false;
    $max = 5; //表示順位
    $limit = 10; //表示最大数
    ?>
    <?php foreach ($event_place_lists as $events): ?>
      <?php
      //参加数が1以下のデータは除外する
      if ($events['analysis']['count'] <=1) {
          $display = false;
      //max以降の同列順位を表示するため
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
        <?php //同列順位を表示するため
        if ($events['analysis']['count'] != $count_pre) {
            $rank = $i;
        }
        ?>
      <tr><td class="tbl-num"><?php echo $rank; ?></td>
          <td><?php echo $events['analysis']['place']; ?></td>
          <td class="tbl-count_analysis"><?php echo $events['analysis']['count']; ?></td>
          <td class="tbl-count_analysis"><?php echo (round($events['analysis']['count'] / $event_counts['join'], 3) *100); ?> %</td></tr>
      <?php endif; ?>
    <?php
    $count_pre = $events['analysis']['count'];
    $i++;
    ?>
    <?php endforeach; ?>
  </table>

<div class="link-right">
  <span class="link-page"><?php echo $this->Html->link('⇨ イベント参加データの分析TOPに戻る', '/analysis/'); ?></span>
</div>