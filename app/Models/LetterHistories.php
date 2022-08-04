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
        'send_by',
        'is_read',
        // 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function sendBy()
    {
        return $this->belongsTo(User::class, 'send_by');
    }

    public function letter()
    {
        return $this->belongsTo(Letter::class, 'letter_id');
    }
}
