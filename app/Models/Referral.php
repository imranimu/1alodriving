<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'referral_code',
        'created_at',
        'updated_at'
    ];
}
