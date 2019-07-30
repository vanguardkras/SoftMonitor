<h2><?= ADMIN_USER_HEADER ?></h2>
<p class="info"><?= $message ?></p>
<h3><?= ADMIN_USER_GROUPS ?></h3>
<table>
    <tr>
        <th><?= WORD_NAME ?></th>
        <th><?= WORD_RIGHTS ?></th>
    </tr>
    <?php
    foreach ($groups as $g) {
        if ($g['rights'] == 0) {
            $non_adm = ' selected';
            $adm = '';
        } elseif ($g['rights'] == 1) {
            $adm = ' selected';
            $non_adm = '';
        }
        ?>
        <form action="" method="post">
            <tr>
                <td><input type="text" name="name" value="<?= $g['name'] ?>" required></td>
                <td>
                    <select name="rights">
                        <option value="0"<?= $non_adm ?>><?= WORD_USER ?></option>
                        <option value="1"<?= $adm ?>><?= WORD_ADMIN ?></option>
                    </select>
                </td>
                <td>
                    <input type="hidden" name="id" value="<?= $g['id'] ?>">
                    <input type="submit" name="change_group" value="<?= MODIFY_BUTTON ?>">
                </td>
                <td>
                    <input type="submit" name="remove_group" value="<?= DELETE_BUTTON ?>">
                </td>
            </tr>
        </form>
    <?php } ?>
    <form action="" method="post">
        <tr>
            <td><input type="text" name="name" required></td>
            <td>
                <select name="rights">
                    <option value="0" selected><?= WORD_USER ?></option>
                    <option value="1"><?= WORD_ADMIN ?></option>
                </select>
            </td>
            <td colspan="2">
                <input type="submit" name="add_group" value="<?= ADMIN_ADD_GROUP ?>">
            </td>
        </tr>
    </form>
</table>
<h3><?= ADMIN_USERS ?></h3>
<table>
    <tr>
        <th><?= WORD_USERNAME ?></th>
        <th><?= WORD_GROUP ?></th>
        <th><?= ADMIN_NEW_PASS ?></th>
    </tr>
    <?php foreach ($user_list as $us) { ?>
        <form action="" method="post">
            <tr>
                <td>
                    <input type="text" name="login" value="<?= $us['login'] ?>" required>
                    <input type="hidden" name="id" value="<?= $us['id'] ?>">
                </td>
                <td>
                    <select name="group_id">
                        <?php
                        foreach ($groups as $gr) {
                            $select = '';
                            if ($gr['id'] == $us['group_id']) {
                                $select = ' selected';
                            }
                            ?>
                            <option value="<?= $gr['id'] ?>"<?= $select ?>><?= $gr['name'] ?></option>
                        <?php } ?>
                    </select>
                    <input type="submit" name="change_user" value="<?= MODIFY_BUTTON ?>">
                </td>
                <td><input type="password" name="pass"></td>
                <td>
                    <input type="submit" name="change_pass" value="<?= CHANGE_PASS_BUTTON ?>">
                </td>
                <td>
                    <input type="submit" name="remove_user" value="<?= DELETE_BUTTON ?>">
                </td>
            </tr>
        </form>
    <?php } ?>
    <form action="" method="post">
        <tr>
            <td>
                <input type="text" name="login" required>
            </td>
            <td>
                <select name="group_id">
                    <?php foreach ($groups as $gr) { ?>
                        <option value="<?= $gr['id'] ?>"><?= $gr['name'] ?></option>
                    <?php } ?>
                </select>
            </td>
            <td>
                <input type="password" name="pass">
            </td>
            <td colspan="2">
                <input type="submit" name="add_user" value="<?= ADMIN_ADD_USER ?>">
            </td>
        </tr>
    </form>
</table>