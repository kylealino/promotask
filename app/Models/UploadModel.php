<?php
namespace App\Models;
use App\Models\MyDBNamesModel;
use App\Models\MyLibzDBModel;
use App\Models\MyLibzSysModel;

use CodeIgniter\Model;
class UploadModel extends Model
{
    protected $table = 'uploaded_images';
    protected $primaryKey = 'id';
    protected $allowedFields = ['image', 'mime_type'];
}
