<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Holiday extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'hours' => 'array'
    ];


    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }


}