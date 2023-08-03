<?php namespace App\Controllers;
  
use CodeIgniter\Controller;
use App\Models\MySalesModel;

class Deposit extends BaseController
{
    //start construct
    public function __construct()
    {
        $this->mydbname = model('App\Models\MyDBNamesModel');
        $this->db_erp = $this->mydbname->medb(0);
        $this->mytrxpromo = new MySalesModel();
        $this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
    }//end construct
    
    //default route
    public function index()
    {
        echo view('templates/meheader01');
        $data['files'] = $this->db->table('files')->get()->getResult();
        echo view('sales/trx_deposit',$data);
        echo view('templates/mefooter01');
    } //end index route
    
    public function deposit_save() { 
        $this->mytrxpromo->deposit_entry_save();
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
    public function buyxtakey_barcode_dl_proc() { 
        $buyxtakey_trxno = $this->request->getVar('buyxtakey_trxno');
        $this->mytrxpromo->download_buyxtakey_barcode($buyxtakey_trxno);
    }//end generate barcode

    public function do_upload() {
	    helper(['form', 'url']);

	    $file = $this->request->getFile('userfile');

	    if ($file->isValid() && !$file->hasMoved()) {
	        $newName = $file->getRandomName();
	        $file->move(ROOTPATH . './public/uploads/meuploadhehe/', $newName);


	        $data = [
	            'filename' => $file->getName(),
	            'path' => ROOTPATH . './public/uploads/meuploadhehe/' . $newName,

	        ];

			try {
			    $this->db->table('files')->insert($data);
			} catch (\Exception $e) {
			    var_dump($e->getMessage());
			}

 			$files = $this->get_files_data();
	        return $this->response->setJSON(['success' => true,'files' => $files]);
	    } else {
	        return $this->response->setJSON(['success' => false, 'error' => $file->getError()]);
	    }
	}


	public function get_files_data()
	{
	    $files = $this->db->table('files')->get()->getResultArray();
	    return $files;
	}


	public function delete_file()
	{
	    $id = $this->request->getVar('id');
	    $this->db->table('files')->delete(['id' => $id]);
	    return $this->response->setJSON(['success' => true]);
	}

	public function view_file()
	{
	    $id = $this->request->getVar('id');
	    $file = $this->db->table('files')->where('id', $id)->get()->getRowArray();
	    return $this->response->setJSON($file);
	}

}//end main class