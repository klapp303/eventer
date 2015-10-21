<?php echo $this->Html->css('users', array('inline' => FALSE)); ?>
<h3>パスワードの変更</h3>

<p class="txt-min">（次回ログイン時から変更が反映されます）</p>

  <table class="UserEditForm">
    <?php echo $this->Form->create('User', array( //使用するModel
        'type' => 'put', //変更はput送信
        'action' => 'pw_edit', //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
        )
    ); ?><!-- form start -->
    <?php echo $this->Form->input('id', array('type' => 'hidden', 'value' => $id)); ?>
    <tr>
      <td><label>新しいパスワード</label></td>
      <td><?php echo $this->Form->input('password', array('type' => 'text', 'label' => false, 'value' => '')); ?><span class="txt-alt txt-b">*</span><span class="txt-min">（半角英数のみ）</span></td>
    </tr>
    
    <tr>
      <td></td>
      <td><?php echo $this->Form->submit('変更', array('div' => false, 'class' => 'submit')); ?>　　<span class="txt-alt txt-b">*</span><span class="txt-min">は必須項目</span></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>