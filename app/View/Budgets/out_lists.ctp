<!-- 未使用 -->
<?php echo $this->Html->css('budgets', array('inline' => false)); ?>
<div class="intro">
  <p>
    支払い確認のフラグが立っていない支出一覧です。<br>
    基本的にステータス、フラグ管理はイベント作成者によります。
  </p>
</div>

<h3>支出一覧</h3>

  <?php echo $this->Paginator->numbers($paginator_option); ?>

  <table class="detail-list">
    <tr><th class="tbl-date">開催日<?php echo $this->Paginator->sort('EventDetail.date', '▼'); ?></th>
        <th>イベント名<?php echo $this->Paginator->sort('EventDetail.title', '▼'); ?></th>
        <th class="tbl-ico">種類<br>
                            状態</th>
        <th>作成者</th>
        <th class="tbl-num">金額</th>
        <th class="tbl-date">入金<br>締切日<?php echo $this->Paginator->sort('EventUser.payment_end', '▼'); ?></th>
        <th class="tbl-action_out">action</th></tr>
    
    <?php foreach ($out_lists as $out_list): ?>
    <tr><td class="tbl-date"><?php echo $out_list['EventDetail']['date']; ?></td>
        <td><?php echo $out_list['EventDetail']['title']; ?></td>
        <td class="tbl-ico"><span class="icon-genre col-event_<?php echo $out_list['EventDetail']['genre_id']; ?>"><?php echo $out_list['EventDetail']['EventGenre']['title']; ?></span><br>
                            <?php foreach ($eventEntryStatus as $entry_status) {
                                if ($entry_status['status'] == $out_list['EventDetail']['status']) {
                                    echo '<span class="icon-' . $entry_status['class'] . '">' . $entry_status['name'] . '</span>';
                                    break;
                                }
                            } ?></td>
        <td><?php echo $out_list['EventDetail']['UserName']['handlename']; ?></td>
        <td class="tbl-num"><?php echo $out_list['EventDetail']['amount']; ?>円</td>
        <td class="tbl-date"><?php echo $out_list['EventDetail']['payment_end']; ?></td>
        <td class="tbl-action_out"><span class="icon-button"><?php echo $this->Html->link('詳細', '/events/' . $out_list['EventDetail']['id'], array('target' => '_blank')); ?></span></td></tr>
    <?php endforeach; ?>
  </table>