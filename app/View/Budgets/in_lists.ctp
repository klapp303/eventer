<!-- 未使用 -->
<?php echo $this->Html->css('budgets', array('inline' => FALSE)); ?>
<div class="intro_budgets">
  <p>
    受け取り確認のフラグが立っていない収入一覧です。<br>
    <span class="icon-button">受取</span>のボタンからフラグを立てて管理できます。<br><br>
    <span class="txt-alt txt-min">（削除すると元に戻せません）</span>
  </p>
</div>

<h3>収入一覧</h3>

  <?php echo $this->Paginator->numbers(array(
      'modulus' => 4, //現在ページから左右あわせてインクルードする個数
      'separator' => '|', //デフォルト値のセパレーター
      'first' => '＜', //先頭ページへのリンク
      'last' => '＞' //最終ページへのリンク
  )); ?>

  <table class="detail-list">
    <tr><th class="tbl-date">開催日<?php echo $this->Paginator->sort('EventDetail.date', '▼'); ?></th>
        <th>イベント名<?php echo $this->Paginator->sort('EventDetail.title', '▼'); ?></th>
        <th class="tbl-ico">種類<br>
                            状態</th>
        <th>参加者</th>
        <th class="tbl-num">金額</th>
        <th class="tbl-date">入金<br>締切日<?php echo $this->Paginator->sort('EventUser.payment_end', '▼'); ?></th>
        <th class="tbl-action">action</th></tr>
    
    <?php foreach ($in_lists AS $in_list) { ?>
    <tr><td class="tbl-date"><?php echo $in_list['EventDetail']['date']; ?></td>
        <td><?php echo $in_list['EventDetail']['title']; ?></td>
        <td class="tbl-ico"><span class="icon-genre col-event_<?php echo $in_list['EventDetail']['genre_id']; ?>"><?php echo $in_list['EventDetail']['EventGenre']['title']; ?></span>
                            <br><?php if ($in_list['EventDetail']['status'] == 0) {echo '<span class="icon-genre">未定</span>';}
                                  elseif ($in_list['EventDetail']['status'] == 1) {echo '<span class="icon-like">申込中</span>';}
                                  elseif ($in_list['EventDetail']['status'] == 2) {echo '<span class="icon-true">確定</span>';}
                                  elseif ($in_list['EventDetail']['status'] == 3) {echo '<span class="icon-false">落選</span>';} ?></td>
        <td><?php echo $in_list['UserProfile']['handlename']; ?></td>
        <td class="tbl-num"><?php echo $in_list['EventDetail']['amount']; ?>円</td>
        <td class="tbl-date"><?php echo $in_list['EventDetail']['payment_end']; ?></td>
        <td class="tbl-action"><span class="icon-button"><?php echo $this->Html->link('詳細', '/event/'.$in_list['EventDetail']['id'], array('target' => '_blank')); ?></span>
            <br><span class="icon-button"><?php echo $this->Form->postLink('受取', array('action' => 'edit', $in_list['EventUser']['id']), null, '受け取り確認済みに変更しますか'); ?></span>
            <span class="icon-button"><?php echo $this->Form->postLink('削除', array('action' => 'delete', $in_list['EventUser']['id']), null, '本当に削除しますか'); ?></span></td></tr>
    <?php } ?>
  </table>