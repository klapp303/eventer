<?php echo $this->Html->css('users', array('inline' => false)); ?>
<div class="intro_login">
  <h3>イベ幸って？</h3>
  
  <p>
    ライブやイベントによく行く作者が、申し込みや入金を忘れないようにと
    イベントの日程やチケット、申込方法を一元管理するために作ったWebアプリです。<br>
    <br>
    各イベントの申込開始日時から終了日時、当落発表、入金締切、
    そして開催日時場所など忘れがちな情報をまとめて登録管理する事ができます。
  </p>
  
  <span class="link-page"><?php echo $this->Html->link('⇨ 詳しい機能と使い方を確認する', '/pages/about/'); ?></span>
</div>

<h3>ログイン</h3>

  <table class="UserLoginForm">
    <?php echo $this->Form->create('User', array( //使用するModel
        'type' => 'post', //デフォルトはpost送信
        'action' => 'login', //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
    )); ?><!-- form start -->
    
    <tr>
      <td><label>メールアドレス</label></td>
      <td><?php echo $this->Form->input('username', array('type' => 'text', 'label' => false, 'value' => @$guest_name)); ?></td>
    </tr>
    <tr>
      <td><label>パスワード</label></td>
      <td><?php echo $this->Form->input('password', array('type' => 'text', 'label' => false, 'value' => @$guest_password)); ?></td>
    </tr>
    
    <tr>
      <td></td>
      <td><?php echo $this->Form->submit('ログイン'); ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>

<div class="link-page_users">
  <span class="link-page"><?php echo $this->Html->link('⇨ 新規登録はこちら', '/users/add/'); ?></span>
</div>
<div class="link-page_users">
  <span class="link-page"><?php echo $this->Html->link('⇨ パスワードを忘れた場合はこちら', '/users/pw_renew/'); ?></span>
</div>