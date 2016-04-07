<?php echo $this->Html->css('events', array('inline' => FALSE)); ?>
<?php if (preg_match('#/events/entry_edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
<h3>エントリーの編集</h3>

  <?php echo $this->Form->create('Events', array( //使用するModel
      'type' => 'put', //変更はput
      'action' => 'entry_edit', //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
      )
  ); ?>
<?php } else { //登録用 ?>
<h3>エントリーの登録</h3>

  <?php echo $this->Form->create('Events', array( //使用するModel
      'type' => 'post', //デフォルトはpost送信
      'action' => 'entry_add', //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
      )
  ); ?>
<?php } ?><!-- form start -->
  
  <?php if (preg_match('#/events/entry_edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
    <?php echo $this->Form->input('EventsEntry.id', array('type' => 'hidden')); ?>
    <?php echo $this->Form->input('EventsEntry.events_detail_id', array('type' => 'hidden')); ?>
  <?php } else { //登録用 ?>
    <?php echo $this->Form->input('EventsEntry.event_id', array('type' => 'hidden', 'value' => $events_detail['EventsDetail']['event_id'])); ?>
    <?php echo $this->Form->input('EventsEntry.events_detail_id', array('type' => 'hidden', 'value' => $events_detail['EventsDetail']['id'])); ?>
    <?php echo $this->Form->input('EventsEntry.user_id', array('type' => 'hidden', 'value' => $userData['id'])); ?>
    <?php echo $this->Form->input('EventsEntry.date_event', array('type' => 'hidden', 'value' => ($events_detail['EventsDetail']['time_start'])? $events_detail['EventsDetail']['date'].' '.$events_detail['EventsDetail']['time_start']: $events_detail['EventsDetail']['date'])); ?>
  <?php } ?>
  
  <table>
    <tr><td>エントリー名</td>
        <td><?php echo $this->Form->input('EventsEntry.title', array('type' => 'text', 'label' => false, 'required' => true, 'size' => 20, 'placeholder' => '例）FC先行、一般etc')); ?><span class="txt-alt txt-b">*</span></td></tr>
    <tr><td>種類</td>
        <td><?php echo $this->Form->input('EventsEntry.entries_genre_id', array('type' => 'select', 'label' => false, 'options' => $entry_genres)); ?><span class="txt-alt txt-b">*</span></td></tr>
    <tr><td>価格</td>
        <td><?php echo $this->Form->input('EventsEntry.price', array('type' => 'text', 'label' => false, 'size' => 18)); ?>円</td></tr>
    <tr><td>枚数</td>
        <td><?php echo $this->Form->input('EventsEntry.number', array('type' => 'text', 'label' => false, 'size' => 18)); ?>枚</td></tr>
    
    <?php foreach ($entryDateColumn AS $key => $column) { ?>
    <tr><td><?php echo $key; ?></td>
        <td><?php echo $this->Form->input('EventsEntry.'.$column, array('type' => 'datetime', 'label' => false, 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y')+1, 'minYear' => 2015, 'timeFormat' => 24, 'class' => $column,
            'disabled' => (preg_match('#/events/entry_edit/#', $_SERVER['REQUEST_URI']))? ((@$requestData['EventsEntry'][$column.'_null'] == 1)? 'disabled': ''): 'disabled')); ?>
            <?php echo $this->Form->input('EventsEntry.'.$column.'_null', array('type' => 'checkbox', 'label' => false, 'class' => $column.'_null',
            'checked' => (preg_match('#/events/entry_edit/#', $_SERVER['REQUEST_URI']))? ((@$requestData['EventsEntry'][$column.'_null'] == 1)? 'checked': ''): 'checked')); ?><span class="txt-min">なし</span></td></tr>
    <script>
      jQuery(function($) {
          $('.' + '<?php echo $column; ?>' + '_null').change(function(){
            if ($(this).is(':checked')) {
              $('.' + '<?php echo $column; ?>').attr('disabled','disabled');
            } else {
              $('.' + '<?php echo $column; ?>').removeAttr('disabled').focus();
            }
          });
      });
    </script>
    <?php } ?>
    
    <tr><td>支払方法</td>
        <td><?php echo $this->Form->input('EventsEntry.payment', array('type' => 'select', 'label' => false, 'options' => array('' => '', 'credit' => 'クレジットカード', 'conveni' => 'コンビニ支払', 'delivery' => '代金引換', 'buy' => '買取', 'other' => 'その他'))); ?></td></tr>
    <tr><td>種類</td>
        <td><?php echo $this->Form->input('EventsEntry.status', array('type' => 'select', 'label' => false, 'options' => array(0 => '検討中', 1 => '申込中', 2 => '当選', 3 => '落選', 4 => '見送り'))); ?><span class="txt-alt txt-b">*</span></td></tr>
  </table>
  
  <div class="cf" style="width: 800px;">
    <?php echo $this->Form->submit((preg_match('#/events/entry_edit/#', $_SERVER['REQUEST_URI']))? '編集する': '登録する', array('div' => false, 'class' => 'submit')); ?>　<span class="txt-alt txt-b">*</span><span class="txt-min">は必須項目</span>
  </div>
  
  <?php echo $this->Form->end(); ?><!-- form end -->

<div class="link-page_events">
  <span class="link-page"><?php echo $this->Html->link('⇨ イベント詳細ページに戻る', '/event/'.$events_detail_id); ?></span>
</div>