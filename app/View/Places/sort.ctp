<?php echo $this->Html->css('places', array('inline' => FALSE)); ?>
<h3>会場の並び替え</h3>

  <table class="PlaceAddForm">
    <?php echo $this->Form->create('Place', array( //使用するModel
        'type' => 'post', //デフォルトはpost送信
        'action' => 'sort', //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
        )
    ); ?><!-- form start -->
    <tr><td><span class="txt-min">1 から <?php echo count($place_lists); ?> までの数値で指定してください。</span></td></tr>
    <?php foreach ($place_lists AS $place) { ?>
    <?php echo $this->Form->input($place['Place']['id'].'.id', array('type' => 'hidden', 'label' => false, 'value' => $place['Place']['id'])); ?>
    <tr>
      <td><label><?php echo $place['Place']['name']; ?></label></td>
      <td><?php echo $this->Form->input($place['Place']['id'].'.sort', array('type' => 'text', 'label' => false, 'value' => $place['Place']['sort']-1, 'size' => 1)); ?></td>
    </tr>
    <?php } ?>
    
    <tr>
      <td></td>
      <td><?php echo $this->Form->submit('変更する', array('div' => false, 'class' => 'submit')); ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>

<div class="intro_places">
  <span class="link-page"><?php echo $this->Html->link('⇨ 会場一覧に戻る', '/places/place_lists/'); ?></span>
</div>