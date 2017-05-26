<?php echo $this->Html->css('places', array('inline' => false)); ?>
<?php echo $this->Html->script('jquery-tmb', array('inline' => false)); ?>
<?php if (preg_match('#/places/edit/#', $_SERVER['REQUEST_URI'])): //編集用 ?>
<h3>会場の修正</h3>

  <table class="PlaceAddForm">
    <?php echo $this->Form->create('Place', array( //使用するModel
//        'type' => 'put', //変更はput
        'enctype' => 'multipart/form-data', //fileアップロードの場合
        'action' => 'edit', //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
    )); ?>
<?php else: //登録用 ?>
<h3><?php echo $sub_page; ?></h3>

  <table class="PlaceAddForm">
    <?php echo $this->Form->create('Place', array( //使用するModel
//        'type' => 'post', //デフォルトはpost送信
        'enctype' => 'multipart/form-data', //fileアップロードの場合
        'action' => 'add', //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
    )); ?>
<?php endif; ?><!-- form start -->

  <?php if (preg_match('#/places/edit/#', $_SERVER['REQUEST_URI'])): //編集用 ?>
  <?php echo $this->Form->input('id', array('type' => 'hidden', 'value' => $id)); ?>
  <?php else: //登録用 ?>
  <?php echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $userData['id'])); ?>
  <?php endif; ?>

    <tr>
      <td><label>会場名</label></td>
      <td><?php echo $this->Form->input('name', array('type' => 'text', 'label' => false, 'placeholder' => '通称で問題なし', 'disabled' => (preg_match('#/places/edit/#', $_SERVER['REQUEST_URI']))? 'disabled' : '')); ?><span class="txt-alt txt-b">*</span></td>
    </tr>
    <tr>
      <td><label>都道府県</label></td>
      <td><?php echo $this->Form->input('prefecture_id', array('type' => 'select', 'options' => $prefecture_lists, ((preg_match('#/places/edit/#', $_SERVER['REQUEST_URI']))? '' : 'selected') => 13 , 'label' => false, 'disabled' => (preg_match('#/places/edit/#', $_SERVER['REQUEST_URI']))? 'disabled' : '')); ?><span class="txt-alt txt-b">*</span></td>
    </tr>
    <tr>
      <td><label>収容人数</label></td>
      <td><?php echo $this->Form->input('capacity', array('type' => 'text', 'label' => false, 'placeholder' => '例）2000')); ?>人</td>
    </tr>
    <tr>
      <td><label>最寄り駅</label></td>
      <td><?php echo $this->Form->input('access', array('type' => 'text', 'label' => false, 'placeholder' => '例）東京', 'disabled' => (preg_match('#/places/edit/#', $_SERVER['REQUEST_URI']))? 'disabled' : '')); ?>駅<span class="txt-alt txt-b">*</span></td>
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
      <td><label>座席図</label></td>
      <td><?php if (preg_match('#/places/edit/#', $_SERVER['REQUEST_URI'])): //編集用 ?>
          <?php echo $this->Form->input('delete_name', array('type' => 'hidden', 'label' => false, 'value' => $image_name)); ?>
          <?php if (!$image_name) {
              $image_name = '../no_image.jpg';
          } ?>
          <?php echo $this->Html->image('../files/place/' . $image_name, array('class' => 'tmb-display js-tmb_pre')); ?>
          <?php endif; ?>
          <?php echo $this->Form->input('file', array('type' => 'file', 'label' => false)); ?></td>
    </tr>
    
    <tr>
      <td></td>
      <td><?php echo $this->Form->submit((preg_match('#/places/edit/#', $_SERVER['REQUEST_URI']))? '修正する' : '登録する', array('div' => false, 'class' => 'submit')); ?>　　<span class="txt-alt txt-b">*</span><span class="txt-min">は必須項目</span></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>