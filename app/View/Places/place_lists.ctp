<?php echo $this->Html->css('places', array('inline' => false)); ?>
<?php echo $this->element('searchbox', array(
    'controller' => 'places',
    'value' => @$search_word,
    'placeholder' => '会場名 or 最寄り駅 を入力'
)); ?>

<div class="intro intro_places">
  <p>
    追加する際は、既に会場が登録されていないか確認してください。<br>
  </p>
  
  <span class="link-page"><?php echo $this->Html->link('⇨ 会場の新規登録はこちら', '/places/add/'); ?></span>
  <?php if ($userData['role'] <= 3): ?>
  <span class="link-page"><?php echo $this->Html->link('⇨ 会場の並び替えはこちら', '/places/sort/'); ?></span>
  <?php endif; ?>
</div>

<h3>会場一覧</h3>

  <?php echo $this->Paginator->numbers($paginator_option); ?>

  <table class="detail-list">
    <tr><th>会場名<?php echo $this->Paginator->sort('name', '▼'); ?></th>
        <th class="tbl-num_place">収容人数<?php echo $this->Paginator->sort('capacity', '▼'); ?></th>
        <th>最寄り駅</th>
        <th class="tbl-act">action</th></tr>
    
    <?php foreach ($place_lists as $place_list): ?>
    <tr><td><?php echo $place_list['Place']['name']; ?><span class="txt-min">　（<?php echo $place_list['Prefecture']['name']; ?>）</span></td>
        <td class="tbl-num_place"><?php echo $place_list['Place']['capacity']; ?><?php echo ($place_list['Place']['capacity'])? '人' : ''; ?></td>
        <td><?php echo $place_list['Place']['access']; ?><?php echo ($place_list['Place']['access'])? '駅' : ''; ?></td>
        <td class="tbl-act"><span class="icon-button"><?php echo $this->Html->link('詳細', '/places/place_detail/' . $place_list['Place']['id'], array('target' => '_blank')); ?></span>
          <?php if ($userData['role'] >= 3): ?>
          <span class="icon-button"><?php echo $this->Html->link('修正', '/places/edit/' . $place_list['Place']['id']); ?></span>
          <?php endif; ?>
          <?php if ($place_list['Place']['id'] > $PLACE_BLOCK_KEY): ?>
          <div class="delete_places"><span class="icon-button"><?php echo $this->Form->postLink('削除', array('action' => 'delete', $place_list['Place']['id']), null, $place_list['Place']['name'] . ' を本当に削除しますか'); ?></span></div>
          <?php endif; ?></td></tr>
    <?php endforeach; ?>
  </table>