<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'company_id',
        'subscription_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
