<ul class="menu-list">
  <?php foreach ($array_menu as $menu) { ?>
    <li><?php echo $this->Html->link($menu['title'], $menu['link']); ?></li>
  <?php } ?>
</ul>