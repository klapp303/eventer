<h1>
  <span class="head-title"><?php echo $this->Html->link('イベンターに幸あれ', '/'); ?></span>
  <?php if ($this->Session->read('Auth.User.handlename')) { ?>
    <?php $handlename = $this->Session->read('Auth.User.handlename'); ?>
    <div class="head-msg fr">
      <span class="head-welcome">ようこそ</span>
      <span class="head-handlename"><?php echo $this->Html->link($handlename, '#'); ?></span>
      <span class="head-welcome">さん</span>
    </div>
  <?php } ?>
</h1>