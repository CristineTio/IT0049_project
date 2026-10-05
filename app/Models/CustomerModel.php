<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table         = 'customers';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['full_name', 'email', 'phone', 'created_at'];

    // The customers table only has created_at, so updated_at is disabled.
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    // Deleted customers are kept (hidden) because past sales still reference them.
    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';
}
