<?php

namespace App\Models;

use App\Models\Position;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'position_id',
    ];

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }
}
