<?php echo $this->Html->css('artists', array('inline' => false)); ?>
<h3>アーティストの登録</h3>

  <div class="tbl-txt_artists">
    <span class="txt-min">追加する際は、既にアーティストが登録されていないか確認してください。</span>
  </div>
  <table>
    <?php echo $this->Form->create('Artist', array( //使用するModel
        'type' => 'post', //デフォルトはpost送信
        'action' => 'add', //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
    )); ?><!-- form start -->

  <?php echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $userData['id'])); ?>

  <tr>
    <td><label>アーティスト名</label></td>
    <td><?php echo $this->Form->input('name', array('type' => 'text', 'label' => false, 'placeholder' => '例）竹達彩奈')); ?><span class="txt-alt txt-b">*</span></td>
  </tr>
  <tr>
    <td><label>アーティストカナ（全角カナ）</label></td>
    <td><?php echo $this->Form->input('kana', array('type' => 'text', 'label' => false, 'placeholder' => '例）タケタツアヤナ')); ?><span class="txt-alt txt-b">*</span></td>
  </tr>
  
  <tr>
    <td></td>
    <td><?php echo $this->Form->submit('登録する', array('div' => false, 'class' => 'submit')); ?>　　<span class="txt-alt txt-b">*</span><span class="txt-min">は必須項目</span></td>
  </tr>
  <?php echo $this->Form->end(); ?><!-- form end -->
</table>

<h3>アーティスト一覧</h3>

  <?php foreach ($array_kana as $key => $val): ?>
  <table class="list_artists list_artists_<?php echo $key; ?>" style="display:<?php echo ($key == 0)? 'block' : 'none'; ?>;">
    <tr><?php foreach ($array_kana as $key_2 => $kana): ?>
        <td class="list-tab_artists list-tab_<?php echo $key_2; ?> <?php echo ($key_2 == $key)? 'list-tab-active_artists' : ''; ?>">
          <span class="<?php echo ($key_2 == $key)? 'txt-b' : ''; ?>"><?php echo $kana['name']; ?></span>
        </td>
        <?php endforeach; ?><td class="list-tab-non_artists"></td></tr>
    
    <tr><td colspan="9" class="list-body_artists">
      <div class="list-name-tag">
        <?php if (${'artist_lists_' . $key}): ?>
          <?php foreach (${'artist_lists_' . $key} as $artist): ?>
          <span class="name-tag-long">
            <?php echo $this->Html->link($artist['Artist']['name'], '/artists/artist_detail/' . $artist['Artist']['id']); ?>
          </span>
          <?php endforeach; ?>
        <?php else: ?>
        <p>登録されているアーティストはありません。</p>
        <?php endif; ?>
      </div>
    </td></tr>
  </table>
  <?php endforeach; ?>

<?php foreach ($array_kana as $key => $val): ?>
<script>
    jQuery(function($) {
        var key = <?php echo $key; ?>;
        $('.list-tab_' + key).click(function() {
            $('.list_artists').hide();
            $('.list_artists_' + key).show();
        });
    });
</script>
<?php endforeach; ?>

<div class="link-right">
  <span class="link-page"><?php echo $this->Html->link('⇨ イベント参加データ一覧はこちら', '/artists/compare_lists/'); ?></span>
</div>