<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = ['libele'];


    public function users()
    {
        return $this->belongsToMany(User::class, 'user_permissions', 'id_permission', 'id_user');

    }
}
