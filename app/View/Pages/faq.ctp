<?php echo $this->Html->css('pages', array('inline' => FALSE)); ?>
<h3>よくある質問（FAQ）</h3>

<?php //よくある質問を配列で渡しておく
  $array_faq = [
      0 => [
          'date' => '2016-06-01',
          'question' => 'ダミー質問ダミー質問',
          'answer' => 'ダミー回答<br>ダミー回答'
      ]
  ];
  //日付順にソート
//  foreach ($array_faq AS $key => $val) {
//    $sort[$key] = $val['date'];
//  }
//  array_multisort($sort, SORT_DESC, $array_faq);
?>

  <?php foreach ($array_faq AS $key => $faq) { ?>
  <div class="body_faq">
  <?php echo ($key != 0)? '<hr>': ''; ?>
  
  <p>
    <span class="txt-b">Q<?php echo $key +1; ?></span><br>
    <?php echo $faq['question']; ?>
  </p>
  
  <p>
    <span class="txt-b">A<?php echo $key +1; ?></span><br>
    <?php echo $faq['answer']; ?>
  </p>
  </div>
  <?php } ?>