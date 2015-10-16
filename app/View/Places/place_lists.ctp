<?php echo $this->Html->css('places', array('inline' => FALSE)); ?>
<div class="intro_places">
  <P>
    追加する際は、既に会場が登録されていないか確認してください。
  </P>
  
  <span class="link-page"><?php echo $this->Html->link('⇨ 会場の新規登録はこちら', '/places/add/'); ?></span>
</div>

<h3>会場一覧</h3>

  <?php echo $this->Paginator->numbers(array(
      'modulus' => 4, //現在ページから左右あわせてインクルードする個数
      'separator' => '|', //デフォルト値のセパレーター
      'first' => '＜', //先頭ページへのリンク
      'last' => '＞' //最終ページへのリンク
  )); ?>

  <table class="detail-list">
    <tr><th>会場名<?php echo $this->Paginator->sort('name', '▼'); ?></th>
        <th class="tbl-num">収容人数</th>
        <th>最寄り駅</th>
        <th class="tbl-action_places">action</th></tr>
    
    <?php foreach ($place_lists AS $place_list) { ?>
    <tr><td><?php echo $place_list['Place']['name']; ?></td>
        <td class="tbl-num"><?php echo $place_list['Place']['capacity']; ?>人</td>
        <td><?php echo $place_list['Place']['access']; ?>駅</td>
        <td class="tbl-action_places"><span class="icon-button"><?php echo $this->Html->link('詳細', '/places/place_detail/'.$place_list['Place']['id'], array('target' => '_blank')); ?></span>
          <span class="icon-button"><?php echo $this->Html->link('修正', '/places/edit/'.$place_list['Place']['id']); ?></span>
          <?php if ($place_list['Place']['id'] > $PLACE_BLOCK_KEY) { ?>
            <span class="icon-button"><?php echo $this->Form->postLink('削除', array('action' => 'delete', $place_list['Place']['id']), null, '本当に削除しますか'); ?></span>
        <?php } ?></td></tr>
    <?php } ?>
  </table>