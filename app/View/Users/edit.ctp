<?php echo $this->Html->css('users', array('inline' => FALSE)); ?>
<h3>ユーザ情報の変更</h3>

  <?php echo $this->Form->create('User', array( //使用するModel
      'type' => 'put', //変更はput送信
      'action' => 'edit', //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
      )
  ); ?><!-- form start -->
  <?php echo $this->Form->input('id', array('type' => 'hidden', 'value' => $id)); ?>
  <?php echo $this->Form->input('username', array('type' => 'text', 'label' => 'メールアドレス', 'placeholder' => 'ログイン時に使用します')); ?><br>
  <?php echo $this->Form->input('handlename', array('type' => 'text', 'label' => 'ハンドルネーム', 'placeholder' => '他ユーザに公開されます')); ?>（16文字以内）<br>
  <?php echo $this->Form->input('station', array('type' => 'text', 'label' => '最寄り駅', 'placeholder' => '例）東京')); ?>駅
  
  <?php echo $this->Form->submit('変更'); ?>
  <?php echo $this->Form->end(); ?><!-- form end -->