<?php

namespace App\Observers;

use App\Order;
use App\User;

class UserObserver
{
    public function deleting(User $user)
    {
        $user->orders()->delete();
        //Todo remove chat messages
    }
}
