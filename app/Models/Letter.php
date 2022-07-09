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
        'member_id',
        'letter_file',
        'status',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}
