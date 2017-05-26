<?php if ($event_lists): ?>
近日の予定は以下の通りです。
<?php else: ?>
近日の予定はありません。
<?php endif; ?>


<?php foreach ($event_lists as $event_list): ?>
<?php if ($event_list['Event']['title'] == $event_list['EventsDetail']['title']): ?>
イベント名：<?php echo $event_list['Event']['title']; ?>
<?php else: ?>
イベント名：<?php echo $event_list['Event']['title']; ?>　<?php echo $event_list['EventsDetail']['title']; ?>
<?php endif; ?>

エントリー名：<?php echo $event_list['EventsEntry']['title']; ?>

<?php $week_lists = ['日', '月', '火', '水', '木', '金', '土']; ?>
<?php if ($event_list['EventsEntry']['date_status'] != '本日開演' && $event_list['EventsEntry']['date_status'] != '近日開催'): //if文1 ?>
<?php echo $event_list['EventsEntry']['date_status']; ?>：<?php echo date('m/d(' . $week_lists[date('w', strtotime($event_list['EventsEntry'][$entryDateColumn[$event_list['EventsEntry']['date_status']]]))] . ')H:i', strtotime($event_list['EventsEntry'][$entryDateColumn[$event_list['EventsEntry']['date_status']]])); ?>

<?php if ($event_list['EventsDetail']['time_start']): //if文2 ?>
開演日時：<?php echo date('m/d(' . $week_lists[date('w', strtotime($event_list['EventsEntry']['date_event']))] . ')H:i', strtotime($event_list['EventsEntry']['date_event'])); ?>
<?php else: //if文2 ?>
開演日時：<?php echo date('m/d(' . $week_lists[date('w', strtotime($event_list['EventsEntry']['date_event']))] . ')', strtotime($event_list['EventsEntry']['date_event'])); ?>
<?php endif; //if文2 ?>
<?php else: //if文1 ?>
<?php if ($event_list['EventsDetail']['time_start']): //if文3 ?>
<?php echo $event_list['EventsEntry']['date_status']; ?>：<?php echo date('m/d(' . $week_lists[date('w', strtotime($event_list['EventsEntry']['date_event']))] . ')H:i', strtotime($event_list['EventsEntry']['date_event'])); ?>
<?php else: //if文3 ?>
<?php echo $event_list['EventsEntry']['date_status']; ?>：<?php echo date('m/d(' . $week_lists[date('w', strtotime($event_list['EventsEntry']['date_event']))] . ')', strtotime($event_list['EventsEntry']['date_event'])); ?>
<?php endif; //if文3 ?>
<?php endif; //if文1 ?>


<?php endforeach; ?>