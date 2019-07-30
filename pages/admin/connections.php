<h2><?= ADMIN_CONNECTION_HEADER ?></h2>
<?php if (isset($_GET['id'])) { ?>
    <p><a href="./connections"><-<?= WORD_BACK ?></a></p>
        <form action="" method="post">
        <table>
            <tr>
                <th><?= ADMIN_CON_TEMPLATE_NAME ?>:</th>
                <td>
                    <input value="<?= $cur['name'] ?>" type="text" name="name" required>
                    <input value="<?= $cur['id'] ?>" type="hidden" name="id">
                </td>
            </tr>
            <tr>
                <th><?= ADMIN_SNMP_VERSION ?>:</th>
                <td>
                    <select name="snmp_version">
                        <?php
                        for ($i = 1; $i <= 3; $i++) {
                            $selected = '';
                            if ($cur['snmp_version'] == $i) {
                                $selected = ' selected';
                            }
                            ?>
                            <option value="<?= $i ?>"<?= $selected ?>>v<?= $i ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><?= ADMIN_LOGIN_STRING ?></th>
                <td>
                    <input value="<?= $cur['login'] ?>" type="text" name="login" required>
                </td>
            </tr>
            <tr>
                <th><?= PASSWORD_WORD ?>:</th>
                <td>
                    <input value="<?= $cur['pass'] ?>" type="text" name="pass">
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="submit" name="submit" value="<?= SAVE_BUTTON ?>">
                </td>
            </tr>
        </table>
    <?php } else {
        ?>
        <h4><?= ADMIN_CON_TEMPLATE_SELECT_HEADER ?></h4>
        <p class="info"><?= $message ?></p>
        <table>
        <?php foreach ($names as $n) {
            ?>
            <tr>
        <form action="" method="post">
            <td><a href="?id=<?= $n['id'] ?>"><?= $n['name'] ?></a> </td>
            <td>
                <input type="hidden" name="id" value="<?= $n['id'] ?>">
                <input type="submit" name="copy" value="<?= COPY_BUTTON ?>">
            </td>
            <td>
                <input type="submit" name="delete" value="<?= DELETE_BUTTON ?>">
            </td>
            </p>
        </form>
            <tr>
        <?php } ?>
            <table>
                <hr>
        <h4><?= ADMIN_ADD_CON_TEMPLATE_HEADER ?></h4>
        <form action="" method="post">
            <table>
                <tr>
                    <th><?= ADMIN_CON_TEMPLATE_NAME ?>:</th>
                    <td>
                        <input type="text" name="name" required>
                    </td>
                </tr>
                <tr>
                    <th><?= ADMIN_SNMP_VERSION ?>:</th>
                    <td>
                        <select name="snmp_version">
                            <option value="1">v1</option>
                            <option value="2">v2</option>
                            <option value="3" selected>v3</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><?= ADMIN_LOGIN_STRING ?>:</th>
                    <td>
                        <input type="text" name="login" required>
                    </td>
                </tr>
                <tr>
                    <th><?= PASSWORD_WORD ?>:</th>
                    <td>
                        <input type="text" name="pass">
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="submit" name="submit" value="<?= ADD_BUTTON ?>">
                    </td>
                </tr>
            </table>
        </form>
    <?php } ?>
