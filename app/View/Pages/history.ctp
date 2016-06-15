<?php echo $this->Html->css('pages', array('inline' => false)); ?>
<h3>お知らせ、更新履歴</h3>

<?php
//日付順にソート
foreach ($array_history as $key => $val) {
    $sort[$key] = $val['date'];
}
array_multisort($sort, SORT_DESC, $array_history);
?>

  <table class="detail-list-min">
    <tr><th class="tbl-date">日付</th><th>更新内容</th></tr>
   
    <?php foreach ($array_history as $key => $history) { ?>
      <tr><td class="tbl-date"><?php echo $history['date']; ?></td>
          <td><?php echo $history['title']; ?><?php echo ($key == 0)? '<span class="txt-alt txt-b">⇐ New</span>' : ''; ?>
              <?php foreach ($history['sub'] as $sub) { ?>
                <br><span class="sub_history"><?php echo $sub; ?></span>
              <?php } ?><br></td></tr>
    <?php } ?>
  </table>