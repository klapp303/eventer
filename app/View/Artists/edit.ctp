<?php echo $this->Html->css('artists', array('inline' => false)); ?>
<?php echo $this->element('searchbox', array(
    'controller' => 'artists',
    'value' => @$search_word,
    'placeholder' => 'アーティスト名 or カナ を入力'
)); ?>

<h3>出演者タグの編集</h3>

  <table class="PlaceAddForm">
    <?php echo $this->Form->create('Artist', array( //使用するModel
        'type' => 'put', //変更はput
        'action' => 'edit', //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
    )); ?><!-- form start -->

  <?php echo $this->Form->input('id', array('type' => 'hidden', 'value' => $id)); ?>

    <tr>
      <td><label>アーティスト名</label></td>
      <td><?php echo $this->Form->input('name', array('type' => 'text', 'label' => false, 'placeholder' => '例）竹達彩奈')); ?><span class="txt-alt txt-b">*</span></td>
    </tr>
    <tr>
      <td><label>アーティストカナ（全角カナ）</label></td>
      <td><?php echo $this->Form->input('kana', array('type' => 'text', 'label' => false, 'placeholder' => '例）タケタツアヤナ')); ?><span class="txt-alt txt-b">*</span></td>
    </tr>
    
    <tr>
      <td></td>
      <td><?php echo $this->Form->submit('修正する', array('div' => false, 'class' => 'submit')); ?>　　<span class="txt-alt txt-b">*</span><span class="txt-min">は必須項目</span></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>