<?php

namespace App\Models;

use App\Models\User;
use App\Models\Image;
use App\Models\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mtasks';

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
    public function status()
    {
        return $this->belongsTo(Status::class);
    }
    public function image()
    {
        return $this->hasMany(Image::class);
    }
}
