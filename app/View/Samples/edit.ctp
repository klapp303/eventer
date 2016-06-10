<!-- 未使用 -->
<h3>サンプルの修正</h3>

  <?php echo $this->Form->create('Sample', array( //使用するModel
      'type' => 'put', //変更はput
      'action' => 'edit', //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
  )); ?><!-- form start -->
  
  <?php echo $this->Form->input('id', array('type' => 'hidden', 'value' => $id)); ?>
  <?php echo $this->Form->input('title', array('type' => 'text', 'label' => 'タイトル')); ?><br>
  <?php echo $this->Form->input('date', array('type' => 'date', 'label' => '日付', 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y') +1, 'minYear' => 2015)); ?><br>
  <?php echo $this->Form->input('amount', array('type' => 'text', 'label' => '数値')); ?><br>
  <?php echo $this->Form->input('status', array('type' => 'select', 'label' => '状態', 'options' => array(0 => '未定', 1 => '確定'))); ?><br>
  
  <?php echo $this->Form->submit('修正する'); ?>
  <?php echo $this->Form->end(); ?><!-- form end -->

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