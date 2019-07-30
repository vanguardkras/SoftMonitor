<form action="" method="post">
    <?= START_TIME ?>: <input type="datetime-local" name="start">
    <?= END_TIME ?>: <input type="datetime-local" name="end">
    <input type="submit" name="submit" value="<?= SHOW_BUTTON ?>">
    <input type="submit" name="download" value="<?= DOWNLOAD_LOG_BUTTON ?>">
</form>
<table>
    <tr>
        <th><?= WORD_TIME ?></th>
        <th><?= WORD_DEVICE ?></th>
        <th><?= WORD_MESSAGE ?></th>
    </tr>
    <?php foreach ($log_data as $l) { ?>
    <tr class="<?= ALARM_LEVELS[$l['lvl']] ?>">
        <td><?= date(TIME_FORMAT, $l['occur_time']) ?></td>
        <td><?= $l['device'] ?></td>
        <td><?= $l['message'] ?></td>
    </tr>
    <?php } ?>
</table>