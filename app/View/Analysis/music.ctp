<?php echo $this->Html->css('analysis', array('inline' => false)); ?>
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

<div class="link-right">
  <span class="link-page"><?php echo $this->Html->link('⇨ イベント参加データの分析TOPに戻る', '/analysis/'); ?></span>
</div>