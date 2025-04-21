<?php

use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

function logActivity($activity, $description = null)
{
    Activity::create([
        'user_id' => Auth::id(),
        'activity' => $activity,
        'description' => $description,
    ]);
}
