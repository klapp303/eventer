<?php echo $this->Html->script('http://maps.googleapis.com/maps/api/js?sensor=false', array('inline' => false)); ?>
<?php echo $this->Html->script('jquery-image_drag', array('inline' => false)); ?>
<?php echo $this->Html->css('places', array('inline' => false)); ?>
<h3>会場詳細</h3>

  <table class="detail-list-min">
    <tr><th>会場名</th>
        <th class="tbl-num">収容人数</th>
        <th>最寄り駅</th></tr>
    <tr><td><?php echo $place_detail['Place']['name']; ?>　<span class="txt-min">（<?php echo $place_detail['Prefecture']['name']; ?>）</span></td>
        <td class="tbl-num"><?php echo $place_detail['Place']['capacity']; ?><?php echo ($place_detail['Place']['capacity'])? '人' : ''; ?></td>
        <td><?php echo $place_detail['Place']['access']; ?><?php echo ($place_detail['Place']['access'])? '駅' : ''; ?></td></tr>
    <tr><th colspan="3">公式サイト</th></tr>
    <tr><td colspan="3"><?php if ($place_detail['Place']['url']): ?>
                        <?php echo $this->Html->link($place_detail['Place']['url'], $place_detail['Place']['url'], array('target' => '_blank')); ?>
                        <?php else: ?>
                        <br>
                        <?php endif; ?></td></tr>
  </table>

<div class="cf">
  <?php if ($place_detail['Place']['latitude'] && $place_detail['Place']['longitude']): ?>
  <div id="map" class="fl">
  <?php //GoogleMapオプション
  $map_options = array(
      'latitude' => $place_detail['Place']['latitude'],
      'longitude' => $place_detail['Place']['longitude'],
      'windowText' => $place_detail['Place']['name']
  ); ?>
    <?php echo $this->GoogleMap->map($map_options); ?>
  </div>
  <?php endif; ?>
  
  <?php if ($place_detail['Place']['seat_name']): ?>
  <div id="seat" class="fr">
  <script> //ImageDragオプション
      jQuery(function($) {
          imageDrag('#seat', '<?php echo $place_detail['Place']['seat_name']; ?>');
      });
  </script>
  </div>
  <div class="link-page_place-seat">
    <span class="link-page"><?php echo $this->Html->link('⇨ 座席画像を確認する', '/files/place/' . $place_detail['Place']['seat_name'], array('target' => 'blank')); ?></span>
  </div>
  <?php endif; ?>
</div>

<h3>開催予定のイベント</h3>

  <?php echo $this->element('eventer_eventlist', array('paginator' => false)); ?>

<div class="link-right">
  <span class="link-page"><?php echo $this->Html->link('⇨ すべてのイベント一覧を確認する', '/places/event_lists/' . $place_detail['Place']['id']); ?></span>
</div>