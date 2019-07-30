<div class="cols_2">
    <div>
        <h2><?= ADMIN_TEMPLATE_HEADER ?></h2>
        <p class="info"><?= $message ?></p>
        <table>
            <?php foreach ($temps as $t) { ?>
                <form action="./template" method="post">
                    <tr>
                        <td>
                            <a href="?id=<?= $t['id'] ?>"><?= $t['name'] ?></a>
                        </td>
                        <td>
                            <input type="hidden" name="id" value="<?= $t['id'] ?>">
                            <input type="submit" name="copy" value="<?= COPY_BUTTON ?>">
                            <input type="submit" name="delete_template" value="<?= DELETE_BUTTON ?>">
                        </td>
                    </tr>
                </form>
            <?php } ?>
            <form action="" method="post">
                <tr class="add">
                    <td>
                        <input type="text" name="name" required>
                    </td>
                    <td colspan="2">
                        <input type="submit" name="add_template" value="<?= ADMIN_ADD_TEMPLATE ?>">
                    </td>
                </tr>
            </form>
            <form action="" method="post" enctype="multipart/form-data">
                <tr>
                    <td>
                        <input type="file" name="tmplt" required>
                    </td>
                    <td>
                        <input type="submit" name="upload" value="<?= IMPORT_BUTTON ?>" >
                    </td>
                </tr>
            </form>
        </table>
    </div>
    <?php if (isset($_GET['id'])) { ?>
        <div>
            <h3><?= ADMIN_EDIT_TEMPLATE ?></h3>
            <table>
                <form action="" method="post">
                    <tr>
                        <th><?= WORD_NAME ?>:</th>
                        <td>
                            <input type="text" name="name" value="<?= $templ['name'] ?>" required>
                            <input type="hidden" name="id" value="<?= $_GET['id'] ?>">
                        </td>
                        <td>
                            <input type="submit" name="export" value="<?= EXPORT_BUTTON ?>">
                        </td>
                    </tr>
                    <tr>
                        <th><?= ADMIN_REC_TIME ?>:</th>
                        <td>
                            <input type="number" name="recover_time" value="<?= $templ['recover_time'] ?>" required>
                        </td>
                        <td>
                            <input type="submit" name="change" value="<?= CHANGE_BUTTON ?>">
                        </td>
                    </tr>
                    <tr><td class="space"></td></tr>
            </table>
            <table>
                </form>
                <tr>
                    <th>OID</th>
                    <th><?= WORD_LOW ?></th>
                    <th><?= WORD_HIGH ?></th>
                    <th><?= WORD_MESSAGE ?></th>
                    <th><?= WORD_LEVEL ?></th>
                </tr>
                <?php foreach ($templ['oids'] as $oid) { ?>
                    <form action="" method="post">
                        <tr>
                            <td>
                                <input type="hidden" name="id" value="<?= $oid['id'] ?>">
                                <input type="text" name="oid" value="<?= $oid['oid'] ?>" required>
                            </td>
                            <td>
                                <input type="text" name="low" value="<?= $oid['low'] ?>">
                            </td>
                            <td>
                                <input type="text" name="high" value="<?= $oid['high'] ?>">
                            </td>
                            <td>
                                <input type="text" name="message" value="<?= $oid['message'] ?>" required>
                            </td>
                            <td>
                                <select name="level">
                                    <option value="1"<?php if ($oid['level'] == 1) echo ' selected'; ?>><?= WORD_MINOR ?></option>
                                    <option value="2"<?php if ($oid['level'] == 2) echo ' selected'; ?>><?= WORD_MAJOR ?></option>
                                    <option value="3"<?php if ($oid['level'] == 3) echo ' selected'; ?>><?= WORD_CRITICAL ?></option>
                                </select>
                            </td>
                            <td>
                                <input type="hidden" name="template_id" value="<?= $oid['template_id'] ?>">
                                <input type="submit" name="modify" value="<?= MODIFY_BUTTON ?>">
                            </td>
                            <td>
                                <input type="submit" name="delete_oid" value="<?= DELETE_BUTTON ?>">
                            </td>
                        </tr>
                    </form>
                <?php } ?>
                <form action="" method="post">
                    <tr>
                        <td>
                            <input type="text" name="oid" required>
                            <input type="hidden" name="template_id" value="<?= $_GET['id'] ?>">
                        </td>
                        <td>
                            <input type="text" name="low">
                        </td>
                        <td>
                            <input type="text" name="high">
                        </td>
                        <td>
                            <input type="text" name="message" required>
                        </td>
                        <td>
                            <select name="level">
                                <option value="1"><?= WORD_MINOR ?></option>
                                <option value="2"><?= WORD_MAJOR ?></option>
                                <option value="3" selected><?= WORD_CRITICAL ?></option>
                            </select>
                        </td>
                        <td>
                            <input type="submit" name="add_oid" value="<?= ADD_BUTTON ?>">
                        </td>
                    </tr>
                </form>
            </table>
        </div>
    </div>
<?php } ?>
