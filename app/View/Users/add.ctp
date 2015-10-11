<h3>ユーザ登録</h3>

  <?php echo $this->Form->create('User', array( //使用するModel
      'type' => 'post', //デフォルトはpost送信
      'action' => 'add', //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
      )
  ); ?><!-- form start -->
  <?php echo $this->Form->input('username', array('type' => 'text', 'label' => 'ユーザ名', 'placeholder' => 'ログイン時に使用します')); ?>（半角英数のみ）<br>
  <?php echo $this->Form->input('handlename', array('type' => 'text', 'label' => 'ハンドルネーム', 'placeholder' => '他ユーザに公開されます')); ?>（16文字以内）<br>
  <?php echo $this->Form->input('password', array('type' => 'text', 'label' => 'パスワード', 'placeholder' => 'ログイン時に使用します')); ?>（半角英数のみ）
  
  <?php echo $this->Form->submit('登録'); ?>
  <?php echo $this->Form->end(); ?><!-- form end -->

<p><?php echo $this->Html->link('ログインはこちら', '/users/login/'); ?></p>