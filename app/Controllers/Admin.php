<?php namespace App\Controllers;

use System\BaseController;
use App\Helpers\Session;
use App\Helpers\Url;
use App\Models\User;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Admin extends BaseController
{
    protected $user;

    public function __construct()
    {
        parent::__construct();

        $this->user = new User();
    }

    public function index()
    {
        if (! Session::get('logged_in')) {
            Url::redirect('/admin/login');
        }

        $title = 'Dashboard';

        $this->view->render('admin/index', compact('title'));
    }

    public function login()
    {
        //echo password_hash('demo', PASSWORD_BCRYPT);

        if (Session::get('logged_in')) {
            Url::redirect('/admin');
        }

        $errors = [];

        if (isset($_POST['submit'])) {
            $username = htmlspecialchars($_POST['username']);
            $password = htmlspecialchars($_POST['password']);

            if (password_verify($password, $this->user->get_hash($username)) == false) {
                $errors[] = 'Wrong email address or password';
            }

            if (count($errors) == 0) {

                //logged in
                $data = $this->user->get_data($username);

                Session::set('logged_in', true);
                Session::set('user_id', $data->userId);
                Session::set('is_admin', $data->isAdmin);
                Session::set('first_name', $data->firstName);
                Session::set('last_name', $data->lastName);

                if(Session::get('is_admin') == 1){    

                    Url::redirect('/users');
                }
                else{
                    Url::redirect('/awards/');   
                }
            }
        }

        $title = 'Login';

        $this->view->render('admin/auth/login', compact('title', 'errors'));
    }

    public function reset()
    {
        if (Session::get('logged_in')) {
            Url::redirect('/admin');
        }

        $errors = [];

        if (isset($_POST['submit'])) {

           $email = (isset($_POST['email']) ? $_POST['email'] : null);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Please enter a valid email address';
            } else {
                if ($email != $this->user->get_user_email($email)){
                    $errors[] = 'Email address not found';
                }
            }

            if (count($errors) == 0) {

                $token = md5(uniqid(rand(),true));
                $data  = ['resetToken' => $token];
                $where = ['email' => $email];
                $this->user->update($data, $where);

                $mail = new PHPMailer(true);


                //updates start here
                $mail->SMTPDebug = 2;
                $mail->isSMTP();
                $mail->Host         = 'smtp.mail.com';
                $mail->SMTPAuth     = true;
                $mail->Username     = 'xx@xx.com';
                $mail->Password     = 'xxpw';
                $mail->SMTPSecure   = 'tls';
                $mail->Port         = 587;
                    

                $mail->setFrom('fangrecognition@mail.com'); 

                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Reset your account';
                $mail->Body    = "<p>To change your password please click <a href='http://" . $_SERVER['HTTP_HOST'] . "/admin/change_password/$token'>this link</a></p>";
                $mail->AltBody = "To change your password please go to this address: http://" . $_SERVER['HTTP_HOST'] . "/admin/change_password/$token";
                //updates end here
                
                $mail->send();

                Session::set('success', "Email sent to ".htmlentities($email));

                Url::redirect('/admin/reset');

            }

        }

        $title = 'Reset Account';

        $this->view->render('admin/auth/reset', compact('title', 'errors'));
    }

    public function change_password($token)
    {
        if (Session::get('logged_in')) {
            Url::redirect('/admin');
        }

        $errors = [];

        $user = $this->user->get_user_reset_token($token);


        if ($user == null) {
            $errors[] = 'user not found.';
        }

        if (isset($_POST['submit'])) {

            $token            = htmlspecialchars($_POST['token']);
            $password         = htmlspecialchars($_POST['password']);
            $password_confirm = htmlspecialchars($_POST['password_confirm']);


            $user = $this->user->get_user_reset_token($token);

            if ($user == null) {
                $errors[] = 'user not found.';
            }

            if ($password != $password_confirm) {
                $errors[] = 'Passwords do not match';
            } elseif (strlen($password) < 3) {
                $errors[] = 'Password is too short';
            }

            if (count($errors) == 0) {

                $data  = [
                    'resetToken' => null,
                    'password' => password_hash($password, PASSWORD_BCRYPT)
                ];

                $where = [
                    'resetToken' => $token
                ];
  
                $this->user->update($data, $where);

                $data1 = $this->user->get_user($user->userId);        

                Session::set('logged_in', true);
                Session::set('user_id', $user->userId);
                
                Session::set('is_admin', $data1->isAdmin);
                Session::set('first_name', $data1->firstName);
                Session::set('last_name', $data1->lastName);

                Session::set('success', "Password updated");
                
                if(Session::get('is_admin') == 1){    

                    Url::redirect('/users');
                }
                else{
                    Url::redirect('/awards/');   
                }
            }

        }

        $title = 'Change Password';

        $this->view->render('admin/auth/change_password', compact('title', 'token', 'errors'));
    }

    public function logout()
    {
        Session::destroy();
        Url::redirect('/admin/login');
    }

    public function delete($userId)
    {
        if (! is_numeric($userId)) {
            Url::redirect('/users');
        }

        if (Session::get('user_id') == $userId) {
            die('You cannot delete yourself.');
        }

        $user = $this->user->get_user($userId);

        if ($user == null) {
            Url::redirect('/404');
        }

        $where = ['id' => $user->userId];

        $this->user->delete($where);

        Session::set('success', 'User deleted');

        Url::redirect('/users');
    }

}

/* Adapted from "Beginning PHP" by David Carr and Markus Gray, Packt Publishing, July 2018. */
