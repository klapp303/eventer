<?php // echo $this->Html->css('analysis', array('inline' => false)); ?>
<div class="intro">
  <p class="txt-min">※各イベント参加数には当選した将来のイベントも含まれます。</p>
  
  <table class="detail-list-min">
    <tr><td>イベント登録数</td><td><?php echo $count_event; ?> 件</td></tr>
    <tr><td>イベント応募数</td><td><?php echo $count_entry; ?> 件</td></tr>
    <tr><td>イベント参加数</td><td><?php echo $count_join; ?> 件</td></tr>
  </table>
</div>

<h3>年別イベント参加データ</h3>

  <table class="detail-list">
    <tr><th>年</th>
        <th>参加数</th></tr>
    
    <?php foreach ($event_year_lists as $year => $events): ?>
    <tr><td><?php echo $events['analysis']['year']; ?></td>
        <td><?php echo $events['analysis']['count']; ?></td></tr>
    <?php endforeach; ?>
  </table>

<h3>アーティスト別イベント参加データ</h3>

  <table class="detail-list">
    <tr><th></th>
        <th>アーティスト</th>
        <th>参加数</th></tr>
    
    <?php $i = 1; ?>
    <?php foreach ($event_artist_lists as $artist => $events): ?>
      <?php if ($i <= 10): ?>
      <tr><td><?php echo $i; ?></td>
          <td><?php echo $events['analysis']['artist']['name']; ?></td>
          <td><?php echo $events['analysis']['count']; ?></td></tr>
      <?php $i++; ?>
      <?php endif; ?>
    <?php endforeach; ?>
  </table>

<h3>会場別イベント参加データ</h3>

  <table class="detail-list">
    <tr><th></th>
        <th>会場</th>
        <th>参加数</th></tr>
    
    <?php $i = 1; ?>
    <?php foreach ($event_place_lists as $place => $events): ?>
      <?php if ($i <= 10): ?>
        <?php if (strpos($events['analysis']['place'], 'その他') === false): ?>
        <tr><td><?php echo $i; ?></td>
            <td><?php echo $events['analysis']['place']; ?></td>
            <td><?php echo $events['analysis']['count']; ?></td></tr>
        <?php $i++; ?>
        <?php endif; ?>
      <?php endif; ?>
    <?php endforeach; ?>
  </table>

<h3>楽曲別イベント参加データ</h3>

  <table class="detail-list">
    <tr><th></th>
        <th>楽曲</th>
        <th>アーティスト</th>
        <th>参加数</th></tr>
    
    <?php $i = 1; ?>
    <?php foreach ($event_music_lists as $music => $events): ?>
      <?php if ($i <= 10): ?>
      <tr><td><?php echo $i; ?></td>
          <td><?php echo $events['analysis']['music']['title']; ?></td>
          <td><?php echo $events['analysis']['music']['artist']; ?></td>
          <td><?php echo $events['analysis']['count']; ?></td></tr>
      <?php $i++; ?>
      <?php endif; ?>
    <?php endforeach; ?>
  </table>