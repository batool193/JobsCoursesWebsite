<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'subtitles',
        'price',
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
    public function attachements()
    {
        return $this->morphMany(Attachement::class, 'attachmentable');
    }
}
