<table>
    <tr>
        <th><?= WORD_TIME ?></th>
        <th><?= WORD_DEVICE ?></th>
        <th><?= WORD_MESSAGE ?></th>
    </tr>
    <?php
    foreach ($alarms_list as $al) {
        $blink = $al['ack'] == 0 ? '_blink' : '';
        $class = $al['recover'] == 1 ?
                'notification' : ALARM_LEVELS[$al['lvl']] . $blink;
        ?>
        <tr class="<?= $class ?>">
            <td class="time"><?= date(TIME_FORMAT, $al['occur_time']) ?></td>
            <td class="dev_name">
                <a target="_blank" href="/devices?id=<?= $al['device_id'] ?>">
                    <?= $al['device'] ?>
                </a>
            </td>
            <td><?= $al['message'] ?></td>
            <td class="ack">
                <form action="" method="post">
                    <input type="hidden" name="id" value="<?= $al['id'] ?>">
                    <?php if ($al['ack'] == 0) { ?>
                        <input type="submit" name="ack" value="<?= ACK_BUTTON ?>">
                    <?php } else { ?>
                        <input type="submit" name="unack" value="<?= UNACK_BUTTON ?>">
                    <?php } ?>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>
<!-- Autorefreshing page-->
<script>
   refresh = 1;
</script>