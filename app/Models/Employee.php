<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Employee extends Model
{
    use HasApiTokens;
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'email',
        'password',
    ];

      protected $hidden = [
        'password',
    ];

    public function volumes()
    {
        return $this->hasMany(VolumeHistory::class, 'employee_code', 'code');
    }
}
