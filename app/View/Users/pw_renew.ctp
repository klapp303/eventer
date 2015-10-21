<?php echo $this->Html->css('users', array('inline' => FALSE)); ?>
<h3>パスワードのリセット</h3>

<p>登録されているメールアドレスを入力してください。<br>
   新しいパスワードを発行しメールでお知らせします。</p>

  <table class="UserEditForm">
    <?php echo $this->Form->create('User', array( //使用するModel
        'type' => 'put', //変更はput送信
        'action' => 'pw_renew', //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
        )
    ); ?><!-- form start -->
    <tr>
      <td><label>メールアドレス</label></td>
      <td><?php echo $this->Form->input('username', array('type' => 'text', 'label' => false, 'value' => '')); ?><span class="txt-alt txt-b">*</span></td>
    </tr>
    
    <tr>
      <td></td>
      <td><?php echo $this->Form->submit('送信', array('div' => false, 'class' => 'submit')); ?>　　<span class="txt-alt txt-b">*</span><span class="txt-min">は必須項目</span></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>

<div class="link-page_users">
  <span class="link-page"><?php echo $this->Html->link('⇨ ログインはこちら', '/users/login/'); ?></span>
</div>
<div class="link-page_users">
  <span class="link-page"><?php echo $this->Html->link('⇨ 新規登録はこちら', '/users/add/'); ?></span>
</div>