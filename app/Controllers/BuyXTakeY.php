<?php namespace App\Controllers;
  
use CodeIgniter\Controller;
use App\Models\MySalesModel;

class BuyXTakeY extends BaseController
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
        echo view('templates/meheader01');
        echo view('sales/trx_buyxtakey');
        echo view('templates/mefooter01');
    } //end index route
    
    public function buyxtakey_save() { 
        $this->mytrxpromo->buyxtakey_entry_save();
    } 

    public function buyxtakey_vw() { 
        $data = $this->mytrxpromo->buyxtakey_rec_view(1,10);
        return view('sales/trx_buyxtakey_rec',$data);
    } //end record viewing

    //start record pagination
    public function buyxtakey_recs() { 
        $txtsearchedrec = $this->request->getVar('txtsearchedrec');
        $mpages = $this->request->getVar('mpages');
        $mpages = (empty($mpages) ? 0: $mpages);
        $data = $this->mytrxpromo->buyxtakey_rec_view($mpages,10,$txtsearchedrec);
        return view('sales/trx_buyxtakey_rec',$data);
    }//end record pagination


    //show view approval record
     public function buyxtakey_vw_appr() { 
        $data = $this->mytrxpromo->buyxtakey_post_view(1,10);
        return view('sales/trx_buyxtakey_appr',$data);
    } //end view approval record

    //view approval pagination
    public function buyxtakey_recs_appr() { 
        $txtsearchedrec = $this->request->getVar('txtsearchedrec');
        $mpages = $this->request->getVar('mpages');
        $mpages = (empty($mpages) ? 0: $mpages);
        $data = $this->mytrxpromo->buyxtakey_post_view($mpages,10,$txtsearchedrec);
        return view('sales/trx_buyxtakey_appr',$data);
    } //end approval pagination

    //for approval saving
    public function buyxtakey_save_appr() { 
        $this->mytrxpromo->buyxtakey_for_approval();
    }//end for approval

    //generate barcode
    public function buyxtakey_dl_proc() { 
        $buyxtakey_trxno = $this->request->getVar('buyxtakey_trxno');
        $this->mytrxpromo->download_buyxtakey_barcode($buyxtakey_trxno);
    }//end generate barcode


}//end main class