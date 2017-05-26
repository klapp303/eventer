<?php echo $this->Html->css('pages', array('inline' => false)); ?>
<?php if (!$userData): ?>
<div class="fr">
  <span class="link-page"><?php echo $this->Html->link('⇨ ログインはこちら', '/users/login/'); ?></span>
</div>
<?php endif; ?>

<h3><?php echo $sub_page; ?></h3>

<h4>1. イベントを登録する</h4>

<div class="body_about">
  <?php echo $this->Html->image('../files/info_01.jpg', array('class' => 'img_about')); ?>
  <p class="txt_about">
    まずはイベントを登録します。<br>
    右上のメニューボタンから「イベント管理」に進み、必要事項を入力すれば簡単に登録できます。
  </p>
</div>

<h4>2. イベント一覧で確認する</h4>

<div class="body_about">
  <?php echo $this->Html->image('../files/info_02.jpg', array('class' => 'img_about')); ?>
  <p class="txt_about">
    登録されたイベントは下のイベント一覧に表示されます。<br>
    詳細ボタンから各イベント毎に登録されたデータを確認できます。
  </p>
</div>

<h4>3. イベント詳細でエントリー方法を登録する</h4>

<div class="body_about">
  <?php echo $this->Html->image('../files/info_03.jpg', array('class' => 'img_about')); ?>
  <p class="txt_about">
    イベント詳細ページではまたエントリー方法の登録ができます。<br>
    日付の過ぎたものは自動的にグレーで表示されるため、視覚的に分かりやすいです。
  </p>
</div>

<h4>4. 会場の確認をする</h4>

<div class="body_about">
  <?php echo $this->Html->image('../files/info_04.jpg', array('class' => 'img_about')); ?>
  <p class="txt_about">
    また登録されたイベントから会場の詳細ページを呼び出せます。<br>
    会場までのマップや最寄り駅をいちいち検索しなくてもひと目で確認できます。
  </p>
</div>

<h4>5. TOPページで予定を確認する</h4>

<div class="body_about">
  <?php echo $this->Html->image('../files/info_05.jpg', array('class' => 'img_about')); ?>
  <p class="txt_about">
    これらの登録されたイベント予定はTOPページでまとめて確認する事ができます。<br>
    開催日や申込開始日といったものが迫れば、当日ならば「本日の予定」に、翌日～2週間後までは「直近の予定」に表示されます。
  </p>
</div>

<h4>6. お知らせメールで予定を確認する</h4>

<div class="body_about">
  <?php echo $this->Html->image('../files/info_06.jpg', array('class' => 'img_about')); ?>
  <p class="txt_about">
    もちろんいちいちログインしなくてもメールで確認する事もできます。<br>
    右上のユーザ名からユーザ設定でお知らせメールを「配信する」に設定する事で、毎日 AM 9:00 に当日から3日間分の予定をメールでお知らせします。
  </p>
</div>

<?php if (!$userData): ?>
<div class="link-center">
  <span class="link-page"><?php echo $this->Html->link('⇨ 実際に登録して使ってみる', '/users/add/'); ?></span>
</div>
<div class="link-center">
  <span class="link-page"><?php echo $this->Html->link('⇨ ゲストアカウントでログインしてみる', '/users/login/?user=' . urlencode($guest_name) . '&pass=' . urlencode($guest_password)); ?></span>
</div>
<?php endif; ?>