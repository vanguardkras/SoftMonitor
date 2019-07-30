<h2><?= ADMIN_DEFAULTS_HEADER ?></h2>
<form action="" method="post" enctype="multipart/form-data">
    <tr>
        <td>
            <input type="file" name="lic" required>
        </td>
        <td>
            <input type="submit" name="upload" value="<?= UPLOAD_LICENCE_BUTTON ?>" >
        </td>
    </tr>
</form>
<br>
<form action="" method="post">
    <input onclick="return confirm('<?= LOG_CLEAR_NOTIFICATION ?>')"type="submit" name="clear_log" value="<?= CLEAR_LOG_BUTTON ?>">
</form>
<br>
<form action="" method="post">
    <table>
        <tr>
            <td><?= ADMIN_DEFAULTS_PROC_NUMBER ?>: </td>
            <td>
                <input type="number" value="<?= $pars['processor_instances'] ?>" name="processor_instances" required>
            </td> 
        </tr>
        <tr>
            <td><?= ADMIN_DEFAULTS_REC_TIME ?>: </td>
            <td>
                <input type="number" value="<?= $pars['default_recovery_time'] ?>" name="default_recovery_time" required>
            </td> 
        </tr>
        <tr>
            <td><?= ADMIN_DEFAULTS_PING_ATT ?>: </td>
            <td>
                <input type="number" value="<?= $pars['default_ping_attempts'] ?>" name="default_ping_attempts" required>
            </td> 
        </tr>
        <tr>
            <td><?= ADMIN_DEFAULTS_PING_TIMEOUT ?>: </td>
            <td>
                <input type="number" value="<?= $pars['default_ping_timeout'] ?>" name="default_ping_timeout" required>
            </td> 
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="submit" value="<?= APPLY_BUTTON ?>" name="defaults_change">
            </td>
        </tr>
    </table>
</form>