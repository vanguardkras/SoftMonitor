<div class="one_device">
    <div class="mon">
        <div class="<?= ALARM_LEVELS[$severty] ?>"></div>
        <p><?= $device['name'] ?></p>
    </div>
    <div>
        IP: <?= $device['ip'] ?>
        <?= WORD_PING ?>: <?= $device['ping'] ?>
    </div>
    <div>
        <?= WORD_TEMPLATE ?>: <?= $template['name'] ?>
        <br>
        <?= WORD_CONNECTION ?>: <?= $conn['name'] ?>
    </div>
</div>
<h3 class="one_device"><?= WORD_ALARMS ?>:</h3>
<div class="alarms">
    <table>
        <?php
        foreach ($alarm as $a) {
            if ($a['recover'] == 1) {
                $lvl = 0;
            } else {
                $lvl = $a['lvl'];
            }
            ?>
            <tr class="<?= ALARM_LEVELS[$lvl] ?>">
                <td class="time"><?= date(TIME_FORMAT, $a['occur_time']) ?></td>
                <td><?= $a['message'] ?></td>
            </tr>
        <?php } ?>
    </table>
</div>
<h3 class="one_device"><?= WORD_LOG ?>:</h3>
<div class="log">
    <table>
        <tr>
            <th><?= WORD_TIME ?></th>
            <th><?= WORD_MESSAGE ?></th>
        </tr>
        <?php foreach ($log_data as $l) { ?>
            <tr class="<?= ALARM_LEVELS[$l['lvl']] ?>">
                <td><?= date(TIME_FORMAT, $l['occur_time']) ?></td>
                <td><?= $l['message'] ?></td>
            </tr>
        <?php } ?>
    </table>
</div>