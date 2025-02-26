<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachement extends Model
{
    use HasFactory;
    protected $fillable = ['file_name','file_path','attachmentable_id','attachmentable_type'];
    public function attachmentable()
    {
        return $this->morphTo();
    }
}
