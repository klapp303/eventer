<?php echo $this->Html->css('users', array('inline' => FALSE)); ?>
<h3><?php if ($user_detail['User']['handlename'] != null) { ?>
    <?php echo $user_detail['User']['handlename'].'さんの'; ?>
    <?php } ?>
    ユーザ情報</h3>

  <table class="detail-list-min tbl-prof_user">
    <tr><th>ハンドルネーム</th><td><?php echo $user_detail['User']['handlename']; ?></td></tr>
    <tr><th>メールアドレス<span class="txt-min txt-n">（ログインに使用）</span></th><td><?php echo $user_detail['User']['username']; ?></td></tr>
    <tr><th>パスワード<span class="txt-min txt-n">（ログインに使用）</span></th><td>表示しないよ！！</td></tr>
    <tr><th>最寄り駅</th><td><?php echo $user_detail['User']['station']; ?>駅</td></tr>
    <tr><th>参加者機能</th><?php if ($user_detail['User']['community_id'] == 0) { ?><td>利用しない</td>
                   <?php } elseif ($user_detail['User']['community_id'] == 1) { ?><td>利用する</td>
                   <?php } ?></tr>
    <tr><th>action</th><td class="tbl-ico"><span class="icon-button"><?php echo $this->Form->postLink('変更する', array('action' => 'edit', $user_detail['User']['id'])); ?></span></td></tr>
  </table>

<div class="link-page_users">
  <span class="link-page"><?php echo $this->Form->postLink('⇨ パスワード変更はこちら', array('action' => 'pw_edit', $user_detail['User']['id'])); ?></span>
</div>