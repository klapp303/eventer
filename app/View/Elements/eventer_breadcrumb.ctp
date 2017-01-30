<?php $breadcrumbs = array(); ?>
<?php
//urlからパンくずを作成するので末尾を / に揃えておく
$request_url = $_SERVER['REQUEST_URI'];
if (mb_substr($request_url, -1) != '/') {
    $request_url = $request_url . '/';
}
?>
<?php foreach ($array_menu as $menu) { //$array_menuはAppControllerで定義 ?>
    <?php if (preg_match('*' . $menu['link'] . '*', $request_url)) {
        $breadcrumbs = array(
            0 => array('menu' => $menu['title'], 'link' => $menu['link'])
        );
        break;
    } ?>
<?php } ?>
<?php if ($breadcrumbs) { ?>
  <h2 class="breadcrumb cf">
    <ol>
      <li><?php echo $this->Html->link('TOP', '/'); ?></li>
      <?php $tmp = $breadcrumbs; //後でforeach構文で最後の処理をするため ?>
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li>
          <span>＞　</span>
          <?php if (next($tmp)) { ?>
            <?php if ($breadcrumb['link'] == '#') { ?>
              <?php echo $breadcrumb['menu']; ?>
            <?php } else { ?>
              <?php echo $this->Html->link($breadcrumb['menu'], $breadcrumb['link']); ?>
            <?php } ?>
          <?php } else { ?>
            <?php echo $breadcrumb['menu']; ?>
          <?php } ?>
        </li>
      <?php } ?>
    </ol>
  </h2>
<?php } ?>