<?php namespace App\Controllers;
  
use CodeIgniter\Controller;
use App\Models\MySalesModel;
use App\Models\UploadModel;

class Upload extends BaseController
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
        echo view('sales/trx_upload');
        echo view('templates/mefooter01');
    } //end index route

    public function upload()
    {
        $model = new UploadModel();
        $image = $this->request->getFile('image');
        if ($image->isValid() && !$image->hasMoved()) {
            $imageData = file_get_contents($image->getTempName());
            $mimeType = $image->getMimeType();
            $model->insert(['image' => $imageData, 'mime_type' => $mimeType]);
            $id = $model->getInsertID();
            $data['image_url'] = base_url("display-image/$id");
            return view('sales/trx_upload', $data);
        }
    }

    public function displayImage($id)
    {
        $model = new UploadModel();
        $data = $model->find($id);
        header('Content-Type: ' . $data['mime_type']);
        echo $data['image'];
    }
    

}//end main class