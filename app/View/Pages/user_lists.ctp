<p>
  将来的な参加者の管理で選択させる事になるけど人数分からん＼(^o^)／<br>
  身内だけだし全ユーザ全イベント表示で修正削除が作成者のみでいいハズ…<br>
  とはいえ友達の友達はもはや他人だからハンドルネームfieldは作ってある。<br>
  ネットに本名とか怖いもんね！！！
</p>

<h3>ユーザ一覧</h3>

  <table class="detail-list">
    <tr><th class="tbl-num">ユーザID</th><th>ユーザ名</th><th>ハンドルネーム</th></tr>
    <?php foreach ($user_lists AS $user_list) { ?>
    <tr><td class="tbl-num"><?php echo $user_list['User']['id']; ?></td>
        <td><?php echo $user_list['User']['username']; ?></td>
        <td><?php echo $user_list['User']['handlename']; ?></td></tr>
    <?php } ?>
  </table>