<?php echo $this->Html->css('artists', array('inline' => false)); ?>
<?php echo $this->Html->script('jquery-tmb', array('inline' => false)); ?>
<h3>出演者タグの編集</h3>

  <table class="PlaceAddForm">
    <?php echo $this->Form->create('Artist', array( //使用するModel
        'type' => 'put', //変更はput
        'enctype' => 'multipart/form-data', //fileアップロードの場合
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
      <td><label>画像</label></td>
      <td><?php echo $this->Form->input('delete_name', array('type' => 'hidden', 'label' => false, 'value' => $image_name)); ?>
          <?php if (!$image_name) {
              $image_name = '../no_image.jpg';
          } ?>
          <?php echo $this->Html->image('../files/artist/' . $image_name, array('class' => 'img_artist js-tmb_pre')); ?>
          <?php echo $this->Form->input('file', array('type' => 'file', 'label' => false)); ?></td>
    </tr>
    <tr>
      <td><label>公式サイト</label></td>
      <td><?php echo $this->Form->input('link_urls', array('type' => 'text', 'label' => false)); ?></td>
    </tr>
    <tr>
      <td><label>関連アーティスト</label></td>
      <td><?php echo $this->Form->input('related_artists_id', array('type' => 'text', 'label' => false)); ?></td>
    </tr>
    
    <tr>
      <td></td>
      <td><?php echo $this->Form->submit('修正する', array('div' => false, 'class' => 'submit')); ?>　　<span class="txt-alt txt-b">*</span><span class="txt-min">は必須項目</span></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>

<div class="link-page_artists">
  <span class="link-page"><?php echo $this->Html->link('⇨ アーティストの詳細に戻る', '/artists/artist_detail/' . $id); ?></span>
</div>