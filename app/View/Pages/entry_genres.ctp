<?php echo $this->Html->css('pages', array('inline' => false)); ?>
<div class="intro">
  <p>
    イベントへのエントリー方法（申込方法）のジャンル説明一覧です。<br>
    <br>
    FCやメディア先行は優先度が高く、各チケットサイトは先着順かもしれない等、<br>
    各自の判断材料の参考にしてください。
  </p>
</div>

<h3><?php echo $sub_page; ?></h3>

  <table class="detail-list">
      <tr><th>エントリー名</th>
          <th class="tbl-genre">ルール</th>
          <th class="tbl-genre">コスト</th>
          <th class="tbl-genre">使用システム</th></tr>
    
    <?php foreach ($entry_genre_lists as $entry_genre_list): ?>
    <tr><td><?php echo $entry_genre_list['EntryGenre']['title']; ?></td>
        <td class="tbl-genre"><span class="icon-genre col-rule_<?php echo $entry_genre_list['EntryGenre']['entry_rule_id']; ?>"><?php echo $entry_genre_list['EntryRule']['title']; ?></span></td>
        <td class="tbl-genre"><span class="icon-genre col-cost_<?php echo $entry_genre_list['EntryGenre']['entry_cost_id']; ?>"><?php echo $entry_genre_list['EntryCost']['title']; ?></span></td>
        <td><?php if ($entry_genre_list['EntrySystem']['url']): ?>
            <a href="<?php echo $entry_genre_list['EntrySystem']['url']; ?>" target="_blank"><?php echo $entry_genre_list['EntrySystem']['title']; ?></a>
            <?php else: ?>
            <?php echo $entry_genre_list['EntrySystem']['title']; ?>
            <?php endif; ?></td></tr>
    <?php endforeach; ?>
  </table>