<?php
/**
 * Renderer of admin pages.
 *
 * @author shayan-ma
 */

class AdminRenderer extends Renderer
{
    /**
     * Returns include path of a page view.
     * @param string $method Function name.
     * @return string
     */   
    protected function view($method): string
    {
        return './pages/admin/'.$method.'.php';
    }
    
    /**
     * Shows connections management.
     * @return void
     */
    public function connections(): void
    {
        $con = new Connections();
        $message = '';

        // Checks connection template id.
        if (isset($_GET['id'])) {

            // Modify a connection.
            if (isset($_POST['submit'])) {
                $con->change($_POST['id'], [
                    $_POST['name'],
                    $_POST['snmp_version'],
                    $_POST['login'],
                    $_POST['pass']
                ]);
            }
            $id = $_GET['id'];
            $cur = $con->getById($id);
        } else {
            // Copy a connection
            if (isset($_POST['copy'])) {
                $copy = $con->getById($_POST['id']);
                $con->add([
                    $copy['name'] . '_copy',
                    $copy['snmp_version'],
                    $copy['login'],
                    $copy['pass'],
                ]);
            }
            
            // Adds new connection.
            if (isset($_POST['submit'])) {
                $con->add([
                    $_POST['name'],
                    $_POST['snmp_version'],
                    $_POST['login'],
                    $_POST['pass']
                ]);
            }

            if (isset($_POST['delete'])) {
                $message = $con->delete($_POST['id']);
            }

            $names = $con->getAll('id, name');
        }

        include_once $this->view(__FUNCTION__);
    }

    /**
     * Shows default connection settings.
     * @return void
     */
    public function defaults(): void
    {

        // Send new parameters
        $def = new Defaults;
        if (isset($_POST['defaults_change'])) {
            $def->setDefaults(
                    $_POST['processor_instances'],
                    $_POST['default_recovery_time'],
                    $_POST['default_ping_attempts'],
                    $_POST['default_ping_timeout']
            );
        } elseif (isset($_POST['clear_log'])) {
            $log = new Log;
            $log->clear();
        } elseif (isset($_POST['upload'])) {
            sleep(4);
            rename($_FILES['lic']['tmp_name'], 'licence');
        }

        //Get current parameters
        $pars = $def->getDefaults();

        include_once $this->view(__FUNCTION__);
    }
    
    /**
     * Shows devices management page.
     * @return void
     */
    public function devices() 
    {
        $connections = new Connections;
        $devices = new Devices;
        $defaults = new Defaults;
        $defaults = $defaults->getDefaults();
        $page = new Paginator($devices->count(), PER_PAGE);
        $templates = new Template;
        $user_groups = new UserGroups;
        $users = new Users;
        $info = '';
        
        if (isset($_POST['modify'])) {
            $devices->change($_POST['id'], [
                $_POST['name'],
                $_POST['ip'],
                $_POST['template_id'],
                $_POST['connection_id'],
                $_POST['ping_attempts'],
                $_POST['ping_timeout'],
                $_POST['group_id']
            ]);
        } elseif (isset($_POST['delete'])) {
            $devices->delete($_POST['id']);
        } elseif (isset($_POST['add'])) {
            $ping_attempts = is_int($_POST['ping_attempts']) ? 
                    $_POST['ping_attempts'] :
                    $defaults['default_ping_attempts'];
            
            $ping_timeout = is_int($_POST['ping_timeout']) ? 
                    $_POST['ping_timeout'] :
                    $defaults['default_ping_timeout'];
            
            $info = $devices->addDevice([
                $_POST['name'],
                $_POST['ip'],
                $_POST['template_id'],
                $_POST['connection_id'],
                $ping_attempts,
                $ping_timeout,
                $_POST['group_id']
            ]);
        }
        $sort = $this->sort();

        $groups = $user_groups->getAllNoAdmin();
        $cons = $connections->getAll();
        $temps = $templates->getAll();
        
        if (isset($_POST['search'])) {
            setcookie('search', $_POST['search_text']);
            $search = $_POST['search_text'];
        } else {
            $search = isset($_COOKIE['search']) ? $_COOKIE['search'] : '';
        }

        $devs = $devices->getAll(
                '',
                $sort['order'],
                $sort['desc_get'],
                PER_PAGE,
                $page->getOffset(),
                $search
        );
        $desc = $sort['desc'];
        
        include_once $this->view(__FUNCTION__);
        echo $page->getHtml();
    }
    
    /**
     * An option for an admin to reinstall the server from scratch.
     */
    public function reinstall()
    {
        rename('installed', 'install');
        header('Location: /');
    }
    
    private static function licenceInstall()
    {
        
    }
    
    /**
     * Returns current template's data for import.
     * @param array $template
     * @return void
     */
    private static function templateExport(array $template): void
    {
        $file = 'temp/template.csv';
        unset($template['id']);
        array_walk($template['oids'], function(&$element) {
            unset($element['id']);
            unset($element['recover_time']);
            unset($element['template_id']);
        });
        $result = json_encode($template);
        file_put_contents($file, $result);
        header('Location: ../../' . $file);
    }
    
    /**
     * Imports new device template to the database.
     * @param Template $template
     * @param Oid $oid
     * @return string
     */
    private static function templateImport(Template $template, Oid $oid): string
    {
        $data = file_get_contents($_FILES['tmplt']['tmp_name']);
        $data = json_decode($data, true);
        if ($data !== null) {
            if ($template->countByColumn('name', $data['name']) == 0) {
                $template->add($data['name']);
                $id = $template->getMaxId();
                foreach ($data['oids'] as $one_oid) {
                    $oid->add([
                        $one_oid['oid'],
                        $one_oid['low'],
                        $one_oid['high'],
                        $one_oid['message'],
                        $one_oid['level'],
                        $id
                    ]);
                }
                return TEMPLATE_IMPORT_SUCCESS;
            } else {
                return TEMPLATE_IMPORT_EXISTS;
            }
        } else {
            return TEMPLATE_IMPORT_FAIL;
        }
    }
    
    /**
     * Shows device template management page.
     * @return void
     */
    public function template() 
    {
        $template = new Template;
        $oid = new Oid;
        $message = '';

        if (isset($_POST['add_template'])) {
            $template->add($_POST['name']);
        } elseif (isset($_POST['delete_template'])) {
            $message = $template->delete($_POST['id']);
        } elseif (isset($_POST['change'])) {
            $template->change($_POST['id'], [
                $_POST['name'],
                $_POST['recover_time']
            ]);
        } elseif (isset($_POST['modify'])) {
            $oid->change($_POST['id'], [
                $_POST['oid'],
                $_POST['low'],
                $_POST['high'],
                $_POST['message'],
                $_POST['level'],
                $_POST['template_id']
            ]);
        } elseif (isset($_POST['delete_oid'])) {
            $oid->delete($_POST['id']);
        } elseif (isset($_POST['add_oid'])) {
            $oid->add([
                $_POST['oid'],
                $_POST['low'],
                $_POST['high'],
                $_POST['message'],
                $_POST['level'],
                $_POST['template_id']
            ]);
        } elseif (isset($_POST['upload'])) {
            $message = self::templateImport($template, $oid);
        } elseif (isset($_POST['copy'])) {
            $tpl = $template->getById($_POST['id']);
            $template->add($tpl['name'] . '_copy');
            $templ_id = $template->getMaxId();
            foreach ($tpl['oids'] as $one_oid) {
                $oid->add([
                    $one_oid['oid'],
                    $one_oid['low'],
                    $one_oid['high'],
                    $one_oid['message'],
                    $one_oid['level'],
                    $templ_id,
                ]);
            }
        }
        
        if (isset($_GET['id'])) {
            $templ = $template->getById($_GET['id']);
            if (isset($_POST['export'])) {
                self::templateExport($templ);
            }
        }
        
        $temps = $template->getAll('id, name', 'name');

        include_once $this->view(__FUNCTION__);
    }
    
    /**
     * Shows user management page.
     * @return void
     */
    public function users() 
    {
        $users = new Users;
        $group = new UserGroups;
        $message = '';

        if (isset($_POST['change_group'])) {
            $group->change($_POST['id'], [
                $_POST['name'],
                $_POST['rights']
            ]);
        } elseif (isset($_POST['remove_group'])) {
            $message = $group->delete($_POST['id']);
        } elseif (isset($_POST['add_group'])) {
            $group->add([$_POST['name'], $_POST['rights']]);
        } elseif (isset($_POST['change_pass'])) {
            $users->changeUserPass($_POST['id'], $_POST['pass']);
        } elseif (isset($_POST['change_user'])) {
            $users->change($_POST['id'], [
                $_POST['login'],
                $_POST['group_id']
            ]);
        } elseif (isset($_POST['remove_user'])) {
            $users->delete($_POST['id']);
        } elseif (isset($_POST['add_user'])) {
            $users->add([
                $_POST['login'],
                md5($_POST['pass']),
                $_POST['group_id']
            ]);
        }

        $groups = $group->getAll();
        $user_list = $users->getAll();

        include_once $this->view(__FUNCTION__);
    }
}
