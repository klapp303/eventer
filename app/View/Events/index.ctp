<?php echo $this->Html->css('events', array('inline' => false)); ?>
<?php echo $this->element('searchbox', array(
    'placeholder' => 'イベント名 を入力'
)); ?>

<?php if (preg_match('#/events/edit/#', $_SERVER['REQUEST_URI'])): //編集用 ?>
<h3>イベントの編集</h3>

  <?php echo $this->Form->create('Event', array( //使用するModel
      'type' => 'put', //変更はput
      'action' => 'edit', //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
  )); ?>
<?php else: //登録用 ?>
<h3>イベントの登録</h3>

  <?php echo $this->Form->create('Event', array( //使用するModel
      'type' => 'post', //デフォルトはpost送信
      'action' => 'add', //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
  )); ?>
<?php endif; ?><!-- form start -->
  
  <?php if (preg_match('#/events/edit/#', $_SERVER['REQUEST_URI'])): //編集用 ?>
  <?php echo $this->Form->input('Event.id', array('type' => 'hidden')); ?>
  <?php else: //登録用 ?>
  <?php echo $this->Form->input('Event.user_id', array('type' => 'hidden', 'value' => $userData['id'])); ?>
  <?php endif; ?>
  
  <div>
    <label>イベント名（全体）</label>
    <?php echo $this->Form->input('Event.title', array('type' => 'text', 'label' => false, 'size' => 25, 'placeholder' => '例）竹達彩奈 1stツアー「Colore Serenata」', 'class' => 'js-insert_data')); ?><span class="txt-alt txt-b">*</span>
    <label>公開設定</label>
    <?php echo $this->Form->input('Event.publish', array('type' => 'select', 'label' => false, 'options' => array(1 => '公開', 0 => '非公開'))); ?><span class="txt-alt txt-b">*</span><br>
    <span class="txt-min">登録が1つの場合、イベント名（全体）と（各公演）は同じで構いません。</span>
    <button type="button" class="js-insert event-copy-button">イベント名をコピー</button>
  </div>
  <script>
      jQuery(function($) {
          $.fn.extend({
              insertAtCaret: function(v) {
                  var o = this.get(0);
                  o.focus();
                  if ($.browser.msie) {
//                  if ($.support.noCloneEvent) {
                      var r = document.selection.createRange();
                      r.text = v;
                      r.select();
                  } else {
                      var s = o.value;
                      var p = o.selectionStart;
                      var np = p + v.length;
                      o.value = s.substr(0, p) + v + s.substr(p);
                      o.setSelectionRange(np, np);
                  }
              }
          });
          
          $('.js-insert').click(function() {
//              var img_name = $(this).attr('data');
              var title_name = $('.js-insert_data').val();
              if (!title_name) {
                  alert('イベント名（全体）を入力してください');
                  return false;
              }
              $('.js-insert_area').insertAtCaret(title_name);
          });
      });
  </script>
  
  <?php $form_max = 20; //フォーム数の最大を設定 ?>
  <?php for ($i = 0; $i < $form_max; $i++): ?>
    <?php if (preg_match('#/events/edit/#', $_SERVER['REQUEST_URI'])): //編集用 ?>
    <?php echo $this->Form->input('EventsDetail.' . $i . '.id', array('type' => 'hidden')); ?>
    <?php endif; ?>
  
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
        <td class="event-add-input"><?php echo $this->Form->input('EventsDetail.' . $i . '.date', array('type' => 'date', 'label' => false, 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y') +1, 'minYear' => $minYearKey, 'class' => 'sub_area_' . $i,
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
    <?php if ($i %2 != 0): ?>
    <tr><td></td>
        <td>
          <?php if ($i < $form_max -1): ?>
          <button type="button" id="event-add-button_<?php echo $i; ?>" class="fr">＋</button>
          <?php endif; ?>
          <?php if ($i > 2): ?>
          <button type="button" id="event-remove-button_<?php echo $i; ?>" class="fr">ー</button>
          <?php endif; ?>
        </td></tr>
    <?php endif; ?>
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
          $('.time_open_null_' + <?php echo $i; ?>).change(function() {
              if ($(this).is(':checked')) { //なしにチェックがある場合
                  $('.time_open_' + <?php echo $i; ?>).attr('disabled', 'disabled');
              } else { //なしにチェックがない場合
                  $('.time_open_' + <?php echo $i; ?>).removeAttr('disabled').focus();
              }
          });
          $('.time_start_null_' + <?php echo $i; ?>).change(function() {
              if ($(this).is(':checked')) { //なしにチェックがある場合
                  $('.time_start_' + <?php echo $i; ?>).attr('disabled', 'disabled');
              } else { //なしにチェックがない場合
                  $('.time_start_' + <?php echo $i; ?>).removeAttr('disabled').focus();
              }
          });
          
          //フォーム追加用
          <?php if ($i %2 != 0): ?>
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
          <?php endif; ?>
      });
  </script>
  <?php endfor; ?>
  
  <div class="cf" style="width: 740px;">
    <?php echo $this->Form->submit((preg_match('#/events/edit/#', $_SERVER['REQUEST_URI']))? '編集する' : '登録する', array('div' => false, 'id' => 'event-post-button', 'class' => 'submit')); ?>　<span class="txt-alt txt-b">*</span><span class="txt-min">は必須項目</span>
  </div>
  <script>
      jQuery(function($) {
          //postする時にフォームがどこまで追加されていたかを取得しておく
          $('#event-post-button').click(function() {
              for (var i = 0; i < <?php echo $form_max; ?>; i++) {
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

  <?php echo $this->element('eventer_eventlist'); ?>
  
  <?php echo $this->Paginator->numbers($paginator_option + array('paramType' => 'querystring')); ?>

<div class="link-right">
  <span class="link-page"><?php echo $this->Html->link('⇨ 公開されている全てのイベントはこちら', '/events/all_lists/'); ?></span>
  <span class="link-page"><?php echo $this->Html->link('⇨ 過去のイベントはこちら', '/events/past_lists/'); ?></span>
</div>