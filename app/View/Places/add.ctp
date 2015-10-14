<h3>会場の登録</h3>

  <?php echo $this->Form->create('Place', array( //使用するModel
      'type' => 'post', //デフォルトはpost送信
      'action' => 'add', //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
      )
  ); ?><!-- form start -->
  <?php echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.id'))); ?>
  <?php echo $this->Form->input('name', array('type' => 'text', 'label' => '会場名')); ?><br>
  <?php echo $this->Form->input('capacity', array('type' => 'text', 'label' => 'キャパシティ')); ?><br>
  <?php echo $this->Form->input('access', array('type' => 'text', 'label' => 'アクセス')); ?><br>
  <?php echo $this->Form->input('url', array('type' => 'text', 'label' => '公式サイト')); ?><br>
  
  <?php echo $this->Form->submit('登録する'); ?>
  <?php echo $this->Form->end(); ?><!-- form end -->