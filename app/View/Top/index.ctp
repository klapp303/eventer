<h3><?php echo date('Y年m月d日'); ?>現在のサンプル情報</h3>

<h3>コンテンツ1</h3>

<h3>コンテンツ2</h3>

<h3>Session情報</h3>
<pre>
  <?php print_r($this->Session->read('Auth')); ?>
</pre>