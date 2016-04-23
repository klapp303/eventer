<?php echo $this->Html->css('pages', array('inline' => FALSE)); ?>
<h3>お知らせ、更新履歴</h3>

<?php //お知らせ、更新履歴を配列で渡しておく
  $array_history = [
      0 => [
          'date' => '2015-10-17',
          'title' => 'イベ幸ver1.0リリース！',
          'sub' => []
      ],
      1 => [
          'date' => '2016-04-08',
          'title' => 'イベ幸ver2.0リリース！',
          'sub' => [
              '別日程のイベントをまとめて管理できる（ツアーや1部2部など）',
              '複数のエントリーをまとめて管理できる（FC先行と一般など）',
              '会場データを大幅に追加',
              '参加者機能から収支管理機能へ変更（参加者に関係なく管理が可能）'
          ]
      ],
      2 => [
          'date' => '2016-04-21',
          'title' => 'ver2.1にアップデート！',
          'sub' => [
              'お知らせメール機能を追加'
          ]
      ]
  ];
  //日付順にソート
  foreach ($array_history AS $key => $val) {
    $sort[$key] = $val['date'];
  }
  array_multisort($sort, SORT_DESC, $array_history);
?>

  <table class="detail-list-min">
    <tr><th class="tbl-date">日付</th><th>更新内容</th></tr>
   
    <?php foreach ($array_history AS $key => $history) { ?>
    <tr><td class="tbl-date"><?php echo $history['date']; ?></td>
        <td><?php echo $history['title']; ?><?php echo ($key == 0)? '<span class="txt-alt txt-b">⇐ New</span>': ''; ?>
            <?php foreach ($history['sub'] AS $sub) { ?>
            <br><span class="sub_history"><?php echo $sub; ?></span>
            <?php } ?><br></td></tr>
    <?php } ?>
  </table>