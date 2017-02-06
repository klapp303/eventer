<?php echo $this->Html->css('events', array('inline' => false)); ?>
<?php echo $this->Html->script('jquery-name_insert', array('inline' => false)); ?>
<?php echo $this->element('searchbox', array(
    'placeholder' => 'イベント名 を入力'
)); ?>

<?php if (preg_match('#/events/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
  <h3>イベントの編集</h3>
  
    <?php echo $this->Form->create('Event', array( //使用するModel
        'type' => 'put', //変更はput
        'action' => 'edit', //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
    )); ?>
<?php } else { //登録用 ?>
  <h3>イベントの登録</h3>
  
    <?php echo $this->Form->create('Event', array( //使用するModel
        'type' => 'post', //デフォルトはpost送信
        'action' => 'add', //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
    )); ?>
<?php } ?><!-- form start -->
  
  <?php if (preg_match('#/events/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
    <?php echo $this->Form->input('Event.id', array('type' => 'hidden')); ?>
  <?php } else { //登録用 ?>
    <?php echo $this->Form->input('Event.user_id', array('type' => 'hidden', 'value' => $userData['id'])); ?>
  <?php } ?>
  
  <div>
    <label>イベント名（全体）</label>
    <?php echo $this->Form->input('Event.title', array('type' => 'text', 'label' => false, 'size' => 25, 'placeholder' => '例）竹達彩奈 2ndALツアー', 'class' => 'js-insert_data')); ?><span class="txt-alt txt-b">*</span>
    <label>公開設定</label>
    <?php echo $this->Form->input('Event.publish', array('type' => 'select', 'label' => false, 'options' => array(1 => '公開', 0 => '非公開'))); ?><span class="txt-alt txt-b">*</span><br>
    <span class="txt-min">登録が1つの場合、イベント名（全体）と（各公演）は同じで構いません。</span>
    <button type="button" class="js-insert event-copy-button">イベント名をコピー</button>
  </div>
  
  <?php $form_max = 10;//フォーム数の最大を設定 ?>
  <?php for ($i = 0; $i < $form_max; $i++) { ?>
    <?php if (preg_match('#/events/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
      <?php echo $this->Form->input('EventsDetail.' . $i . '.id', array('type' => 'hidden')); ?>
    <?php } ?>
    
    <table id="event-add-form_<?php echo $i; ?>" class="event-add-form" style="display: <?php echo ($i < $form_min)? 'block' : 'none'; ?>;">
      <tr><td class="event-add-label">イベント名<br>（各公演）</td>
          <td class="event-add-input"><?php echo $this->Form->input('EventsDetail.' . $i . '.title', array('type' => 'text', 'label' => false, 'required' => ($i == 0)? true : false, 'size' => 20, 'placeholder' => '例）東京2日目、昼の部etc', 'class' => ($i == 0)? 'js-insert_area' : 'main_area_' . $i)); ?>
              <?php echo ($i == 0)? '<span class="txt-alt txt-b">*</span>' : ''; ?></td></tr>
      <tr><td class="event-add-label">種類</td>
          <td class="event-add-input"><?php echo $this->Form->input('EventsDetail.' . $i . '.genre_id', array('type' => 'select', 'label' => false, 'options' => $event_genres, 'class' => 'sub_area_' . $i,
              'disabled' => (preg_match('#/events/edit/#', $_SERVER['REQUEST_URI']) && @$requestData['EventsDetail'][$i]['title'])? '' : (($i == 0)? '' : 'disabled'))); ?>
              <?php echo ($i == 0)? '<span class="txt-alt txt-b">*</span>' : ''; ?></td></tr>
      <tr><td class="event-add-label">会場</td>
          <td class="event-add-input"><?php echo $this->Form->input('EventsDetail.' . $i . '.place_id', array('type' => 'select', 'label' => false, 'options' => $place_lists, 'class' => 'sub_area_' . $i,
              'disabled' => (preg_match('#/events/edit/#', $_SERVER['REQUEST_URI']) && @$requestData['EventsDetail'][$i]['title'])? '' : (($i == 0)? '' : 'disabled'))); ?>
              <?php echo ($i == 0)? '<span class="txt-alt txt-b">*</span>' : ''; ?></td></tr>
      <tr><td class="event-add-label">開催日</td>
          <td class="event-add-input"><?php echo $this->Form->input('EventsDetail.' . $i . '.date', array('type' => 'date', 'label' => false, 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y') +1, 'minYear' => 2015, 'class' => 'sub_area_' . $i,
              'disabled' => (preg_match('#/events/edit/#', $_SERVER['REQUEST_URI']) && @$requestData['EventsDetail'][$i]['title'])? '' : (($i == 0)? '' : 'disabled'))); ?>
              <?php echo ($i == 0)? '<span class="txt-alt txt-b">*</span>' : ''; ?></td></tr>
      <tr><td class="event-add-label">開場時刻</td>
          <td class="event-add-input"><?php echo $this->Form->input('EventsDetail.' . $i . '.time_open', array('type' => 'time', 'label' => false, 'timeFormat' => 24, 'class' => 'time_open_' . $i,
              'disabled' => (preg_match('#/events/edit/#', $_SERVER['REQUEST_URI']))? ((@$requestData['EventsDetail'][$i]['time_open_null'] == 1 || @!$requestData['EventsDetail'][$i]['title'])? 'disabled' : '') : 'disabled')); ?>
              <?php echo $this->Form->input('EventsDetail.' . $i . '.time_open_null', array('type' => 'checkbox', 'label' => false, 'class' => 'time_open_null_' . $i . ' sub_area_' . $i,
              'disabled' => (preg_match('#/events/edit/#', $_SERVER['REQUEST_URI']) && @$requestData['EventsDetail'][$i]['title'])? '' : (($i == 0)? '' : 'disabled'),
              'checked' => (preg_match('#/events/edit/#', $_SERVER['REQUEST_URI']))? ((@$requestData['EventsDetail'][$i]['time_open_null'] == 1 || @!$requestData['EventsDetail'][$i]['title'])? 'checked' : '') : 'checked')); ?><span class="txt-min">なし</span></td></tr>
      <tr><td class="event-add-label">開演時刻</td>
          <td class="event-add-input"><?php echo $this->Form->input('EventsDetail.' . $i . '.time_start', array('type' => 'time', 'label' => false, 'timeFormat' => 24, 'class' => 'time_start_' . $i,
              'disabled' => (preg_match('#/events/edit/#', $_SERVER['REQUEST_URI']))? ((@$requestData['EventsDetail'][$i]['time_start_null'] == 1 || @!$requestData['EventsDetail'][$i]['title'])? 'disabled' : '') : 'disabled')); ?>
              <?php echo $this->Form->input('EventsDetail.' . $i . '.time_start_null', array('type' => 'checkbox', 'label' => false, 'class' => 'time_start_null_' . $i . ' sub_area_' . $i,
              'disabled' => (preg_match('#/events/edit/#', $_SERVER['REQUEST_URI']) && @$requestData['EventsDetail'][$i]['title'])? '' : (($i == 0)? '' : 'disabled'),
              'checked' => (preg_match('#/events/edit/#', $_SERVER['REQUEST_URI']))? ((@$requestData['EventsDetail'][$i]['time_start_null'] == 1 || @!$requestData['EventsDetail'][$i]['title'])? 'checked' : '') : 'checked')); ?><span class="txt-min">なし</span></td></tr>
      <?php if ($i %2 != 0) { ?>
      <tr><td></td>
          <td>
            <?php if ($i < $form_max -1) { ?>
              <button type="button" id="event-add-button_<?php echo $i; ?>" class="fr">＋</button>
            <?php } ?>
            <?php if ($i > 2) { ?>
              <button type="button" id="event-remove-button_<?php echo $i; ?>" class="fr">ー</button>
            <?php } ?>
          </td></tr>
      <?php } ?>
    </table>
  <script>
      jQuery(function($) {
          //イベント名（各公演）がなければdisabled
          $('input:text.main_area_' + <?php echo $i; ?>).blur(function() {
              if (!$('.main_area_' + <?php echo $i; ?>).val()) { //公演名がない場合
                  $('.sub_area_' + <?php echo $i; ?>).attr('disabled', 'disabled');
                  $('.time_open_' + <?php echo $i; ?>).attr('disabled', 'disabled');
                  $('.time_start_' + <?php echo $i; ?>).attr('disabled', 'disabled');
              } else { //公演名がある場合
                  $('.sub_area_' + <?php echo $i; ?>).removeAttr('disabled').focus();
                  if (!$('.time_open_null_' + <?php echo $i; ?>).is(':checked')) {
                      $('.time_open_' + <?php echo $i; ?>).removeAttr('disabled').focus();
                  }
                  if (!$('.time_start_null_' + <?php echo $i; ?>).is(':checked')) {
                      $('.time_start_' + <?php echo $i; ?>).removeAttr('disabled').focus();
                  }
              }
          });
          
          //開催、開演時刻がなければdisabled
          $('.time_open_null_' + <?php echo $i; ?>).change(function(){
              if ($(this).is(':checked')) { //なしにチェックがある場合
                  $('.time_open_' + <?php echo $i; ?>).attr('disabled', 'disabled');
              } else { //なしにチェックがない場合
                  $('.time_open_' + <?php echo $i; ?>).removeAttr('disabled').focus();
              }
          });
          $('.time_start_null_' + <?php echo $i; ?>).change(function(){
              if ($(this).is(':checked')) { //なしにチェックがある場合
                  $('.time_start_' + <?php echo $i; ?>).attr('disabled', 'disabled');
              } else { //なしにチェックがない場合
                  $('.time_start_' + <?php echo $i; ?>).removeAttr('disabled').focus();
              }
          });
          
          //フォーム追加用
          <?php if ($i %2 != 0) { ?>
          $('#event-add-button_' + <?php echo $i; ?>).click(function() {
              $('#event-add-form_' + <?php echo $i +1; ?>).show();
              $('#event-add-form_' + <?php echo $i +2; ?>).show();
              
              $('#event-add-button_' + <?php echo $i; ?>).hide();
              $('#event-remove-button_' + <?php echo $i; ?>).hide();
          });
          $('#event-remove-button_' + <?php echo $i +2; ?>).click(function() {
              $('#event-add-form_' + <?Php echo $i +1; ?>).hide();
              $('#event-add-form_' + <?php echo $i +2; ?>).hide();
              
              $('#event-add-button_' + <?php echo $i; ?>).show();
              $('#event-remove-button_' + <?php echo $i; ?>).show();
          });
          <?php } ?>
      });
  </script>
  <?php } ?>
  
  <div class="cf" style="width: 740px;">
    <?php echo $this->Form->submit((preg_match('#/events/edit/#', $_SERVER['REQUEST_URI']))? '編集する' : '登録する', array('div' => false, 'id' => 'event-post-button', 'class' => 'submit')); ?>　<span class="txt-alt txt-b">*</span><span class="txt-min">は必須項目</span>
  </div>
  <script>
      jQuery(function($) {
          //postする時にフォームがどこまで追加されていたかを取得しておく
          $('#event-post-button').click(function() {
              for (var i = 0; i < 10; i++) {
                  var display = $('#event-add-form_' + i).css('display');
                  if (display == 'none') {
                      $('<input>').attr({
                          type: 'hidden', name: 'form_count', value: i
                      }).appendTo('#event-add-form_0');
                      break;
                  }
                  if (i == <?php echo $form_max -1; ?>) {
                      $('<input>').attr({
                          type: 'hidden', name: 'form_count', value: i + 1
                      }).appendTo('#event-add-form_0');
                      break;
                  }
              }
          });
      });
  </script>
  <?php echo $this->Form->end(); ?><!-- form end -->

<h3>イベント一覧</h3>

  <?php echo $this->Paginator->numbers($paginator_option + array('paramType' => 'querystring')); ?>

  <table class="detail-list event-list">
    <tr><th class="tbl-date">開催日<?php echo $this->Paginator->sort('EventsDetail.date', '▼'); ?></th>
        <th>イベント名<?php echo $this->Paginator->sort('Event.title', '▼'); ?></th>
        <th class="tbl-genre">種類<br>
                              状態</th>
        <th class="tbl-act">action</th></tr>
    
    <?php foreach ($event_lists as $event_list) { ?>
      <tr><td class="tbl-date"><?php echo date('Y/m/d(' . $week_lists[date('w', strtotime($event_list['EventsDetail']['date']))] . ')', strtotime($event_list['EventsDetail']['date'])); ?></td>
          <td><?php echo $event_list['Event']['title']; ?>
              <?php if ($event_list['Event']['title'] != $event_list['EventsDetail']['title']) { ?><br>
                <span class="title-sub"><?php echo $event_list['EventsDetail']['title']; ?></span>
              <?php } ?>
          </td>
          <td class="tbl-genre"><span class="icon-genre col-event_<?php echo $event_list['EventsDetail']['genre_id']; ?>"><?php echo $event_list['EventGenre']['title']; ?></span><br>
                                <?php foreach ($eventEntryStatus as $entry_status) {
                                    if ($entry_status['status'] == $event_list['EventsDetail']['status']) {
                                        echo '<span class="icon-' . $entry_status['class'] . '">' . $entry_status['name'] . '</span>';
                                        break;
                                    }
                                } ?></td>
          <td class="tbl-act"><span class="icon-button"><?php echo $this->Html->link('詳細', '/events/' . $event_list['EventsDetail']['id'], array('target' => '_blank')); ?></span>
                              <?php if ($event_list['EventsDetail']['user_id'] == $userData['id']) { ?>
                                <br><span class="icon-button"><?php echo $this->Html->link('修正', '/events/edit/' . $event_list['Event']['id']); ?></span>
                                <span class="icon-button"><?php echo $this->Form->postLink('削除', array('action' => 'delete', $event_list['EventsDetail']['id']), null, ($event_list['Event']['title'] != $event_list['EventsDetail']['title'])? $event_list['Event']['title'] . ' の
' . $event_list['EventsDetail']['title'] . ' を本当に削除しますか' : $event_list['EventsDetail']['title'] . ' を本当に削除しますか'); ?></span>
                              <?php } ?></td></tr>
    <?php } ?>
  </table>
  
  <?php echo $this->Paginator->numbers($paginator_option + array('paramType' => 'querystring')); ?>

<div class="link-page_events">
  <span class="link-page"><?php echo $this->Html->link('⇨ 公開されている全てのイベントはこちら', '/events/all_lists/'); ?></span>
  <span class="link-page"><?php echo $this->Html->link('⇨ 過去のイベントはこちら', '/events/past_lists/'); ?></span>
</div>