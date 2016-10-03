<?php echo $this->Html->css('artists', array('inline' => false)); ?>
<h3>アーティスト詳細</h3>

  <table class="detail-list-min">
    <tr><th>アーティスト名</th>
        <th>公式サイト</th></tr>
    <tr><td><?php echo $artist_detail['Artist']['name']; ?>
            <span class="txt-min">（<?php echo $artist_detail['Artist']['kana']; ?>）</span>
        </td>
        <td><?php echo $artist_detail['Artist']['url']; ?></td></tr>
  </table>
  
  <table class="detail-list-min">
    <tr><th>状態</th>
        <th>説明</th>
        <th>関連アーティスト</th></tr>
    <tr><td><?php if ($artist_detail['Artist']['publish'] == 0) { ?>
              <span class="icon-false">非表示</span>
            <?php } elseif ($artist_detail['Artist']['publish'] == 1) { ?>
              <span class="icon-true">表示</span>
            <?php } ?></td>
        <td><?php echo $artist_detail['Artist']['description']; ?></td>
        <td><?php foreach ($related_artist_lists as $artist) { ?>
              <a href="/artists/artist_detail/<?php ?>"><?php ?></a><br>
            <?php } ?></td></tr>
  </table>

<?php if ($userData['role'] >= 3) { ?>
  <div class="link-page_artists">
    <span class="link-page"><?php echo $this->Html->link('⇨ アーティスト情報を追加、修正する', '/artists/edit/' . $artist_detail['Artist']['id']); ?></span>
  </div>
<?php } ?>

<h3>開催予定のイベント</h3>

  <?php if ($event_lists) { ?>
    <table class="event-list event-list_place">
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