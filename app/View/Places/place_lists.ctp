<?php echo $this->Html->css('places', array('inline' => FALSE)); ?>
<h3>会場一覧</h3>

  <?php echo $this->Paginator->numbers(array(
      'modulus' => 4, //現在ページから左右あわせてインクルードする個数
      'separator' => '|', //デフォルト値のセパレーター
      'first' => '＜', //先頭ページへのリンク
      'last' => '＞' //最終ページへのリンク
  )); ?>

  <table class="tbl-list_places">
    <tr><th class="tbl-date_events">会場名<?php echo $this->Paginator->sort('name', '▼'); ?></th>
        <th class="tbl-num">キャパシティ</th>
        <th>アクセス</th>
        <th class="tbl-action_events">action</th></tr>
    
    <?php foreach ($place_lists AS $place_list) { ?>
    <tr><td><?php echo $place_list['Place']['name']; ?></td>
        <td class="tbl-num"><?php echo $place_list['Place']['capacity']; ?>名</td>
        <td><?php echo $place_list['Place']['access']; ?></td>
        <td class="tbl-action_events"><span class="icon-button"><?php echo $this->Html->link('詳細', '/places/place_detail/'.$place_list['Place']['id'], array('target' => '_blank')); ?></span>
            <span class="icon-button"><?php echo $this->Form->postLink('削除', array('action' => 'delete', $place_list['Place']['id']), null, '本当に削除しますか'); ?></span></td></tr>
    <?php } ?>
  </table>

<?php echo $this->Html->link('新規登録', '/places/add/'); ?>