<?php echo $this->Html->css('users', array('inline' => false)); ?>
<h3><?php if ($user_detail['User']['handlename'] != null): ?>
    <?php echo $user_detail['User']['handlename'] . 'さんの'; ?>
    <?php endif; ?>ユーザ情報</h3>

  <table class="detail-list-min tbl-prof_user">
    <tr><th>ハンドルネーム</th><td><?php echo $user_detail['User']['handlename']; ?></td></tr>
    <tr><th>メールアドレス<span class="txt-min txt-n">（ログインに使用）</span></th><td><?php echo $user_detail['User']['username']; ?></td></tr>
    <tr><th>パスワード<span class="txt-min txt-n">（ログインに使用）</span></th><td>********</td></tr>
    <tr><th>最寄り駅</th><td><?php echo $user_detail['User']['station']; ?><?php echo ($user_detail['User']['station'])? '駅' : ''; ?></td></tr>
    <tr><th>お知らせメール</th>
        <?php if ($user_detail['User']['mail'] == 0): ?>
        <td><span class="txt-min">現在は</span> 配信しない <span class="txt-min">です</span></td>
        <?php elseif ($user_detail['User']['mail'] == 1): ?>
        <td><span class="txt-min">現在は</span> 配信する <span class="txt-min">です</span></td>
        <?php endif; ?>
    </tr>
    <tr><th>action</th>
        <td class="tbl-ico tbl-ico-prof">
          <span class="icon-button"><?php echo $this->Form->postLink('ユーザ情報の変更', array('action' => 'edit', $user_detail['User']['id'])); ?></span>
          <span class="icon-button"><?php echo $this->Form->postLink('パスワードの変更', array('action' => 'pw_edit', $user_detail['User']['id'])); ?></span>
        </td></tr>
  </table>

<div class="link-right">
  <span class="link-page"><?php echo $this->Html->link('⇨ 参加データの分析はこちら', '#'); ?></span>
</div>