<h3>会場詳細</h3>

  <table class="detail-list">
    <tr><th>会場名</th><th class="tbl-num">収容人数</th><th>アクセス</th><th>公式サイト</th></tr>
    <tr><td><?php echo $place_detail['Place']['name']; ?></td>
        <td class="tbl-num"><?php echo $place_detail['Place']['capacity']; ?>名</td>
        <td><?php echo $place_detail['Place']['access']; ?></td>
        <td><?php echo $this->Html->link($place_detail['Place']['url'], $place_detail['Place']['url'], array('target' => '_blank')); ?></td></tr>
  </table>

  <table class="detail-list">
    <tr><th>Google Map</th></tr>
    <tr><td class="tbl-map">表示できたらそれはとっても嬉しいなって</td></tr>
  </table>