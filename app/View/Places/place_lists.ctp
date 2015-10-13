<h3>会場の登録</h3>

  <p>
    将来的にはそんなしょっちゅう登録するものでもないので、<br>
    リストの見やすさも考えて別ページで検討中。<br>
    いずれにせよ、テストDBに登録してもまた本番で登録しなきゃなので、<br>
    登録（流用してるから＋編集）フォームは後回し。<br>
    リンクclickしてもバーボンだよっと。<br><br>
    というか書いてて気付いたけど、削除されると紐付くイベントでエラー出るから削除はなしかな。<br>
    でも登録ミスったら削除いちいち製作者に言わないとできないのもアレだし…<br>
    一部ユーザに管理権限付与とかかなぁ…フラグの立ってるユーザのみ登録編集削除できるとか。
  </p>

<h3>会場一覧</h3>

  <?php echo $this->Paginator->numbers(array(
      'modulus' => 4, //現在ページから左右あわせてインクルードする個数
      'separator' => '|', //デフォルト値のセパレーター
      'first' => '＜', //先頭ページへのリンク
      'last' => '＞' //最終ページへのリンク
  )); ?>

  <table class="detail-list">
    <tr><th class="tbl-date_events">会場名<?php echo $this->Paginator->sort('name', '▼'); ?></th>
        <th class="tbl-num">キャパシティ</th>
        <th>アクセス</th>
        <th class="tbl-action_events">action</th></tr>
    
    <?php foreach ($place_lists AS $place_list) { ?>
    <tr><td><?php echo $place_list['Place']['name']; ?></td>
        <td class="tbl-num"><?php echo $place_list['Place']['capacity']; ?>名</td>
        <td><?php echo $place_list['Place']['access']; ?></td>
        <td class="tbl-action_events"><span class="icon-button"><?php echo $this->Html->link('詳細', '/places/place_detail/'.$place_list['Place']['id'], array('target' => '_blank')); ?></span>
            <br><span class="icon-button"><?php echo $this->Html->link('修正', '/places/edit/'.$place_list['Place']['id']); ?></span>
            <span class="icon-button"><?php echo $this->Form->postLink('削除', array('action' => 'delete', $place_list['Place']['id']), null, '本当に削除しますか'); ?></span></td></tr>
    <?php } ?>
  </table>