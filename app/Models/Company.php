<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'address',
        'user_id'
    ];
    public function attachements(): MorphOne
    {
        return $this->morphOne(Attachement::class, 'attachmentable');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function subscriptions()
    {
        return $this->belongsToMany(Subscription::class);
    }
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
