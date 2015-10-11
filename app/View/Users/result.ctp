<!-- 未使用 -->
<?php echo $this->Html->script('jquery-hide', array('inline' => FALSE)); ?>
<h3>ユーザ登録</h3>

  <p>
    登録されたデータは下記になります。<br>
    登録情報は各自で管理してください。<br>
    確認メールとか別にいらないよね？
  </p>
  
  <table class="detail-list">
    <tr><th>ユーザ名</th>
        <td><?php echo $this->request->data['User']['username']; ?></td></tr>
    <tr><th>ハンドルネーム</th>
        <td><?php echo $this->request->data['User']['handlename']; ?></td></tr>
    <tr><th>パスワード<br><span class="icon-genre js-hide-button">確認する</span></th>
        <td><span class="js-show">表示しないよ！！</span>
            <span class="js-hide"><?php echo $this->request->data['User']['password']; ?></span></td></tr>
  </table>

<p><?php echo $this->Html->link('ログインはこちら', '/users/login/'); ?></p>