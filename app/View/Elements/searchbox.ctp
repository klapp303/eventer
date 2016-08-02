<?php if (@$ctrl) { ?>
  <div class="searchbox">
    <?php echo $this->Form->create('Search', array( //使用するModel
        'type' => 'get', //デフォルトはpost送信
        'url' => array('controller' => $ctrl, 'action' => 'search'), //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
    )); ?>
    
    <?php echo $this->Form->input('word', array('type' => 'text', 'label' => false, 'value' => @$value, 'placeholder' => @$placeholder)); ?><br>
    <?php echo $this->Form->submit('検索する'); ?>
    
    <?php echo $this->Form->end(); ?>
  </div>
<?php } ?>