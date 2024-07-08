<?php

namespace App\Models;

use App\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;

    protected $table = 'timages';
    protected $guarded = ['id'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
