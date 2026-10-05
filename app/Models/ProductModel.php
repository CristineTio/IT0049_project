<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    /**
     * Folder inside public/ where prepared product images are stored.
     */
    public const IMAGE_DIR = 'uploads/products/';

    protected $table         = 'products';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['name', 'price', 'stock_quantity', 'image', 'created_at'];

    // The products table only has created_at, so updated_at is disabled.
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    // Deleting a product archives it, because past sales still reference it.
    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';
}
