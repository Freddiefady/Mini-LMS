<?php

declare(strict_types=1);

namespace App\Observers;

use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

final class UserObserver
{
    public function created(User $user): void
    {
        Mail::to($user)->queue(new WelcomeEmail($user));
    }
}
