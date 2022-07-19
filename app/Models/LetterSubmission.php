<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'letter_id', 'created_by', 'next_approval_by'
    ];

    public function submission()
    {
        return $this->hasMany(LetterSubmission::class, 'letter_id');
    }
}
