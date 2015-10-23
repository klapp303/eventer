本日の予定があります。

<?php foreach ($event_lists AS $event_list) { ?>
  <?php echo $event_list['Event']['title']; ?>
（<?php if ($event_list['Event']['date'] == date('Y-m-d')) {echo '本日開催';}
  elseif ($event_list['Event']['payment_end'] == date('Y-m-d')) {echo '入金締切';}
  elseif ($event_list['Event']['announcement_date'] == date('Y-m-d')) {echo '当落発表';}
  elseif ($event_list['Event']['entry_end'] == date('Y-m-d')) {echo '申込締切';}
  elseif ($event_list['Event']['entry_start'] == date('Y-m-d')) {echo '申込開始';} ?>）
  
<?php } ?>