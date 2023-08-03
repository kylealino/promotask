<?php namespace App\Controllers;

use CodeIgniter\Controller;

class Meupload extends Controller {

		public function __construct()
	{ 
		$this->db = \Config\Database::connect();

	}

	 public function index() {
	 	echo view('templates/meheader01');
	    $data['files'] = $this->db->table('files')->get()->getResult();
	    echo view('Myupload/upload_form', $data);
	    echo view('templates/mefooter01');
	}

	public function do_upload() {
	    helper(['form', 'url']);

	    $file = $this->request->getFile('userfile');

	    if ($file->isValid() && !$file->hasMoved()) {
	        $newName = $file->getRandomName();
	        $file->move(ROOTPATH . './public/uploads/meuploadhehe/', $newName);

	        $data = [
	            'filename' => $file->getName(),
	            'path' => ROOTPATH . './public/uploads/meuploadhehe/' . $newName,
	            'uploaded_by' =>  $this->request->getPost('upload_by'),
	            'description' => $this->request->getPost('description'), // add the description to the data array
	            'created_at' => date('Y-m-d H:i:s'),
	            'age' => $this->request->getPost('age')

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


}


