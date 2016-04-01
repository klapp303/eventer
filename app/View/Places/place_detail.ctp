<?php echo $this->Html->script('http://maps.googleapis.com/maps/api/js?sensor=false', array('inline' => FALSE)); ?>
<?php echo $this->Html->css('places', array('inline' => FALSE)); ?>
<h3>会場詳細</h3>

  <table class="detail-list">
    <tr><th>会場名</th>
        <th class="tbl-num">収容人数</th>
        <th>最寄り駅</th>
        <th>公式サイト</th></tr>
    <tr><td><?php echo $place_detail['Place']['name']; ?></td>
        <td class="tbl-num"><?php echo $place_detail['Place']['capacity']; ?><?php if ($place_detail['Place']['capacity']) {echo '人';} ?></td>
        <td><?php echo $place_detail['Place']['access']; ?><?php if ($place_detail['Place']['access']) {echo '駅';} ?></td>
        <td><?php if ($place_detail['Place']['url']) { ?>
            <?php echo $this->Html->link($place_detail['Place']['url'], $place_detail['Place']['url'], array('target' => '_blank')); ?>
            <?php } ?></td></tr>
  </table>

<div id="map" class="fr cf">
<?php //GoogleMapオプション
  $map_options = array(
      'latitude' => $place_detail['Place']['latitude'],
      'longitude' => $place_detail['Place']['longitude'],
      'windowText' => $place_detail['Place']['name']
  ); ?>
  <?php if ($place_detail['Place']['latitude']) { ?>
    <?php echo $this->GoogleMap->map($map_options); ?>
  <?php } ?>
</div>

<div id="event-list_place">
<h3>開催予定のイベント</h3>

  <table class="event-list_place">
    <tr><th class="tbl-date">開催日</th>
        <th>イベント名</th>
        <th class="tbl-act_place">action</th></tr>
    
    <?php foreach ($event_lists AS $event_list) { ?>
    <tr><td class="tbl-date"><?php echo date('Y/m/d('.$week_lists[date('w', strtotime($event_list['EventsDetail']['date']))].')', strtotime($event_list['EventsDetail']['date'])); ?></td>
        <td><?php echo $event_list['Event']['title']; ?>
            <?php if ($event_list['Event']['title'] != $event_list['EventsDetail']['title']) { ?><br>
            <span class="title-sub"><?php echo $event_list['EventsDetail']['title']; ?></span>
            <?php } ?>
        </td>
        <td class="tbl-act_place"><span class="icon-button"><?php echo $this->Html->link('詳細', '/event/'.$event_list['EventsDetail']['id'], array('target' => '_blank')); ?></span></td></tr>
    <?php } ?>
  </table>
</div>