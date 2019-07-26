<?php namespace App\Controllers;
use System\BaseController;
use App\Helpers\Session;
use App\Helpers\Url;
use App\Models\Award;

class Reports extends BaseController
{
    protected $award;
    public function __construct()
    {
        parent::__construct();
        if (! Session::get('logged_in')) {
            Url::redirect('/admin/login');
        }
        $this->award = new Award();
    }
    public function index()
    {
        return $this->view->render('reports/index');//, compact('records'));
    }
    public function get($inputName)
    {
        $decoded = urldecode($inputName);
        error_log($decoded);
        $reports = $this->award->getAwardByAwardees($decoded);
        return $this->view->render('reports/get', compact('reports'));
       
    }
    public function getLocation()
    {
        $reports = $this->award->getAwardByLocation();
        return $this->view->render('reports/get', compact('reports'));
    }
    public function getManager()
    {
        $reports = $this->award->getAwardByManager();
        return $this->view->render('reports/get', compact('reports'));
    }
    public function getPosition()
    {
        $reports = $this->award->getAwardByPosition();
        return $this->view->render('reports/get', compact('reports'));
    }
}