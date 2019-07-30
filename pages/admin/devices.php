<h2><?= ADMIN_DEVICE_HEADER ?></h2>
<form action="" method="post">
    <input type="text" name="search_text" value="<?= $search ?>">
    <input type="submit" name="search" value="<?= SEARCH_BUTTON ?>">
</form>
<span class="info"><?= $info ?></span>
<table>
    <tr>
        <th><a href="?order=name&desc=<?= $desc ?>"><?= WORD_NAME ?><?= $this->getSortArrows('name', $desc)?></a></th>
        <th><a href="?order=ip&desc=<?= $desc ?>">IP<?= $this->getSortArrows('ip', $desc)?></a></th>
        <th><a href="?order=template_id&desc=<?= $desc ?>"><?= WORD_TEMPLATE ?><?= $this->getSortArrows('template_id', $desc)?></a></th>
        <th><a href="?order=connection_id&desc=<?= $desc ?>"><?= WORD_CONNECTION ?><?= $this->getSortArrows('connection_id', $desc)?></a></th>
        <th><?= WORD_PING_ATT ?></th>
        <th><?= WORD_PING_TIMEOUT ?></th>
        <th><a href="?order=group_id&desc=<?= $desc ?>"><?= WORD_GROUP ?><?= $this->getSortArrows('group_id', $desc)?></a></th>
    </tr>
    <form action="" method="post">
        <tr>
            <td><input type="text" name="name" required></td>
            <td><input type="text" name="ip" required></td>
            <td>
                <select name="template_id">
                    <?php foreach ($temps as $t) { ?>
                        <option value="<?= $t['id'] ?>"><?= $t['name'] ?></option>
                    <?php } ?>
                </select>
            </td>
            <td>
                <select name="connection_id">
                    <?php foreach ($cons as $c) { ?>
                        <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                    <?php } ?>
                </select>
            </td>
            <td>
                <input type="number" name="ping_attempts">
            </td>
            <td>
                <input type="number" name="ping_timeout">
            </td>
            <td>
                <select name="group_id">
                    <?php foreach ($groups as $g) { ?>
                        <option value="<?= $g['id'] ?>"><?= $g['name'] ?></option>
                    <?php } ?>
                </select>
            </td>
            <td colspan="2">
                <input type="submit" name="add" value="<?= ADD_DEVICE_BUTTON ?>">
            </td>
        </tr>
    </form>
    <?php foreach ($devs as $d) { ?>
        <form action="" method="post">
            <tr>
                <td><input type="text" name="name" value="<?= $d['name'] ?>" required></td>
                <td>
                    <input type="text" name="ip" value="<?= $d['ip'] ?>" required>
                    <input type="hidden" name="id" value="<?= $d['id'] ?>">
                </td>
                <td>
                    <select name="template_id">
                        <?php
                        foreach ($temps as $t) {
                            $sel = '';
                            if ($d['template_id'] == $t['id']) {
                                $sel = ' selected';
                            }
                            ?>
                            <option value="<?= $t['id'] ?>"<?= $sel ?>><?= $t['name'] ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td>
                    <select name="connection_id">
                        <?php
                        foreach ($cons as $c) {
                            $sel = '';
                            if ($d['connection_id'] == $c['id']) {
                                $sel = ' selected';
                            }
                            ?>
                            <option value="<?= $c['id'] ?>"<?= $sel ?>><?= $c['name'] ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td>
                    <input type="number" name="ping_attempts" value="<?= $d['ping_attempts'] ?>">
                </td>
                <td>
                    <input type="number" name="ping_timeout" value="<?= $d['ping_timeout'] ?>">
                </td>
                <td>
                    <select name="group_id">
                        <?php
                        foreach ($groups as $g) {
                            $sel = '';
                            if ($d['group_id'] == $g['id']) {
                                $sel = ' selected';
                            }
                            ?>
                            <option value="<?= $g['id'] ?>"<?= $sel ?>><?= $g['name'] ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td>
                    <input type="submit" name="modify" value="<?= MODIFY_BUTTON ?>">
                </td>
                <td>
                    <input type="submit" name="delete" value="<?= DELETE_BUTTON ?>">
                </td>
            </tr>
        </form>
    <?php } ?>
</table>