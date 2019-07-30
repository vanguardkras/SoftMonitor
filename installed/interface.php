<!DOCTYPE html>
<html lang="ru">
    <head>
        <link type="text/css" rel="stylesheet" href="/css/main.css" />
    </head>
    <body>
        <div class="footer">
            <h1><p>Soft monitor system installation</p></h1>
            <p>In order to install the server, fill all parameter fields below and press Check Button.</p>
            <p>After the check is finished, press Apply button to finish the installation.</p>
            <p>
            <form action="" method="post">
                <table class="install">
                    <tr>
                        <th colspan="2">Database parameters</th>
                    </tr>
                    <tr>
                        <td>Hostname:</td>
                        <td><input type="text" name="db_host" value="<?= $db_host_filler ?>" required></td>
                        <td class="info"><?= $host_error ?></td>
                    </tr>
                    <tr>
                        <td>User login:</td>
                        <td><input type="text" name="db_login" value="<?= $db_login_filler ?>" required></td>
                    </tr>
                    <tr>
                        <td>User password:</td>
                        <td><input type="password" name="db_pass" value="<?= $db_pass_filler ?>"></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="info"><?= $db_check ?></td>
                    </tr>
                    <tr>
                        <td class="space"></td>
                    </tr>
                    <tr>
                        <th colspan="2">System parameters</th>
                    </tr>
                    <tr>
                        <td>Language:</td>
                        <td>
                            <select name="lang">
                                <option value="en"<?php if ($lang_filler == 'en') echo ' selected'?>>English</option>
                                <option value="ru"<?php if ($lang_filler == 'ru') echo ' selected'?>>Русский</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Time format:</td>
                        <td><input type="text" name="time_format" value="<?= $time_format_filler ?>" required></td>
                        <td style="color: greenyellow;"><?= $time ?></td>
                    </tr>
                    <tr>
                        <td>Requests interval:</td>
                        <td><input type="number" min="0" max="604800" name="request_interval" value="<?= $request_interval_filler ?>" required> (sec)</td>
                    </tr>
                    <tr>
                        <td class="space"></td>
                    </tr>
                    <tr>
                        <th colspan="2">View parameters</th>
                    </tr>
                    <tr>
                        <td>Devices per page:</td>
                        <td><input type="number" name="per_page" min="5" max="100" value="<?= $per_page_filler ?>" required></td>
                    </tr>
                    <tr>
                        <td class="space"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type="submit" name="check" value="Check">
                            <input type="submit" name="install" value="Install">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </p>
</body>
</html>
