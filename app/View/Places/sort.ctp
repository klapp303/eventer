<?php echo $this->Html->css('places', array('inline' => false)); ?>
<?php echo $this->Html->script('http://code.jquery.com/ui/1.11.3/jquery-ui.js', array('inline' => false)); ?>
<h3><?php echo $sub_page; ?></h3>

<div class="intro intro_places">
  <P>
    ドラッグ&ドロップでイベント登録時の会場リストを並び替える事ができます。<br>
    ここでの並び順はイベント登録時の会場選択肢の並び順にも反映されます。<br>
    並び替えの結果は全ユーザに適用されます。
  </P>
</div>

  <?php echo $this->Form->create('Place', array( //使用するModel
      'type' => 'post', //デフォルトはpost送信
      'action' => 'sort', //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
  )); ?><!-- form start -->
  
  <ul class="sortable sort-list_palce">
  <?php $i = 0; ?>
  <?php foreach ($place_lists as $place) { ?>
    <?php $i++; ?>
    <li id="<?php echo $i; ?>">
      <?php echo $this->Form->input('Place.' . $i . '.id', array('type' => 'hidden', 'label' => false, 'value' => $place['Place']['id'])); ?>
      <?php echo $place['Place']['name']; ?>
      <span class="icon-button fr"><?php echo $this->Html->link('詳細', '/places/place_detail/' . $place['Place']['id'], array('target' => '_blank')); ?></span>
    </li>
  <?php } ?>
  </ul>
  
  <?php echo $this->Form->submit('変更する', array('div' => false, 'class' => 'sort-btn_place')); ?>
  <?php echo $this->Form->end(); ?><!-- form end -->

<script>
    $(function() {
        $('.sortable').sortable();
        $('.sortable').disableSelection();
    });
</script>

<div class="link-right">
  <span class="link-page"><?php echo $this->Html->link('⇨ 会場一覧に戻る', '/places/place_lists/'); ?></span>
</div>