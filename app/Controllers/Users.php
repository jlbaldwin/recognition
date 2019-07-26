<?php namespace App\Controllers;

use System\BaseController;
use App\Helpers\Session;
use App\Helpers\Url;
use App\Models\User;

class Users extends BaseController
{
    protected $user;

    public function __construct()
    {
        parent::__construct();

        if (! Session::get('logged_in')) {
            Url::redirect('/admin/login');
        }

        $this->user = new User();
    }

    public function index()
    {
        $users = $this->user->get_users();
        $title = 'Users';

        $this->view->render('admin/users/index', compact('users', 'title'));
    }

    public function add()
    {
        $errors = [];

        if (isset($_POST['submit'])) {
            $firstName          = (isset($_POST['firstName']) ? $_POST['firstName'] : null);
            $lastName           = (isset($_POST['lastName']) ? $_POST['lastName'] : null);
            $email              = (isset($_POST['email']) ? $_POST['email'] : null);
            $password           = (isset($_POST['password']) ? $_POST['password'] : null);
            $password_confirm   = (isset($_POST['password_confirm']) ? $_POST['password_confirm'] : null);
            $isAdmin            = (isset($_POST['isAdmin']) ? $_POST['isAdmin'] : null);    
            $timeCreated        = date("Y-m-d H:i:s");
//<<<<<<< confirmDeleteUser
          
            //using absolute path
            //$imgDirectory       =   "C:\wamp64\www\adam2\app\Resources\images\\";
//=======

            $imgDirectory       =   APPDIR."\Resources\images\\";
//>>>>>>> master
            
            if($isAdmin==0)
            {
                $fileName           = basename($_FILES['signature']['name']);
                $signature          = $imgDirectory . $fileName;     
                //move_uploaded_file($_FILES['signature']['tmp_name'], $signature);
    
                if(!move_uploaded_file($_FILES['signature']['tmp_name'], $signature)){
                    $errors[] = 'Please add a Signature file.';
                }
            }


            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Please enter a valid email address';
            } else {
                if ($email == $this->user->get_user_email($email)){
                    $errors[] = 'Email address is already in use';
                }
            }

            if ($password != $password_confirm) {
                $errors[] = 'Passwords do not match';
            } elseif (strlen($password) < 3) {
                $errors[] = 'Password is too short';
            }

            if (count($errors) == 0) {
                if($isAdmin==0) {
                    $signature          = 'images/' . $fileName;     
                }

                $data = [
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_BCRYPT),
                    'isAdmin' => $isAdmin,
                    'timeCreated' => $timeCreated
                ];
                if($isAdmin==0){
                    $data['signature'] = $signature;
                }

                $this->user->insert($data);

                Session::set('success', 'User created');

                Url::redirect('/users');

            }

        }

        $title = 'Add User';
        $this->view->render('admin/users/add', compact('errors', 'title'));
    }

    public function edit($id)
    {
        if (! is_numeric($id)) {
            Url::redirect('/users');
        }

        $user = $this->user->get_user($id);

        if ($user == null) {
            Url::redirect('/404');
        }

        $errors = [];

        if (isset($_POST['submit'])) {
            $firstName           = (isset($_POST['firstName']) ? $_POST['firstName'] : null);
            $lastName            = (isset($_POST['lastName']) ? $_POST['lastName'] : null);
            $email               = (isset($_POST['email']) ? $_POST['email'] : null);
            $password            = (isset($_POST['password']) ? $_POST['password'] : null);
            $password_confirm    = (isset($_POST['password_confirm']) ? $_POST['password_confirm'] : null);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Please enter a valid email address';
            }

            if ($password != null) {
                if ($password != $password_confirm) {
                    $errors[] = 'Passwords do not match';
                } elseif (strlen($password) < 3) {
                    $errors[] = 'Password is too short';
                }
            }

            if (count($errors) == 0) {

                $data = [
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                    'email' => $email
                ];

                if ($password != null) {
                    $data['password'] = password_hash($password, PASSWORD_BCRYPT);
                }

                $where = ['userId' => $id];

                $this->user->update($data, $where);

                Session::set('success', 'User updated');

                Url::redirect('/users');

            }

        }

        $title = 'Edit User';
        $this->view->render('admin/users/edit', compact('user', 'errors', 'title'));
    }

    public function delete($userId)
    {
        if (! is_numeric($userId)) {
            Url::redirect('/users');
        }

        if (Session::get('user_id') == $userId) {
            Session::set('danger', 'You cannot delete yourself.');
            Url::redirect('/users');
        }

        $user = $this->user->get_user($userId);

        if ($user->numInUse != 0) {
            Session::set('danger', 'That user belongs to an award and so cannot be deleted.');
            Url::redirect('/users');
        }

        if ($user == null) {
            Url::redirect('/404');
        }

        $where = ['userId' => $user->userId];

        $this->user->delete($where);

        Session::set('success', 'User deleted');

        Url::redirect('/users');
    }
}

/* CREDIT TO "Beginning PHP" by David Carr and Markus Gray, Packt Publishing, July 2018. */
