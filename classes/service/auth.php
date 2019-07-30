<?php
/**
 * Description:
 * A simple authentication class without registration. 
 * It uses sessions to save users' data.
 * Use it with an Administrator user registration.
 * --------------------------------------------------------
 * Database:
 * For this class you need two tables users and users_groups.
 * 
 * Table user has fields id, login, pass, group_id
 * login - string
 * pass - string with md5 hash
 * group id - int with group id
 * 
 * Table user_groups id, name, rights.
 * name - string with group names
 * rights - can be 1 - means admin rights, and 0 - user rights.
 * --------------------------------------------------------
 * Requires:
 *      class Database
 *      trait Dbcon instantiates Database
 *      abstract class Datachanger uses Dbcon
 *      class UserGroups extends Datachanger
 *      class Users extends Datachanger 
 * --------------------------------------------------------
 * Usage:
 * 
 * Instantiate this class in the beginning of your code,
 * as it uses sessions.
 * 
 * $a = new Auth;
 * 
 * //Creates html login form
 * echo $a->getLoginForm
 * 
 * //Checks if the user is authenticated
 * if ($a->isAuth())
 * 
 * //Checks if the user is admin
 * if ($a->isAdmin())
 * --------------------------------------------------------
 * @author: Shaian Maksim
 */

final class Auth 
{
    private $admin = 0;
    private $auth = 0;
    private $group;
    private $login = UNKNOWN_USER;
    private const ADMIN_RIGHTS = ADMIN_RIGHTS;
    private const USER_RIGHTS = USER_RIGHTS;
    private const LOGIN_ERROR = LOGIN_ERROR;
    private const PASS_ERROR = PASS_ERROR;
    
    
    /**
     * Auth form builder.
     * 
     * This function builds html-form as a table for authentication.
     * Change it if you want to use another form.
     * 
     * @return string
     */
    private function buildForm(): string
    {
        $form = '<table>' . PHP_EOL;
        $form .= '<tr>' . PHP_EOL;
        $form .= '<td>Login: </td>' . PHP_EOL;
        $form .= '<td><input type="text" name="login" required></td>' . PHP_EOL;
        $form .= '</tr>' . PHP_EOL;
        $form .= '<tr>' . PHP_EOL;
        $form .= '<td>Password: </td>' . PHP_EOL;
        $form .= '<td><input type="password" name="password" required></td>' . PHP_EOL;
        $form .= '</tr>' . PHP_EOL;
        $form .= '<tr>' . PHP_EOL;
        $form .= '<td></td>' . PHP_EOL;
        $form .= '<td><input type="submit" name="login_submit" value="'
                .LOGIN_BUTTON.'"></td>' . PHP_EOL;
        $form .= '</tr>' . PHP_EOL;
        $form .= '</table>' . PHP_EOL;
        $form = self::form($form);
        return $form;
    }
    
    /**
     * Initialization of variables.
     * @return void
     */
    private function isLogged(): void
    {
        if (
                isset($_SESSION['auth']) &&
                isset($_SESSION['admin']) &&
                isset($_SESSION['login']) &&
                isset($_SESSION['group'])
        ) {
            $this->login = $_SESSION['login'];
            $this->auth = $_SESSION['auth'];
            $this->admin = $_SESSION['admin'];
            $this->group = $_SESSION['group'];
        }
    }
    
    /**
     * Service method for applying new data.
     * @return void
     */
    private function save(): void
    {
        $_SESSION['auth'] = $this->auth;
        $_SESSION['admin'] = $this->admin;
        $_SESSION['login'] = $this->login;
        $_SESSION['group'] = $this->group;
    }
    
    /**
     * Login method.
     * 
     * This method initiates login process and checks and returns
     * information message for the authantication form.
     * 
     * @param string $login
     * @param string $pass
     * @return string
     */
    private function logIn(string $login, string $pass): string
    {
        $users = new Users;
        $user = $users->getByName($login);
        if ($user) {
            if ($user['pass'] == md5($pass)) {
                $this->auth = 1;
                $this->login = $user['login'];
                $this->group = $user['group_id'];
                $groups = new UserGroups;
                $rights = $groups->getById($user['group_id']);
                $rights = $rights['rights'];
                if($rights == 1) {
                    $this->admin = 1;
                    $message = self::ADMIN_RIGHTS;
                } else {
                    $this->admin = 0;
                    $message = self::USER_RIGHTS;
                }
            } else {
                $this->auth = 0;
                $message = self::PASS_ERROR;
            }
        } else {
            $this->auth = 0;
            $message = self::LOGIN_ERROR;
        }
        $this->save();
        return $message;
    }
    
    /**
     * Service method for logout
     * @return void
     */
    private function logout(): void
    {
        $_SESSION['auth'] = 0;
        $_SESSION['admin'] = 0;
        $_SESSION['group'] = 0;
        $_SESSION['login'] = UNKNOWN_USER;
        $this->isLogged();
    }
    
    /**
     * Creates an instance of Auth class.
     */
    public function __construct() 
    {
        session_start([
            'save_path' => './userdata/',
            'name' => 'userdata',
            'cookie_lifetime' => 15552000,
        ]);
        $this->isLogged();
    }
    
    /**
     * Method for wrapping anything to a form.
     * @param string $data
     * @param string $action
     * @return string
     */
    public static function form(string $data, string $action = ''): string
    {
        return '<form class= "login" action="' . $action . '" method="post">' . PHP_EOL 
                . $data . '</form>' . PHP_EOL;
    }
    
    /**
     * Method for drawing login form or authentication data.
     * @return string
     */
    public function getLoginForm(): string
    {
        $info = '';
        if (isset($_POST['login_submit'])) {
            $info = '<p class="info">' . 
                    $this->logIn($_POST['login'], $_POST['password']) . 
                    '</p>';
        } elseif (isset($_POST['logout'])) {
            $this->logout();
        }
        
        $form = '<div class="login">' . PHP_EOL;
        if ($this->isAuth()) {
            if ($this->admin) {
                $form .= '<p class="admin">'.ADMINISTRATOR_LOGGED.': ' . $this->login;
            } else {
                $form .= '<p>'.USER_LOGGED.' ' . $this->login;
            }
            $logout = '<input type="submit" name="logout" '
                    . 'value="'.LOGOUT_BUTTON.'">' . PHP_EOL;
            $form .= self::form($logout);
            $form .= '</p>' . PHP_EOL;
        } else {           
            $form .= $this->buildForm();
        }
        $form .= $info;
        return $form . '</div>' . PHP_EOL;
    }
    
    /**
     * Gets current user group id.
     * @return int
     */
    public function getGroupId(): int
    {
        return $this->group;
    }
        
    /**
     * Checks if authentication is successful.
     * @return bool
     */
    public function isAuth(): bool
    {
        if ($this->auth == 1) {
            return $this->login;
        } elseif($this->auth == 0) {
            return false;
        }
    }
    
    /**
     * Checks if current user has admin rights.
     * @return bool
     */
    public function isAdmin(): bool
    {
        if ($this->admin == 1) {
            return true;
        } elseif($this->admin == 0) {
            return false;
        }
    }
}