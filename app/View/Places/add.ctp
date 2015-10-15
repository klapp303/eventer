<?php echo $this->Html->css('places', array('inline' => FALSE)); ?>
<h3>会場の登録</h3>

  <table class="PlaceAddForm">
    <?php echo $this->Form->create('Place', array( //使用するModel
        'type' => 'post', //デフォルトはpost送信
        'action' => 'add', //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
        )
    ); ?><!-- form start -->
    <?php echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.id'))); ?>
    <tr>
      <td><label>会場名</label></td>
      <td><?php echo $this->Form->input('name', array('type' => 'text', 'label' => false, 'placeholder' => '通称で問題なし')); ?><span class="txt-alt txt-b">*</span></td>
    </tr>
    <tr>
      <td><label>収容人数</label></td>
      <td><?php echo $this->Form->input('capacity', array('type' => 'text', 'label' => false, 'placeholder' => '例）2000')); ?>人<span class="txt-alt txt-b">*</span></td>
    </tr>
    <tr>
      <td><label>最寄り駅</label></td>
      <td><?php echo $this->Form->input('access', array('type' => 'text', 'label' => false, 'placeholder' => '例）東京')); ?>駅<span class="txt-alt txt-b">*</span></td>
    </tr>
    <tr>
      <td><label>公式サイト</label></td>
      <td><?php echo $this->Form->input('url', array('type' => 'text', 'label' => false, 'placeholder' => 'リンクURLを登録できます')); ?></td>
    </tr>
    
    <tr>
      <td><label>緯度</label></td>
      <td><?php echo $this->Form->input('latitude', array('type' => 'text', 'label' => false, 'placeholder' => '例）35.6813818')); ?><span class="txt-min">（GoogleMapに使用）</span></td>
    </tr>
    <tr>
      <td><label>経度</label></td>
      <td><?php echo $this->Form->input('longitude', array('type' => 'text', 'label' => false, 'placeholder' => '例）139.7660838')); ?><span class="txt-min">（GoogleMapに使用）</span></td>
    </tr>
    
    <tr>
      <td></td>
      <td><?php echo $this->Form->submit('登録する', array('div' => false, 'class' => 'submit')); ?>　　<span class="txt-alt txt-b">*</span><span class="txt-min">は必須項目</span></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>