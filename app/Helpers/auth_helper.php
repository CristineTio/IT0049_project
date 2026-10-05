<?php

use App\Models\UserModel;

if (! function_exists('current_user')) {
    /**
     * Returns the logged-in staff member, or null for guests.
     */
    function current_user(): ?array
    {
        static $users = [];

        $id = session('user_id');

        if ($id === null) {
            return null;
        }

        return $users[$id] ??= model(UserModel::class)->find($id);
    }
}
