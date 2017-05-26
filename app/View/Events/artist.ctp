<?php echo $this->Html->css('events', array('inline' => false)); ?>
<h3>出演者の管理</h3>

  <?php echo $this->Form->create('Events', array( //使用するModel
      'type' => 'post', //デフォルトはpost送信
      'action' => 'cast', //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
  )); ?><!-- form start -->
  
  <?php echo $this->Form->input('EventsArtist.event_id', array('type' => 'hidden', 'value' => $events_detail['EventsDetail']['event_id'])); ?>
  <?php echo $this->Form->input('EventsArtist.events_detail_id', array('type' => 'hidden', 'value' => $events_detail['EventsDetail']['id'])); ?>
  <?php echo $this->Form->input('EventsArtist.user_id', array('type' => 'hidden', 'value' => $userData['id'])); ?>
  
  <table>
    <tr><td><select name="cast-lists" size="15" style="width:200px;" multiple="multiple">
          <?php foreach ($cast_lists as $cast_list): ?>
          <option value="<?php echo $cast_list['EventArtist']['artist_id']; ?>"><?php echo $cast_list['ArtistProfile']['name']; ?></option>
          <?php endforeach; ?>
        </select></td>
        <td><button type="button" class="artist-add-button"><< 追加する</button><br><br>
            <button type="button" class="cast-delete-button">>> 削除する</button></td>
        <td><select name="artist-lists" size="15" style="width:200px;" multiple="multiple">
          <?php foreach ($artist_lists as $artist_list): ?>
          <option value="<?php echo $artist_list['Artist']['id']; ?>"><?php echo $artist_list['Artist']['name']; ?></option>
          <?php endforeach; ?>
        </select></td></tr>
  </table>
  
  <div class="cf" style="width: 800px;">
    <?php echo $this->Form->submit('これで登録する', array('div' => false, 'class' => 'submit')); ?>
  </div>
  
  <?php echo $this->Form->end(); ?><!-- form end -->

<div class="link-right">
  <span class="link-page"><?php echo $this->Html->link('⇨ イベント詳細ページに戻る', '/events/' . $events_detail_id); ?></span>
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
                //キャスト一覧に追加する
                $('[name=cast-lists]').append('<option value="' + artist_id[i] + '">' + artist_name[i] + '</option>');
                //アーティスト一覧から削除する
                $('[name=artist-lists] option[value=' + artist_id[i] + ']').remove();
            });
        });
        
        //キャストの削除
        $('.cast-delete-button').click(function() {
            //選択されているキャストを取得
            var cast_id = $('[name=cast-lists]').val();
            var cast_name = [];
            $('[name=cast-lists] option:selected').each(function() {
                cast_name.push($(this).text());
            });
            
            $.each(cast_id, function(i) {
                //アーティスト一覧に追加する
                $('[name=artist-lists]').append('<option value="' + cast_id[i] + '">' + cast_name[i] + '</option>');
                //キャスト一覧から削除する
                $('[name=cast-lists] option[value=' + cast_id[i] + ']').remove();
            });
        });
        
        //キャストの登録
        $('#EventsCastForm').submit(function() {
            //キャスト一覧を取得する
            var cast_id_all = $('[name=cast-lists]').children();
            
            //要素を一つずつ追加
            for(var i = 0; i < cast_id_all.length; i++) {
                $('<input>').attr('type', 'hidden')
                              .attr('name', 'data[Artist][' + i + '][artist_id]')
                              .attr('value', cast_id_all.eq(i).val())
                              .appendTo('#EventsCastForm');
            }
        });
    });
</script>