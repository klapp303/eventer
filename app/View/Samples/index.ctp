<h3>サンプルの登録</h3>

  <table>
    <?php if (preg_match('#/samples/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
      <?php echo $this->Form->create('Sample', array( //使用するModel
          'type' => 'put', //変更はput
          'action' => 'edit', //Controllerのactionを指定
          'inputDefaults' => array('div' => '')
      )); ?>
      <?php echo $this->Form->input('id', array('type' => 'hidden', 'value' => $id)); ?>
    <?php } else { //登録用 ?>
      <?php echo $this->Form->create('Sample', array( //使用するModel
          'type' => 'post', //デフォルトはpost送信
          'action' => 'add', //Controllerのactionを指定
          'inputDefaults' => array('div' => '')
      )); ?>
    <?php } ?><!-- form start -->
    
    <tr>
      <td>タイトル</td>
      <td><?php echo $this->Form->input('title', array('type' => 'text', 'label' => false)); ?></td>
    </tr>
    <tr>
      <td>日付</td>
      <td><?php echo $this->Form->input('date', array('type' => 'date', 'label' => false, 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y') +1, 'minYear' => 2015)); ?></td>
    </tr>
    <tr>
      <td>数値</td>
      <td><?php echo $this->Form->input('amount', array('type' => 'text', 'label' => false)); ?></td>
    </tr>
    <tr>
      <td>状態</td>
      <td><?php echo $this->Form->input('status', array('type' => 'select', 'label' => false, 'options' => array(0 => '未定', 1 => '確定'))); ?></td>
    </tr>
    
    <tr>
      <td></td>
      <td><?php if (preg_match('#/samples/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
            <?php echo $this->Form->submit('修正する'); ?>
          <?php } else { //登録用 ?>
            <?php echo $this->Form->submit('登録する'); ?>
          <?php } ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>

<h3>サンプル一覧</h3>

  <?php echo $this->Paginator->numbers($paginator_option); ?>

  <table class="detail-list">
    <tr><th>日付<?php echo $this->Paginator->sort('date', '▼'); ?></th>
        <th>タイトル</th>
        <th class="tbl-num">数値</th>
        <th class="tbl-ico">状態</th>
        <th>action</th></tr>
    
    <?php foreach ($sample_lists as $sample_list) { ?>
      <tr><td><?php echo $sample_list['Sample']['date']; ?></td>
          <td><?php echo $sample_list['Sample']['title']; ?></td>
          <td class="tbl-num"><?php echo $sample_list['Sample']['amount']; ?></td>
          <td class="tbl-ico"><?php if ($sample_list['Sample']['status'] == 0) { ?>
                                <span class="icon-false">未定</span>
                              <?php } elseif ($sample_list['Sample']['status'] == 1) { ?>
                                <span class="icon-true">確定</span>
                              <?php } ?></td>
          <td><?php echo $this->Html->link('修正', '/samples/edit/' . $sample_list['Sample']['id']); ?>
              <?php echo $this->Form->postLink('削除', array('action' => 'deleted', $sample_list['Sample']['id']), null, '本当に#' . $sample_list['Sample']['id'] . 'を削除しますか'); ?></td></tr>
    <?php } ?>
  </table>