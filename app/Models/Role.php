<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public const BEM = 5;
    public const KAPRODI = 6;
    public const BKA = 8;
    public const PEMBINA = 7;
}
