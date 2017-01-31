<?php echo $this->Html->css('pages', array('inline' => false)); ?>
<h3><?php echo $sub_page; ?></h3>

<?php
//日付順にソート
//foreach ($array_faq as $key => $val) {
//    $sort[$key] = $val['date'];
//}
//array_multisort($sort, SORT_DESC, $array_faq);
//カテゴリー順にソート
foreach ($array_faq as $key => $val) {
    $sort[$key] = $val['category_id'];
}
array_multisort($sort, SORT_ASC, $array_faq);
//カテゴリー毎に整形
foreach ($array_faq as $key => $val) {
    $array_category[$val['category']][$key] = $val;
}
foreach ($array_category as &$val) {
    $val = array_merge($val);
}
unset($val);
?>

<ul class="list_faq_link">
  <?php $i = 0; ?>
  <?php foreach ($array_category as $title => $category) { ?>
    <?php $i++; ?>
    <li><a href="#category_<?php echo $i; ?>"><?php echo $title; ?></a></li>
  <?php } ?>
</ul>

<?php $i = 0; ?>
<?php $number = 0; ?>
<?php foreach ($array_category as $title => $category) { ?>
  <?php $i++; ?>
  <h4 id="category_<?php echo $i; ?>"><?php echo $i . '. ' . $title; ?></h4>

  <?php foreach ($category as $key => $faq) { ?>
    <?php $number++; ?>
    <div class="body_faq">
      <p id="question_<?php echo $number; ?>" class="body_question">
        <span class="txt-b">Q<?php echo $key +1; ?></span><br>
        <?php echo $faq['question']; ?>
      </p>
      
      <p id="answer_<?php echo $number; ?>" style="display: none;">
        <span class="txt-b">A<?php echo $key +1; ?></span><br>
        <?php echo $faq['answer']; ?>
      </p>
      
      <?php if ($number < count($array_faq)) { ?>
        <hr>
      <?php } ?>
    </div>
  <script>
      jQuery(function($) {
          var number = <?php echo json_encode($number, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
          $(function() {
              $('#question_' + number).click(
                  function() {
                      $('#answer_' + number).toggle();
                  }
              );
          });
      });
  </script>
  <?php } ?>
<?php } ?>