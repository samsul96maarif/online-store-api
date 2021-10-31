<?php

namespace App\Models;

use App\Validators\RoleValidator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Role extends BaseModel
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'role_users',
            'role_id',
            'user_id');
    }

    public function getValidator(): RoleValidator
    {
        return RoleValidator::ins();
    }

    public static function ins()
    {
        return new self();
    }

    public function pop(Request $request)
    {
        return $this->fill([
            'slug' => $request->slug,
            'name' => $request->name
        ]);
    }
}
