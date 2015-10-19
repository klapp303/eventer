<?php echo $this->Html->script('jquery-checked', array('inline' => FALSE)); ?>
<?php echo $this->Html->css('events', array('inline' => FALSE)); ?>
<h3>イベントの登録</h3>

  <?php echo $this->Form->create('Event', array( //使用するModel
      'type' => 'post', //デフォルトはpost送信
      'action' => 'add', //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
      )
  ); ?><!-- form start -->
  <?php $user_id = $this->Session->read('Auth.User.id'); ?>
  <?php echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $user_id)); ?>
  <?php echo $this->Form->input('title', array('type' => 'text', 'label' => 'イベント名', 'size' => 20)); ?><span class="txt-alt txt-b">*</span><br>
  <?php echo $this->Form->input('genre_id', array('type' => 'select', 'label' => '種類', 'options' => $event_genres)); ?><span class="txt-alt txt-b">*</span>
  <?php echo $this->Form->input('place_id', array('type' => 'select', 'label' => '会場', 'options' => $place_lists)); ?><span class="txt-alt txt-b">*</span><br>
  <?php echo $this->Form->input('date', array('type' => 'date', 'label' => '開催日', 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y')+1, 'minYear' => 2015)); ?><span class="txt-alt txt-b">*</span>
  <?php echo $this->Form->input('time_start', array('type' => 'time', 'label' => '開催時刻', 'timeFormat' => '24', 'class'=>'js-input_time_start')); ?>
    なし<?php echo '<input type="checkbox" name="time_start" class="js-checkbox_time_start">'; ?><br>
  <?php echo $this->Form->input('amount', array('type' => 'text', 'label' => '金額', 'size' => 5)); ?>円<span class="txt-alt txt-b">*</span>
  <?php echo $this->Form->input('number', array('type' => 'text', 'label' => '枚数', 'size' => 3)); ?>枚<span class="txt-alt txt-b">*</span>
  <?php echo $this->Form->input('entry_id', array('type' => 'select', 'label' => '申込方法', 'options' => $entry_genres)); ?><span class="txt-alt txt-b">*</span><br>
  <?php echo $this->Form->input('entry_start', array('type' => 'date', 'label' => '申込開始日', 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y')+1, 'minYear' => 2015, 'class'=>'js-input_entry_start')); ?>
    なし<?php echo '<input type="checkbox" name="entry_start" class="js-checkbox_entry_start">'; ?>
  <?php echo $this->Form->input('entry_end', array('type' => 'date', 'label' => '申込終了日', 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y')+1, 'minYear' => 2015, 'class'=>'js-input_entry_end')); ?>
    なし<?php echo '<input type="checkbox" name="entry_end" class="js-checkbox_entry_end">'; ?><br>
  <?php echo $this->Form->input('announcement_date', array('type' => 'date', 'label' => '当落発表日', 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y')+1, 'minYear' => 2015, 'class'=>'js-input_announcement_date')); ?>
    なし<?php echo '<input type="checkbox" name="announcement_date" class="js-checkbox_announcement_date">'; ?>
  <?php echo $this->Form->input('payment_end', array('type' => 'date', 'label' => '入金締切日', 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y')+1, 'minYear' => 2015, 'class'=>'js-input_payment_end')); ?>
    なし<?php echo '<input type="checkbox" name="payment_end" class="js-checkbox_payment_end">'; ?><br>
  <?php echo $this->Form->input('status', array('type' => 'select', 'label' => '状態', 'options' => array(0 => '未定', 1 => '申込中', 2 => '確定', 3 => '落選'))); ?><span class="txt-alt txt-b">*</span>
  <?php echo $this->Form->input('publish', array('type' => 'select', 'label' => '公開設定', 'options' => array(0 => '参加者のみ', 1 => '全てのユーザ'))); ?><span class="txt-alt txt-b">*</span><br>
   
  <!-- FormHelperで難しかったのでHTMLタグ打ち -->
  <label>参加者</label><span class="txt-min">（参加者にもイベントは公開されます）</span><br>
  <?php if ($this->Session->read('Auth.User.community_id') == 1) { ?>
    <?php $i = 0; ?>
    <div class="form-user_events">
    <?php foreach ($user_lists AS $user_list) { ?>
      <span class="checkbox_user txt-min">
      <input type="checkbox" name="data[UserList][<?php echo $i; ?>][user_id]" value="<?php echo $user_list['User']['id']; ?>">
      <?php echo $user_list['User']['handlename']; ?>
      </span>
      <?php $i++; ?>
    <?php } ?>
    </div>
  <?php } else { ?>
    <div class="alt_events">
      <p class="txt-min">参加者機能を利用するにはプロフィールから設定を変更してください。</p>
    </div>
  <?php } ?>
  
  <?php echo $this->Form->submit('登録する', array('div' => false, 'class' => 'submit')); ?>　<span class="txt-alt txt-b">*</span><span class="txt-min">は必須項目</span>
  <?php echo $this->Form->end(); ?><!-- form end -->

<h3>イベント一覧</h3>

  <?php echo $this->Paginator->numbers(array(
      'modulus' => 4, //現在ページから左右あわせてインクルードする個数
      'separator' => '|', //デフォルト値のセパレーター
      'first' => '＜', //先頭ページへのリンク
      'last' => '＞' //最終ページへのリンク
  )); ?>

  <table class="detail-list">
    <tr><th class="tbl-date">開催日<?php echo $this->Paginator->sort('date', '▼'); ?></th>
        <th>イベント名<?php echo $this->Paginator->sort('title', '▼'); ?></th>
        <th class="tbl-ico">種類<br>
                            状態</th>
        <th class="tbl-num">金額<br>
                            枚数</th>
        <th class="tbl-date">申込<br>開始日<?php echo $this->Paginator->sort('entry_start', '▼'); ?></th>
        <th class="tbl-date">入金<br>締切日<?php echo $this->Paginator->sort('payment_end', '▼'); ?></th>
        <th class="tbl-action">action</th></tr>
    
    <?php foreach ($event_lists AS $event_list) { ?>
    <tr><td class="tbl-date"><?php echo $event_list['Event']['date']; ?></td>
        <td><?php echo $event_list['Event']['title']; ?></td>
        <td class="tbl-ico"><span class="icon-genre col-event_<?php echo $event_list['Event']['genre_id']; ?>"><?php echo $event_list['EventGenre']['title']; ?></span>
                            <br><?php if ($event_list['Event']['status'] == 0) {echo '<span class="icon-genre">未定</span>';}
                                  elseif ($event_list['Event']['status'] == 1) {echo '<span class="icon-like">申込中</span>';}
                                  elseif ($event_list['Event']['status'] == 2) {echo '<span class="icon-true">確定</span>';}
                                  elseif ($event_list['Event']['status'] == 3) {echo '<span class="icon-false">落選</span>';} ?></td>
        <td class="tbl-num"><?php echo $event_list['Event']['amount']; ?>円<br>
                            <?php echo $event_list['Event']['number']; ?>枚</td>
        <td class="tbl-date"><?php echo $event_list['Event']['entry_start']; ?></td>
        <td class="tbl-date"><?php echo $event_list['Event']['payment_end']; ?></td>
        <td class="tbl-action"><span class="icon-button"><?php echo $this->Html->link('詳細', '/event/'.$event_list['Event']['id'], array('target' => '_blank')); ?></span>
            <?php if ($event_list['Event']['user_id'] == $this->Session->read('Auth.User.id')) { ?>
            <br><span class="icon-button"><?php echo $this->Html->link('修正', '/events/edit/'.$event_list['Event']['id']); ?></span>
            <span class="icon-button"><?php echo $this->Form->postLink('削除', array('action' => 'delete', $event_list['Event']['id']), null, '本当に削除しますか'); ?></span>
            <?php } ?></td></tr>
    <?php } ?>
  </table>

<div class="link-page_events">
  <span class="link-page"><?php echo $this->Html->link('⇨ 公開されている全てのイベントはこちら', '/events/all_lists/'); ?></span>
  <span class="link-page"><?php echo $this->Html->link('⇨ 過去のイベントはこちら', '/events/past_lists/'); ?></span>
</div>