<?php echo $this->Html->css('users', array('inline' => FALSE)); ?>
<h3><?php if ($user_detail['User']['handlename'] != null) { ?>
    <?php echo $user_detail['User']['handlename'].'さんの'; ?>
    <?php } ?>
    ユーザ情報</h3>

  <table class="tbl-prof_user">
    <tr><th>ハンドルネーム</th><td><?php echo $user_detail['User']['handlename']; ?></td></tr>
    <tr><td><span class="txt-b">ユーザ名</span>（ログインに使用）</td><td><?php echo $user_detail['User']['username']; ?></td></tr>
    <tr><td><span class="txt-b">パスワード</span>（ログインに使用）</td><td>表示しないよ！！</td></tr>
    <tr><th>action</th><td class="tbl-ico"><span class="icon-button"><?php echo $this->Form->postLink('変更する', array('action' => 'edit', $user_detail['User']['id'])); ?></span></td></tr>
  </table>