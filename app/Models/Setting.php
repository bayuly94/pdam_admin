<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{


    const CODE_ABOUT = 'about';
    

    protected $fillable = [
        'code',
        'value',
    ];
}
