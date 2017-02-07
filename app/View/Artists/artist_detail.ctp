<?php echo $this->Html->css('artists', array('inline' => false)); ?>
<h3>アーティスト詳細</h3>

  <table class="detail-list-min">
    <tr><th>アーティスト名</th>
        <th>公式サイト</th></tr>
    <tr><td><?php echo $artist_detail['Artist']['name']; ?><br>
            <span class="txt-min">（<?php echo $artist_detail['Artist']['kana']; ?>）</span>
        </td>
        <td><?php foreach ($artist_detail['Artist']['link_urls'] as $val) { ?>
              <a href="<?php echo $val['link_url']; ?>" target="_blank"><?php echo $val['link_url']; ?></a><br>
            <?php } ?></td></tr>
    
    <tr><td rowspan="2"><?php echo $this->Html->image('../files/artist/' . $artist_detail['Artist']['image_name'], array('alt' => $artist_detail['Artist']['alt_name'], 'class' => 'img_artist')); ?></td>
        <th>関連アーティスト</th></tr>
    <tr><td class="tbl-name-tag_artists"><div class="list-name-tag">
              <?php foreach ($artist_detail['Artist']['related_artists'] as $val) { ?>
                <span class="name-tag-long">
                  <a href="/artists/artist_detail/<?php echo $val['artist_id']; ?>"><?php echo $val['name']; ?></a>
                </span>
              <?php } ?>
            </div></td></tr>
  </table>

<div class="link-right">
  <?php if ($userData['role'] >= 3) { ?>
    <span class="link-page"><?php echo $this->Html->link('⇨ アーティスト情報を追加、修正する', '/artists/edit/' . $artist_detail['Artist']['id']); ?></span>
  <?php } ?>
  <span class="link-page"><?php echo $this->Form->postLink('⇨ アーティストを削除する', array('action' => 'delete', $artist_detail['Artist']['id']), null, $artist_detail['Artist']['name'] . ' を本当に削除しますか'); ?></span>
</div>

<h3>開催予定のイベント</h3>

  <?php echo $this->element('eventer_eventlist', array('paginator' => false)); ?>

<div class="link-right">
  <span class="link-page"><?php echo $this->Html->link('⇨ すべてのイベント一覧を確認する', '/artists/event_lists/' . $artist_detail['Artist']['id']); ?></span>
  <span class="link-page"><?php echo $this->Html->link('⇨ アーティストの一覧に戻る', '/artists/artist_lists/'); ?></span>
</div>