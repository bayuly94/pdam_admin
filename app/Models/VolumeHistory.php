<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VolumeHistory extends Model
{
    //

    protected $fillable = [
        'customer_id',
        'before',
        'volume',
        'after',
        'date',
        'employee_id',
    ];


    protected $casts = [
        'before' => 'float',
        'volume' => 'float',
        'after' => 'float',
        'date' => 'datetime',
    ];



    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
