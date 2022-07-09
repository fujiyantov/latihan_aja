<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterHistories extends Model
{
    use HasFactory;

    protected $fillable = [
        'letter_id',
        'member_id',
        'description',
    ];
}
