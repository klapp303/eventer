<?php
//searchboxのデフォルト設定
if (@!$controller) {
    $controller = 'events';
}
if (@!$action) {
    $action = 'search';
}
if (@!$button) {
    $button = '検索する';
}
?>
<div class="searchbox">
  <?php echo $this->Form->create('Search', array( //使用するModel
      'type' => 'get', //デフォルトはpost送信
      'url' => array('controller' => $controller, 'action' => $action), //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
  )); ?>
  
  <?php echo $this->Form->input('word', array('type' => 'text', 'label' => false, 'value' => @$value, 'placeholder' => @$placeholder)); ?><br>
  <?php echo $this->Form->submit($button); ?>
  
  <?php echo $this->Form->end(); ?>
</div>