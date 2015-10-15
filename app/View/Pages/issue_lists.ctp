<?php echo $this->Html->css('pages', array('inline' => FALSE)); ?>
<div class="intro_pages">
  <p>
    製作者が把握してるけど、現状まだだよ！ってリスト。<br>
    ここにあるからといって実装されるとは限らない。<br>
    しかもこのページ自体が全てHTMLのタグ打ち = リスト更新手作業の模様。<br>
    将来的にこのページはなくなるハズだからね、しかたないね。
  </p>
</div>

<h3>未対応、対応予定一覧</h3>

  <table class="detail-list">
    <tr><th class="tbl-num">No.</th><th>課題</th><th>説明</th><th>状態</th></tr>
    <!--<tr><td class="tbl-num">1</td><td>課題1</td><td>
        説明文が入ります。説明文が入ります。説明文が入ります。<br>
        説明文が入ります。<hr>
        </td><td><span class="icon-genre">minor</span></td></tr>-->
    <tr><td class="tbl-num">1</td><td>メール通知機能<span class="txt-alt">←New</span></td><td>
        システム上はできるハズ。だが製作者はやった事ないので初の試みという…<br>
        個人的にも是非欲しいところだけど長い目で見てくだしあ＞＜<hr>
      </td><td><span class="icon-alt">major</span></td></tr>
    <tr><td class="tbl-num">2</td><td>パスワード変更機能</td><td>
        メール通知機能ができればできるハズ。<br>
        セキュリティの関係で元のパスワード呼び出すのが面倒だけど、<br>
        とりあえず上書きってだけなら比較的すぐに対応できそう。<hr>
        </td><td><span class="icon-alt">major</span></td></tr>
    <tr><td class="tbl-num">3</td><td>ユーザ新規登録時のメール認証機能</td><td>
        直接URL叩いて（メアド認証ないか分からないから）捨てメールも作って<br>
        わざわざ登録する程の外部の暇人はいないだろうから後回しやなぁ。<hr>
        </td><td><span class="icon-like">minor</span></td></tr>
    <tr><td class="tbl-num">4</td><td>イベント参加者の選択機能<span class="txt-alt">←New</span></td><td>
        実装したったｗｗｗ別窓は動きがややこしいからパス。<br>
        とりあえずmax-height決めてあるから、人数増えてきたら埋め込みスクロールにはなるよっと。<br>
        誰がどれに参加しているかは一意じゃないので（userIDとeventIDの組み合わせ結果で一意とするのは無理）<br>
        参加済みユーザ情報を呼び出してチェックを外す事は現状できないです。<hr>
        </td><td><span class="icon-false">closed</span></td></tr>
    <tr><td class="tbl-num">5</td><td>会場データの管理<span class="txt-alt">←New</span></td><td>
        実装したったｗｗｗ普段の「会場調べる→最寄り駅調べる→電車を調べる→駅からのルート調べる」が<br>
        最寄り駅と開催時刻データを使う事でクリックだけでさくっとできるよ！<br>
        地味に便利すぎて震えてる（自画自賛）これはスマホ対応も将来したいンゴねぇ<hr>
        </td><td><span class="icon-false">closed</span></td></tr>
    <tr><td class="tbl-num">6</td><td>収支管理の機能</td><td>
        他が実装できて手を付けられる段階まで来てるけどまだ概観が見えてないから後回し（ぇ<br>
        もうひとつの目玉機能・・・になったらイイね！！<hr>
        </td><td><span class="icon-like">minor</span></td></tr>
    <tr><td class="tbl-num">7</td><td>TOPページ</td><td>
        地味にまだできていないっていう・・・／(^o^)＼ﾅﾝﾃｺｯﾀｲ<br>
        とりあえず<s>簡単そうだし</s>次はこのあたりからかな。<hr>
        </td><td><span class="icon-alt">major</span></td></tr>
    <tr><td class="tbl-num">8</td><td>デザイン諸々<span class="txt-alt">←New</span></td><td>
        メール関連はレイアウトに影響しないし、収支管理はまた別ページだろうから、<br>
        とりあえず既存のページに施してみた。オサレじゃなくても見やすけりゃいいんだよ！！<hr>
        </td><td><span class="icon-false">closed</span></td></tr>
    <tr><td class="tbl-num">9</td><td>同じイベント別日程の処理</td><td>
        昼の部、夜の部とかそういうの。<br>
        現状は別々に登録して別々に管理だけど、一緒にしてしまうとそれはそれで問題…うーん。<hr>
        </td><td><span class="icon-genre">undecided</span></td></tr>
    <tr><td class="tbl-num">10</td><td>別ユーザの同イベントの処理</td><td>
        基本的に別々に登録して別々に管理扱い。<br>
        まあ日程順にソートしたら前後に来るし、そこで分かればいいんじゃね？<br>
        やるとしたら登録時に似たイベントがあればアラート出すとか・・・まあ後回し。<hr>
        </td><td><span class="icon-like">minor</span></td></tr>
    <tr><td class="tbl-num">11</td><td>出演者のデータ管理</td><td>
        ちょっとデータ数が読めないので保留。<br>
        やる事自体は会場のデータ管理と同じ。<br>
        竹達がいるかいないかのステータス管理だけならすぐ作れるで？<hr>
        </td><td><span class="icon-like">minor</span></td></tr>
    <tr><td class="tbl-num">12</td><td>問い合わせ、不具合報告、改善要望フォーム</td><td>
        ここのリストが片付くメドが立ってからやな…<br>
        てか必要なんですかねぇ・・・
        </td><td><span class="icon-like">minor</span></td></tr>
  </table>