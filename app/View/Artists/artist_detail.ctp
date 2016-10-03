<?php echo $this->Html->css('artists', array('inline' => false)); ?>
<h3>アーティスト詳細</h3>

  <table class="detail-list-min">
    <tr><th>アーティスト名</th>
        <th>公式サイト</th></tr>
    <tr><td><?php echo $artist_detail['Artist']['name']; ?>
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

<?php if ($userData['role'] >= 3) { ?>
  <div class="link-page_artists">
    <span class="link-page"><?php echo $this->Html->link('⇨ アーティスト情報を追加、修正する', '/artists/edit/' . $artist_detail['Artist']['id']); ?></span>
    <span class="link-page"><?php echo $this->Form->postLink('⇨ アーティストを削除する', array('action' => 'delete', $artist_detail['Artist']['id']), null, $artist_detail['Artist']['name'] . ' を本当に削除しますか'); ?></span>
  </div>
<?php } ?>

<h3>開催予定のイベント</h3>

  <?php if ($event_lists) { ?>
    <table class="event-list event-list_artist">
      <tr><th class="tbl-date">開催日</th>
          <th>イベント名</th>
          <th class="tbl-act-min">action</th></tr>
      
      <?php foreach ($event_lists as $event_list) { ?>
        <tr><td class="tbl-date"><?php echo date('Y/m/d(' . $week_lists[date('w', strtotime($event_list['EventsDetail']['date']))] . ')', strtotime($event_list['EventsDetail']['date'])); ?></td>
            <td><?php echo $event_list['Event']['title']; ?>
                <?php if ($event_list['Event']['title'] != $event_list['EventsDetail']['title']) { ?><br>
                  <span class="title-sub"><?php echo $event_list['EventsDetail']['title']; ?></span>
                <?php } ?>
            </td>
            <td class="tbl-act-min"><span class="icon-button"><?php echo $this->Html->link('詳細', '/event/' . $event_list['EventsDetail']['id'], array('target' => '_blank')); ?></span></td></tr>
      <?php } ?>
    </table>
  <?php } else { ?>
    <div class="intro_artists">
      <p>
        開催予定のイベントはありません。
      </p>
    </div>
  <?php } ?>

<div class="link-page_artists">
  <span class="link-page"><?php echo $this->Html->link('⇨ アーティストの一覧に戻る', '/artists/artist_lists/'); ?></span>
</div>