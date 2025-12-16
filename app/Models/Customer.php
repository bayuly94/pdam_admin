<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'address',
    ];


    public function volumes()
    {
        return $this->hasMany(VolumeHistory::class, 'customer_id', 'id')->orderBy('created_at', 'desc');
    }


    public function volume_total()
    {
        return $this->volumes()->sum('volume');
    }


    public function volume_total_before($id)
    {
        return $this->volumes()->where('id', '<', $id)->sum('volume');
    }
}
