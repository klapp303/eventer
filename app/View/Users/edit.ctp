<?php echo $this->Html->css('users', array('inline' => FALSE)); ?>
<h3>ユーザ情報の変更</h3>

  <table class="UserEditForm">
    <?php echo $this->Form->create('User', array( //使用するModel
        'type' => 'put', //変更はput送信
        'action' => 'edit', //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
        )
    ); ?><!-- form start -->
    <?php echo $this->Form->input('id', array('type' => 'hidden', 'value' => $id)); ?>
    <tr>
      <td><label>メールアドレス</label></td>
      <td><?php echo $this->Form->input('username', array('type' => 'text', 'label' => false, 'placeholder' => 'ログイン時に使用します')); ?><span class="txt-alt txt-b">*</span></td>
    </tr>
    <tr>
      <td><label>ハンドルネーム</label></td>
      <td><?php echo $this->Form->input('handlename', array('type' => 'text', 'label' => false, 'placeholder' => '他ユーザに公開されます')); ?><span class="txt-alt txt-b">*</span><span class="txt-min">（16文字以内）</span></td>
    </tr>
    <tr>
      <td><label>最寄り駅（任意）</label></td>
      <td><?php echo $this->Form->input('station', array('type' => 'text', 'label' => false, 'placeholder' => '例）東京')); ?>駅</td>
    </tr>
    <tr>
      <td><label>参加者機能</label></td>
      <td><?php echo $this->Form->input('mail', array('type' => 'select', 'label' => false, 'options' => array(0 => '配信しない', 1 => '配信する'))); ?></td>
    </tr>
    
    <tr>
      <td></td>
      <td><?php echo $this->Form->submit('変更', array('div' => false, 'class' => 'submit')); ?>　　<span class="txt-alt txt-b">*</span><span class="txt-min">は必須項目</span></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>