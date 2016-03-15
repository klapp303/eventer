<?php echo $this->Html->css('events', array('inline' => FALSE)); ?>
<?php if (preg_match('#/events/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
<h3>イベントの編集</h3>

  <?php echo $this->Form->create('Event', array( //使用するModel
      'type' => 'put', //変更はput
      'action' => 'edit', //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
      )
  ); ?>
<?php } else { //登録用 ?>
<h3>イベントの登録</h3>

  <?php echo $this->Form->create('Event', array( //使用するModel
      'type' => 'post', //デフォルトはpost送信
      'action' => 'add', //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
      )
  ); ?>
<?php } ?><!-- form start -->
  
  <?php if (preg_match('#/events/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
    <?php echo $this->Form->input('Event.id', array('type' => 'hidden')); ?>
  <?php } else { //登録用 ?>
    <?php echo $this->Form->input('Event.user_id', array('type' => 'hidden', 'value' => $userData[('id')])); ?>
  <?php } ?>
  
  <div>
    <label>イベント名（全体）</label>
    <?php echo $this->Form->input('Event.title', array('type' => 'text', 'label' => false, 'size' => 25, 'placeholder' => '例）竹達彩奈 2ndALツアー')); ?><span class="txt-alt txt-b">*</span>
    <label>公開設定</label>
    <?php echo $this->Form->input('Event.publish', array('type' => 'select', 'label' => false, 'options' => array(1 => '公開', 0 => '非公開'))); ?><span class="txt-alt txt-b">*</span><br>
    <span class="txt-min">登録が1つだけの場合は、イベント名（全体）と（各公演）は同じで構いません。</span>
    <button type="button">イベント名をコピー</button>
  </div>
  
  <?php for ($i = 0; $i < 4; $i++) { ?>
  
  <?php if (preg_match('#/events/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
    <?php echo $this->Form->input('EventsDetail.'.$i.'.id', array('type' => 'hidden')); ?>
  <?php } ?>
  
  <table class="fl">
    <tr><td>イベント名<br>（各公演）</td>
        <td><?php echo $this->Form->input('EventsDetail.'.$i.'.title', array('type' => 'text', 'label' => false, 'required' => ($i == 0)? true: false, 'size' => 20, 'placeholder' => '例）東京2日目、昼の部etc')); ?><?php echo ($i == 0)? '<span class="txt-alt txt-b">*</span>': ''; ?></td></tr>
    <tr><td>種類</td>
        <td><?php echo $this->Form->input('EventsDetail.'.$i.'.genre_id', array('type' => 'select', 'label' => false, 'options' => $event_genres)); ?><?php echo ($i == 0)? '<span class="txt-alt txt-b">*</span>': ''; ?></td></tr>
    <tr><td>会場</td>
        <td><?php echo $this->Form->input('EventsDetail.'.$i.'.place_id', array('type' => 'select', 'label' => false, 'options' => $place_lists)); ?><?php echo ($i == 0)? '<span class="txt-alt txt-b">*</span>': ''; ?></td></tr>
    <tr><td>開催日</td>
        <td><?php echo $this->Form->input('EventsDetail.'.$i.'.date', array('type' => 'date', 'label' => false, 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y')+1, 'minYear' => 2015)); ?><?php echo ($i == 0)? '<span class="txt-alt txt-b">*</span>': ''; ?></td></tr>
    <tr><td>開場時刻</td>
        <td><?php echo $this->Form->input('EventsDetail.'.$i.'.time_open', array('type' => 'time', 'label' => false, 'timeFormat' => '24')); ?></td></tr>
    <tr><td>開演時刻</td>
        <td><?php echo $this->Form->input('EventsDetail.'.$i.'.time_start', array('type' => 'time', 'label' => false, 'timeFormat' => '24')); ?></td></tr>
  </table>
  <?php } ?>
  
  <div class="cf" style="width: 800px;">
    <?php echo $this->Form->submit((preg_match('#/events/edit/#', $_SERVER['REQUEST_URI']))? '編集する': '登録する', array('div' => false, 'class' => 'submit')); ?>　<span class="txt-alt txt-b">*</span><span class="txt-min">は必須項目</span>
  </div>
  
  <?php echo $this->Form->end(); ?><!-- form end -->

<h3>イベント一覧</h3>

  <?php echo $this->Paginator->numbers(array(
      'modulus' => 4, //現在ページから左右あわせてインクルードする個数
      'separator' => '|', //デフォルト値のセパレーター
      'first' => '＜', //先頭ページへのリンク
      'last' => '＞' //最終ページへのリンク
  )); ?>

  <table class="detail-list">
    <tr><th class="tbl-date">開催日<?php echo $this->Paginator->sort('EventsDetail.date', '▼'); ?></th>
        <th>イベント名<?php echo $this->Paginator->sort('EventsDetail.title', '▼'); ?></th>
        <th class="tbl-ico">種類<br>
                            状態</th>
        <th class="tbl-action">action</th></tr>
    
    <?php foreach ($event_lists AS $event_list) { ?>
    <tr><td class="tbl-date"><?php echo $event_list['EventsDetail']['date']; ?></td>
        <td><?php echo $event_list['Event']['title']; ?>
            <?php if ($event_list['Event']['title'] != $event_list['EventsDetail']['title']) { ?>
            <br>　<?php echo $event_list['EventsDetail']['title']; ?>
            <?php } ?>
        </td>
        <td class="tbl-ico"><span class="icon-genre col-event_<?php echo $event_list['EventsDetail']['genre_id']; ?>"><?php echo $event_list['EventsDetail']['genre_id']; ?></span>
                            <br><?php if ($event_list['Event']['publish'] == 0) {echo '<span class="icon-genre">未定</span>';}
                                  elseif ($event_list['Event']['publish'] == 1) {echo '<span class="icon-like">申込中</span>';}
                                  elseif ($event_list['Event']['publish'] == 2) {echo '<span class="icon-true">確定</span>';}
                                  elseif ($event_list['Event']['publish'] == 3) {echo '<span class="icon-false">落選</span>';} ?></td>
        <td class="tbl-action"><span class="icon-button"><?php echo $this->Html->link('詳細', '/event/'.$event_list['EventsDetail']['id'], array('target' => '_blank')); ?></span>
            <?php if ($event_list['EventsDetail']['user_id'] == $userData['id']) { ?>
            <br><span class="icon-button"><?php echo $this->Html->link('修正', '/events/edit/'.$event_list['Event']['id']); ?></span>
            <span class="icon-button"><?php echo $this->Form->postLink('削除', array('action' => 'delete', $event_list['EventsDetail']['id']), null, ($event_list['Event']['title'] != $event_list['EventsDetail']['title'])? $event_list['Event']['title'].' の
'.$event_list['EventsDetail']['title'].' を本当に削除しますか': $event_list['EventsDetail']['title'].' を本当に削除しますか'); ?></span>
            <?php } ?></td></tr>
    <?php } ?>
  </table>

<div class="link-page_events">
  <span class="link-page"><?php echo $this->Html->link('⇨ 公開されている全てのイベントはこちら', '/events/all_lists/'); ?></span>
  <span class="link-page"><?php echo $this->Html->link('⇨ 過去のイベントはこちら', '/events/past_lists/'); ?></span>
</div>