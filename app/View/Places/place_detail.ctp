<?php echo $this->Html->script('http://maps.googleapis.com/maps/api/js?sensor=false', array('inline' => FALSE)); ?>
<?php echo $this->Html->css('places', array('inline' => FALSE)); ?>
<h3>会場詳細</h3>

  <table class="detail-list">
    <tr><th>会場名</th><th class="tbl-num">収容人数</th><th>アクセス</th><th>公式サイト</th></tr>
    <tr><td><?php echo $place_detail['Place']['name']; ?></td>
        <td class="tbl-num"><?php echo $place_detail['Place']['capacity']; ?>名</td>
        <td><?php echo $place_detail['Place']['access']; ?></td>
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
  <table class="detail-list">
    <tr><th>Google Map</th></tr>
    <tr><td id="map">
      <?php if ($place_detail['Place']['latitude']) { ?>
        <?php echo $this->GoogleMap->map($map_options); ?>
      <?php } else { ?>
        登録されていません
      <?php } ?>
    </td></tr>
  </table>