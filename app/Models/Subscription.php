<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type',
        'begins',
        'ends',
        'price',
        'jobs_count',
        'courses_count'
    ];
    protected $casts = [
        'begins'=>'date',
        'ends'=>'date',
    ];
    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }
}
