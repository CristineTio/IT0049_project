<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    /**
     * Folder inside public/ where prepared avatar thumbnails are stored.
     */
    public const AVATAR_DIR = 'uploads/avatars/';

    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['username', 'full_name', 'password', 'avatar', 'created_at'];

    // The users table only has created_at, so updated_at is disabled.
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    // Deleted staff are kept (hidden) because past sales still reference them.
    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';

    // Passwords are always hashed before they reach the database.
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    /**
     * Returns the staff member with these credentials, or null if they don't match.
     */
    public function findByCredentials(string $username, string $password): ?array
    {
        $user = $this->where('username', $username)->first();

        if ($user === null || ! password_verify($password, $user['password'])) {
            return null;
        }

        return $user;
    }

    protected function hashPassword(array $data): array
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }

        return $data;
    }
}
