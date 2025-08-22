<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function getMasters()
    {
        $masters = User::whereHas('role', function ($query) {
            $query->where('name', 'master')
            ->orWhere('name', 'admin');
        })->orderByDesc('created_at')->get();
        return $masters;        
    }
}
