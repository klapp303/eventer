<p>
  製作者が把握してるけど、現状まだだよ！ってリスト。<br>
  ここにあるからといって実装されるとは限らない。<br>
  しかもこのページ自体が全てHTMLのタグ打ち = リスト更新手作業の模様。<br>
  将来的にこのページはなくなるハズだからね、しかたないね。
</p>

<h3>未対応、対応予定一覧</h3>

  <table class="detail-list">
    <tr><th class="tbl-num">ID</th><th>課題</th><th>説明</th><th>状態</th></tr>
    <!--<tr><td class="tbl-num">1</td><td>課題1</td><td>
        説明文が入ります。説明文が入ります。説明文が入ります。<br>
        説明文が入ります。
        </td><td><span class="icon-genre">minor</span></td></tr>-->
    <tr><td class="tbl-num">1</td><td>パスワードの変更フォーム</td><td>
        セキュリティ的な意味で現状は未実装。<br>
        作れなくはないけど面倒だから後回し。
        </td><td><span class="icon-genre">minor</span></td></tr>
    <tr><td class="tbl-num">2</td><td>イベント参加者の選択フォーム<span class="txt-alt">←New</span></td><td>
        対応したったｗｗｗ別窓は動きがややこしいのでパス。<br>
        とりあえずmax-height決めてあるから、ある一定以上の人数になったら埋め込みスクロールにはなる。
        </td><td><span class="icon-false">closed</span></td></tr>
    <tr><td class="tbl-num">3</td><td>会場のデータ管理とイベントとの紐付け<span class="txt-alt">←New</span></td><td>
        サンプルデータと共に実装済み。<br>
        書き換えられるとおかしくなるから、登録削除のみで編集はなしかな…あとデフォルトのは削除不可。
        </td><td><span class="icon-false">closed</span></td></tr>
    <tr><td class="tbl-num">4</td><td>新規登録の諸々<span class="txt-alt">←New</span></td><td>
        URL入力で誰でもアクセスできるから、登録時にもログイン認証は必須。<br>
        と思ってたけどメアド登録させるからそっちの方向でいけそう。<br>
        閲覧専用のアカウントを1つ用意してもいいかも…？
        </td><td><span class="icon-genre">major</span></td></tr>
    <tr><td class="tbl-num">5</td><td>デザイン諸々</td><td>
        システムがある程度固まらないとレイアウト崩れ起こりやすいので後回し。<br>
        動きゃいいんだよ！！！<br>
        システム面があらかた揃ったページからデザインちょっといじった。（追記）
        </td><td><span class="icon-genre">minor</span></td></tr>
    <tr><td class="tbl-num">6</td><td>収支管理の機能</td><td>
        もう一つの目玉機能・・・多分。<br>
        その性格上、上の対応すべて終えてからじゃないと難しそう。
        </td><td><span class="icon-genre">major</span></td></tr>
    <tr><td class="tbl-num">7</td><td>TOPページ</td><td>
        どの情報を載せるか決めてないので後回し。<br>
        データさえあれば、それを表示する事は可能、希望とか聞いてみたいかも。
        </td><td><span class="icon-genre">minor</span></td></tr>
    <tr><td class="tbl-num">8</td><td>イベントの公開、非公開の設定<span class="txt-alt">←New</span></td><td>
        参加者選択をしなければ可能だからこれ自体の実装はなしかな。<br>
        登録項目増えると使う時に億劫だし。
        </td><td><span class="icon-false">closed</span></td></tr>
    <tr><td class="tbl-num">9</td><td>同じイベント別日程の処理</td><td>
        昼の部、夜の部とかそういうの。<br>
        現状は別々に登録して別々に管理だけど、一緒にしてしまうとそれはそれで問題…うーん。
        </td><td><span class="icon-genre">minor</span></td></tr>
    <tr><td class="tbl-num">10</td><td>別ユーザの同イベントの処理</td><td>
        基本的に別々に登録して別々に管理扱い。<br>
        まあ日程順にソートしたら前後に来るし、そこで分かればいいんじゃね？
        </td><td><span class="icon-genre">minor</span></td></tr>
    <tr><td class="tbl-num">11</td><td>出演者のデータ管理</td><td>
        ちょっとデータ数が読めないので保留。<br>
        やる事自体は会場のデータ管理と同じ。<br>
        竹達がいるかいないかのステータス管理だけならすぐ作れるで？
        </td><td><span class="icon-genre">minor</span></td></tr>
    <tr><td class="tbl-num">12</td><td>問い合わせ、不具合報告、改善要望フォーム</td><td>
        ここのリストが片付くメドが立ってからやな…<br>
        てか必要なんですかねぇ…
        </td><td><span class="icon-genre">minor</span></td></tr>
    <tr><td class="tbl-num">13</td><td>ユーザのメアド管理して通知メールを適宜とばす<span class="txt-alt">←New</span></td><td>
        管理者側がどのメアドを使うか決められてない。<br>
        メール系は実装可能なんだけど、やった事ないし手こずる…かも？
        </td><td><span class="icon-genre">major</span></td></tr>
  </table>