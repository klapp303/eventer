<h3>注意事項</h3>

  <ul>
    <li>身内だけとはいえwebアプリです。本名登録は推奨しません。</li>
    <li>パスワードの変更フォームは<s>めんどくさいので</s>現在ありません。登録時は慎重に。</li>
    <li>ユーザ削除フォームも現在ありません。紐付けが難しくなるので複数登録はNG＞＜</li>
    <li>商用とかじゃないのでセキュリティは最低限です。善意のユーザのみ想定しています。</li>
    <li>あともちろんですが、製作者はデータベースに登録された全データを閲覧できます、悪しからず。</li>
  </ul>

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