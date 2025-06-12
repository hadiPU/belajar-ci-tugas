<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductCategoryModel extends Model
{
    protected $table = 'product_category'; // Sesuaikan dengan nama tabel Anda
    protected $primaryKey = 'id';

    protected $allowedFields = ['category_name', 'description', 'created_at', 'updated_at'];

    protected $useTimestamps = true; // akan otomatis isi created_at dan updated_at
}
