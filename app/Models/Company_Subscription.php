<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company_Subscription extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'subscription_id',
    ];


}
