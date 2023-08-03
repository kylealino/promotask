<?php namespace App\Controllers;
  
use CodeIgniter\Controller;
use App\Models\MySalesModel;

class Promo_dashboard extends BaseController
{
    //start construct
    public function __construct()
    {
        $this->mydbname = model('App\Models\MyDBNamesModel');
        $this->db_erp = $this->mydbname->medb(0);
        $this->mytrxpromo = new MySalesModel();
        $this->request = \Config\Services::request();
    }//end construct
    
    //default route
    public function index()
    {
        $data = $this->mytrxpromo->promo_rec_view(1,50);
        echo view('templates/meheader01');
        echo view('sales/trx_dashboard', $data);
        echo view('templates/mefooter01');
    } //end index route



}//end main class