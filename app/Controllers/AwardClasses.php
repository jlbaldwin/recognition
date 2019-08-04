<?php namespace App\Controllers;

use System\BaseController;
use App\Helpers\Session;
use App\Helpers\Url;
use App\Models\AwardClass;

class AwardClasses extends BaseController
{
    protected $awardClass;

    public function __construct()
    {
        parent::__construct();

        if (! Session::get('logged_in')) {
            Url::redirect('/admin/login');
        }

        $this->awardClass = new AwardClass();
    }

    public function index()
    {
        $awardClass = new AwardClass();
        $records = $awardClass->getAwardClasses();

        return $this->view->render('awardClasses/index', compact('records'));
    }

    
    public function add()
    {
        $errors = [];

        if (isset($_POST['submit'])) {
            $awardName           = (isset($_POST['awardName']) ? $_POST['awardName'] : null);

            if (strlen($awardName) < 3) {
                $errors[] = 'Award name is too short';
            }

            if (count($errors) == 0) {

                $data = [
                    'awardName' => $awardName,
                ];

                $this->awardClass->insert($data);

                Session::set('success', 'Award Name added');

                Url::redirect('/awardClasses');

            }

        }

        $title = 'Add Award Name';
        $this->view->render('awardclasses/add', compact('errors', 'title'));
    }

    public function delete($classId)
    {
        if (! is_numeric($classId)) {
            Url::redirect('/awardClasses');
        }

        $awardClass = $this->awardClass->get_awardClass($classId);

        if ($awardClass == null) {
            Url::redirect('/404');
        }

        if ($awardClass->numInUse != 0) {
            Session::set('danger', 'That award type is in use on an award and so cannot be deleted.');
            Url::redirect('/awardClasses');
        }

        //$where = ['classId' => $awardClass->classId];

        $where = ['classId' => $classId];

        $this->awardClass->delete($where);

        Session::set('success', 'Award Type deleted');

        Url::redirect('/awardClasses');
    }
    
}