<?php echo $this->Html->script('http://maps.googleapis.com/maps/api/js?sensor=false', array('inline' => FALSE)); ?>
<?php echo $this->Html->css('places', array('inline' => FALSE)); ?>
<h3>会場詳細</h3>

  <table class="detail-list">
    <tr><th>会場名</th><th class="tbl-num">収容人数</th><th>最寄り駅</th><th>公式サイト</th></tr>
    <tr><td><?php echo $place_detail['Place']['name']; ?></td>
        <td class="tbl-num"><?php echo $place_detail['Place']['capacity']; ?>名</td>
        <td><?php echo $place_detail['Place']['access']; ?>駅</td>
        <td><?php echo $this->Html->link($place_detail['Place']['url'], $place_detail['Place']['url'], array('target' => '_blank')); ?></td></tr>
  </table>

<?php
  //GoogleMapオプション
  $map_options = array(
      'latitude' => $place_detail['Place']['latitude'],
      'longitude' => $place_detail['Place']['longitude'],
      'windowText' => $place_detail['Place']['name']
  );
?>
<div id="map" class="fr cf">
  <?php if ($place_detail['Place']['latitude']) { ?>
    <?php echo $this->GoogleMap->map($map_options); ?>
  <?php } ?>
</div>

<div id="event-list_place">
<h3>開催予定のイベント</h3>

  <?php echo $this->Paginator->numbers(array(
      'modulus' => 4, //現在ページから左右あわせてインクルードする個数
      'separator' => '|', //デフォルト値のセパレーター
      'first' => '＜', //先頭ページへのリンク
      'last' => '＞' //最終ページへのリンク
  )); ?>

  <table class="event-list_place">
    <tr><th class="tbl-date">開催日<?php echo $this->Paginator->sort('date', '▼'); ?></th>
        <th>イベント名<?php echo $this->Paginator->sort('title', '▼'); ?></th>
        <th class="tbl-action_place">action</th></tr>
    
    <?php foreach ($event_lists AS $event_list) { ?>
    <tr><td class="tbl-date"><?php echo $event_list['Event']['date']; ?></td>
        <td><?php echo $event_list['Event']['title']; ?></td>
        <td class="tbl-action_place"><span class="icon-button"><?php echo $this->Html->link('詳細', '/event/'.$event_list['Event']['id']); ?></span></td></tr>
    <?php } ?>
  </table>
</div>