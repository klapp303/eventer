<?php echo $this->Html->css('events', array('inline' => false)); ?>
<?php echo $this->element('searchbox', array(
    'controller' => strtolower($this->name),
    'action' => strtolower($this->action),
    'params_id' => @$this->params['pass'][0],
    'placeholder' => 'イベント名 を入力'
)); ?>
<h3><?php echo $sub_page; ?></h3>

<?php if (@$description): ?>
<div class="intro intro_events">
  <P><?php echo $description; ?></P>
</div>
<?php endif; ?>

  <?php if (@$this->params['pass'][0]) {
      $this->Paginator->options(array('url' => $this->params['pass'][0]));
  } ?>
  <?php echo $this->Paginator->numbers($paginator_option); ?>
  
  <?php echo $this->element('eventer_eventlist'); ?>
  
  <?php echo $this->Paginator->numbers($paginator_option); ?>

<?php if (@$page_link): ?>
<div class="link-right">
  <?php foreach ($page_link as $val): ?>
  <span class="link-page"><?php echo $this->Html->link('⇨ ' . $val['title'], $val['url']); ?></span>
  <?php endforeach; ?>
</div>
<?php endif; ?>