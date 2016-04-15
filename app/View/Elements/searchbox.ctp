<?php if (@$mode == 'event') { ?>
<div class="searchbox">
  <?php echo $this->Form->create('Search', array( //使用するModel
      'type' => 'get', //デフォルトはpost送信
      'url' => array('controller' => 'events', 'action' => 'search'), //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
      )
  ); ?>
  
  <?php echo $this->Form->input('title', array('type' => 'text', 'label' => false)); ?><br>
  <?php echo $this->Form->submit('検索する'); ?>
  
  <?php echo $this->Form->end(); ?>
</div>
<?php } elseif (@$mode == 'place') { ?>
<div class="searchbox">
  <?php echo $this->Form->create('Search', array( //使用するModel
      'type' => 'get', //デフォルトはpost送信
      'url' => array('controller' => 'places', 'action' => 'search'), //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
      )
  ); ?>
  
  <?php echo $this->Form->input('name', array('type' => 'text', 'label' => false)); ?><br>
  <?php echo $this->Form->submit('検索する'); ?>
  
  <?php echo $this->Form->end(); ?>
</div>
<?php } ?>