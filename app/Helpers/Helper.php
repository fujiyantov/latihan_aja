<?php

use App\Models\Letter;
use App\Models\LetterHistories;
use Illuminate\Support\Facades\Auth;

function notifCount()
{

    $user = Auth::user();
    $query = LetterHistories::where('member_id', $user->id);
    $count = $query->where('is_read', 0)->count();

    return $count;
}

function notifCountMessage()
{

    $user = Auth::user();
    $query = LetterHistories::where('member_id', $user->id);
    $collections = $query->orderBy('created_at', 'desc')->get();

    return $collections;
}
