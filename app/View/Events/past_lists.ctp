<?php echo $this->Html->script('jquery-hide', array('inline' => false)); ?>
<?php echo $this->Html->css('events', array('inline' => false)); ?>
<?php echo $this->element('searchbox', array(
    'action' => 'past_lists',
    'placeholder' => 'イベント名 を入力'
)); ?>
<button class="js-show js-hide-button fr cf">未対応のみを表示する</button>
<button class="js-hide js-show-button fr cf">過去すべてを表示する</button>
<h3><?php echo $sub_page; ?></h3>

<div class="intro intro_events">
  <P>
    過去に行われたイベントの一覧になります。<br>
    <br>
    右上のボタンから当落見送りの結果が登録されていないイベントのみを<br>
    表示する事ができます。
  </P>
</div>

  <div class="js-show">
  <?php echo $this->Paginator->numbers($paginator_option); ?>

  <?php echo $this->element('eventer_eventlist'); ?>
  
  <?php echo $this->Paginator->numbers($paginator_option); ?>
  </div>

  <?php if (count($event_undecided_lists) > 0) { ?>
    <div class="tbl-event_lists js-hide">
      <?php echo $this->element('eventer_eventlist', array(
          'event_lists' => $event_undecided_lists,
          'paginator' => false
      )); ?>
    </div>
  <?php } else { ?>
    <div class="intro js-hide">
      <P>未対応のイベントはありません。</P>
    </div>
  <?php } ?>

<div class="link-right">
  <span class="link-page"><?php echo $this->Html->link('⇨ 公開されている全てのイベントはこちら', '/events/all_lists/'); ?></span>
</div>