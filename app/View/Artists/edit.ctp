<?php echo $this->Html->css('artists', array('inline' => false)); ?>
<?php echo $this->Html->script('jquery-tmb', array('inline' => false)); ?>
<h3>出演者タグの編集</h3>

  <table>
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
      <td><label>アーティストカナ<br>（全角カナ）</label></td>
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
      <td><?php echo $this->Form->input('Artist.link_urls.0.link_url', array('type' => 'text', 'label' => false)); ?><br>
          <?php echo $this->Form->input('Artist.link_urls.1.link_url', array('type' => 'text', 'label' => false)); ?><br>
          <?php echo $this->Form->input('Artist.link_urls.2.link_url', array('type' => 'text', 'label' => false)); ?></td>
    </tr>
  </table>
  
  <table>
    <tr>
      <td><label>関連アーティスト</label></td>
      <td><select name="related-lists" size="15" style="width:200px;" multiple="multiple">
            <?php foreach ($related_lists as $related_list) { ?>
              <option value="<?php echo $related_list['artist_id']; ?>"><?php echo $related_list['name']; ?></option>
            <?php } ?>
          </select></td>
      <td><button type="button" class="artist-add-button"><< 追加する</button><br><br>
          <button type="button" class="related-delete-button">>> 削除する</button></td>
      <td><select name="artist-lists" size="15" style="width:200px;" multiple="multiple">
            <?php foreach ($artist_lists as $key => $val) { ?>
              <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
            <?php } ?>
          </select></td>
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

<script>
    jQuery(function($) {
        //アーティストの追加
        $('.artist-add-button').click(function() {
            //選択されているアーティストを取得
            var artist_id = $('[name=artist-lists]').val();
            var artist_name = [];
            $('[name=artist-lists] option:selected').each(function() {
                artist_name.push($(this).text());
            });
            
            $.each(artist_id, function(i) {
                //関連アーティスト一覧に追加する
                $('[name=related-lists]').append('<option value="' + artist_id[i] + '">' + artist_name[i] + '</option>');
                //アーティスト一覧から削除する
                $('[name=artist-lists] option[value=' + artist_id[i] + ']').remove();
            });
        });
        
        //関連アーティストの削除
        $('.related-delete-button').click(function() {
            //選択されている関連アーティストを取得
            var related_id = $('[name=related-lists]').val();
            var related_name = [];
            $('[name=related-lists] option:selected').each(function() {
                related_name.push($(this).text());
            });
            
            $.each(related_id, function(i) {
                //アーティスト一覧に追加する
                $('[name=artist-lists]').append('<option value="' + related_id[i] + '">' + related_name[i] + '</option>');
                //関連アーティスト一覧から削除する
                $('[name=related-lists] option[value=' + related_id[i] + ']').remove();
            });
        });
        
        //関連アーティストの登録
        $('#ArtistEditForm').submit(function() {
            //関連アーティスト一覧を取得する
            var related_id_all = $('[name=related-lists]').children();
            
            //要素を一つずつ追加
            for(var i = 0; i < related_id_all.length; i++) {
                $('<input>').attr('type', 'hidden')
                              .attr('name', 'data[Artist][related_artists_id][' + i + '][artist_id]')
                              .attr('value', related_id_all.eq(i).val())
                              .appendTo('#ArtistEditForm');
            }
        });
    });
</script>