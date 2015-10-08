<h3>イベントの登録</h3>

  <?php echo $this->Form->create('Event', array( //使用するModel
      'type' => 'post', //デフォルトはpost送信
      'action' => 'add', //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
      )
  ); ?><!-- form start -->
  <?php echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => '')); ?>
  <?php echo $this->Form->input('title', array('type' => 'text', 'label' => 'イベント名')); ?><br>
  <?php echo $this->Form->input('genre_id', array('type' => 'select', 'label' => '種類', 'options' => $event_genres)); ?><br>
  <?php echo $this->Form->input('date', array('type' => 'date', 'label' => '開催日', 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y')+1, 'minYear' => 2015)); ?><br>
  <?php echo $this->Form->input('time_start', array('type' => 'text', 'label' => '開催時刻')); ?><br>
  <?php echo $this->Form->input('amount', array('type' => 'text', 'label' => '金額')); ?><br>
  <?php echo $this->Form->input('number', array('type' => 'text', 'label' => '枚数')); ?><br>
  <?php echo $this->Form->input('entry_id', array('type' => 'select', 'label' => '申込方法', 'options' => $entry_genres)); ?><br>
  <?php echo $this->Form->input('date', array('type' => 'date', 'label' => '申込開始日', 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y')+1, 'minYear' => 2015)); ?><br>
  <?php echo $this->Form->input('date', array('type' => 'date', 'label' => '申込終了日', 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y')+1, 'minYear' => 2015)); ?><br>
  <?php echo $this->Form->input('date', array('type' => 'date', 'label' => '当落発表日', 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y')+1, 'minYear' => 2015)); ?><br>
  <?php echo $this->Form->input('date', array('type' => 'date', 'label' => '入金締切日', 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y')+1, 'minYear' => 2015)); ?><br>
  <?php echo $this->Form->input('status', array('type' => 'select', 'label' => '状態', 'options' => array(0 => '未定', 1 => '申込中', 2 => '確定'))); ?><br>
  
  <?php echo $this->Form->submit('登録する'); ?>
  <?php echo $this->Form->end(); ?><!-- form end -->

<h3>イベント一覧</h3>

  <?php echo $this->Paginator->numbers(array(
      'modulus' => 4, //現在ページから左右あわせてインクルードする個数
      'separator' => '|', //デフォルト値のセパレーター
      'first' => '＜', //先頭ページへのリンク
      'last' => '＞' //最終ページへのリンク
  )); ?>

  <table class="detail-list">
    <tr><th>開催日</th><th>イベント名</th><th class="tbl-ico">種類</th><th class="tbl-num">金額</th><th class="tbl-num">枚数</th><th class="tbl-ico">状態</th><th>申込方法</th><th>申込開始日</th><th>申込終了日</th><th>当落発表日</th><th>入金締切日</th><th>開催時刻</th><th>action</th></tr>
    <?php for($i = 0; $i < $event_counts; $i++){ ?>
    <tr><td><?php echo $event_lists[$i]['Event']['date']; ?></td>
        <td><?php echo $event_lists[$i]['Event']['title']; ?></td>
        <td class="tbl-num"><?php echo $event_lists[$i]['Event']['amount']; ?></td>
        <td class="tbl-ico"><?php if ($event_lists[$i]['Event']['status'] == 0) {echo '<span class="icon-false">未定</span>';}
                              elseif ($event_lists[$i]['Event']['status'] == 1) {echo '<span class="icon-true">確定</span>';} ?></td>
        <td><?php echo $this->Form->postLink('修正', array('action' => 'edit', $event_lists[$i]['Event']['id'])); ?>
            <?php echo $this->Form->postLink('削除', array('action' => 'deleted', $event_lists[$i]['Event']['id'])); ?></td></tr>
    <?php } ?>
  </table>