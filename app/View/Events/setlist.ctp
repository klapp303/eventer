<?php echo $this->Html->css('events', array('inline' => false)); ?>
<?php echo $this->Html->css('jquery-ui.min.css', array('inline' => false)); ?>
<?php echo $this->Html->script('jquery-ui.min.js', array('inline' => false)); ?>
<h3>セットリストの管理</h3>

  <?php if (!empty($this->request->data)): //編集用 ?>
  <?php echo $this->Form->create('Events', array( //使用するModel
      'type' => 'put', //変更はput
      'action' => 'setlist', //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
  )); ?>
  <?php else: //登録用 ?>
  <?php echo $this->Form->create('Events', array( //使用するModel
      'type' => 'post', //デフォルトはpost送信
      'action' => 'setlist', //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
  )); ?>
  <?php endif; ?><!-- form start -->
  
  <?php echo $this->Form->input('Event.event_id', array('type' => 'hidden', 'value' => $events_detail['EventsDetail']['event_id'])); ?>
  <?php echo $this->Form->input('Event.events_detail_id', array('type' => 'hidden', 'value' => $events_detail['EventsDetail']['id'])); ?>
  
  <table>
    <tr><td></td><td>曲名</td><td>アーティスト名</td></tr>
    
    <?php $form_min = 30;//フォーム数の最小を設定 ?>
    <?php for ($i = 0; $i < $form_min *2; $i++): ?>
    <?php echo $this->Form->input('EventSetlist.' . $i . '.id', array('type' => 'hidden')); ?>
    <tr class="<?php echo ($i < $form_min)? '' : 'setlist-add-form'; ?>" style="display: <?php echo ($i < $form_min)? 'table-row' : 'none'; ?>;">
      <td><?php echo sprintf('%02d', $i +1) . '.'; ?></td>
      <td><?php echo $this->Form->input('EventSetlist.' . $i . '.title', array('type' => 'text', 'label' => false, 'required' => ($i == 0)? true : false, 'class' => 'setlist_title', 'size' => 30)); ?></td>
      <?php $array_cast = ['' => ''] + $array_cast; ?>
      <td><?php echo $this->Form->input('EventSetlist.' . $i . '.artist_id', array('type' => 'select', 'label' => false, 'options' => $array_cast, 'style' => 'width: 200px;')); ?></td>
    </tr>
    <?php if ($i == $form_min -1): ?>
    <tr><td></td><td></td><td><button type="button" id="setlist-add-button" class="fr">＋</button></td></tr>
    <?php endif; ?>
    <?php if ($i == $form_min *2 -1): ?>
    <tr><td></td><td></td><td><button type="button" id="setlist-remove-button" class="fr" style="display: none;">ー</button></td></tr>
    <?php endif; ?>
    <?php endfor; ?>
  </table>
  <script>
      jQuery(function($) {
          $('#setlist-add-button').click(function() {
              $('.setlist-add-form').show();
              $('#setlist-remove-button').show();
              $('#setlist-add-button').hide();
          });
          $('#setlist-remove-button').click(function() {
              $('.setlist-add-form').hide();
              $('#setlist-add-button').show();
              $('#setlist-remove-button').hide();
          });
          
          //autocomplete
          $(function() {
              var data = JSON.parse('<?php echo $music_lists; ?>');
              $('.setlist_title').autocomplete({
                  source : data,
                  autoFocus : true,
                  delay : 500,
                  minLength : 3
              });
          });
      });
  </script>
  
  <div class="cf" style="width: 800px;">
    <?php echo $this->Form->submit('登録する', array('div' => false, 'class' => 'submit')); ?>
  </div>
  
  <?php echo $this->Form->end(); ?><!-- form end -->

<div class="link-right">
  <span class="link-page"><?php echo $this->Html->link('⇨ イベント詳細ページに戻る', '/events/' . $events_detail['EventsDetail']['id']); ?></span>
</div>