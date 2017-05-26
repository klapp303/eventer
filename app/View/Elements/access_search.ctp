<?php if ($data['Place']['access']): //会場データに最寄り駅がある場合 ?>
  <?php if ($this->Session->read('Auth.User.station')): //ユーザデータに最寄り駅がある場合 ?>
  <?php
  $url = 'http://www.ekikara.jp/cgi-bin/route.cgi?sort=time&check=off&airplane=off&sprexprs=off&utrexprs=off&max=5&half=on&cut=on&direct=on&isTop=on&intext=';
  ?>
    <?php if ($data['EventsDetail']['time_open'] || $data['EventsDetail']['time_start']): //イベントデータに開催時刻がある場合 ?>
    <?php
    $dateFormat = new DateTime($data['EventsDetail']['date']);
    $s_month = $dateFormat->format('Ym');
    $s_day = $dateFormat->format('j');
    $timeFormat = new DateTime(($data['EventsDetail']['time_open'])? $data['EventsDetail']['time_open'] : $data['EventsDetail']['time_start']);
    $s_hour = $timeFormat->format('H');
    $s_min = $timeFormat->format('i');
    ?>
    <span class="icon-button"><?php echo $this->Html->link(
        '経路を確認する',
        $url . $this->Session->read('Auth.User.station') . '&outtext=' . $data['Place']['access'] . '&month=' . $s_month . '&day=' . $s_day . '&hour=' . $s_hour . '&min=' . $s_min . '&arrive=on&way=&search=検索',
        array('target' => '_blank')
    ); ?></span>
    <?php else: //イベントデータに開催時刻がない場合 ?>
    <?php
    $dateFormat = new DateTime($data['EventsDetail']['date']);
    $s_month = $dateFormat->format('Ym');
    $s_day = $dateFormat->format('j');
    $s_hour = 12;
    $s_min = 00;
    ?>
    <span class="icon-button"><?php echo $this->Html->link(
        '経路を確認する',
        $url . $this->Session->read('Auth.User.station') . '&outtext=' . $data['Place']['access'] . '&month=' . $s_month . '&day=' . $s_day . '&hour=' . $s_hour . '&min=' . $s_min . '&arrive=on&way=&search=検索',
        array('target' => '_blank')
    ); ?></span>
    <?php endif; ?>
  <?php else: //ユーザデータに最寄り駅がない場合 ?>
  <div class='txt-min txt-block'>
    最寄り駅を<?php echo $this->Form->postLink('登録', array('controller' => 'Users', 'action' => 'edit', $this->Session->read('Auth.User.id'))); ?>すれば<br>
    経路を1clickで検索できる！
  </div>
  <?php endif; ?>
<?php endif; ?>