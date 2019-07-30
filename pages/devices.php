<form action="" method="post">
    <input type="text" name="search_text" value="<?= $search ?>">
    <input type="submit" name="search" value="<?= SEARCH_BUTTON ?>">
</form>
<div>
    <p class="sort">Sort by:
        <a href="?order=name&desc=<?= $desc ?>"><?= WORD_NAME ?><?= $this->getSortArrows('name', $desc) ?></a>
        <a href="?order=ip&desc=<?= $desc ?>">IP<?= $this->getSortArrows('ip', $desc) ?></a>
    </p>
</div>
<div class="devices_list">
    <?php foreach ($devs as $d) { 
        $level = $d['lvl'] ?? 4;
        if (isset($d['ack'])) {
            $blink = $d['ack'] == 0 ? '_blink' : '';
        } else {
            $blink = '';
        }
        ?>
        <div class="device_list">
                <div class="mon">
                    <div class="<?= ALARM_LEVELS[$level] ?><?= $blink ?>"></div>
                    <a href="?id=<?= $d['id'] ?>"><?= $d['name'] ?></a>
                </div>
            <p>IP: <?= $d['ip'] ?></p>
            <p><?= WORD_PING ?>: <?= $d['ping'] ?></p>
        </div>
    <?php } ?>
</div>
<!-- Autorefreshing page's data. Interval in ms-->
<script>
   refresh = 1;
</script>