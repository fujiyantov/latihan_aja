<?php

namespace App\Models;

use App\Models\Member;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Letter extends Model
{
    use HasFactory;

    protected $fillable = [
        'letter_no',
        'title',
        'date',
        'date_approval',
        'approval_by',
        'next_approval_by',
        'member_id',
        'letter_file',
        'status',
        'dana'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function submission()
    {
        return $this->hasMany(LetterSubmission::class, 'letter_id');
    }

    public function submissionLatest()
    {
        return $this->hasMany(LetterSubmission::class, 'letter_id')->latest();
    }

    public function histories()
    {
        return $this->hasMany(LetterHistories::class, 'letter_id');
    }
}
