<?php

namespace App\Models;

use App\Models\User;
use App\Models\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $table = 'mtasks';

    protected $guarded = ['id'];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
