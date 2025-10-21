<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use SensitiveParameter;
use Throwable;

final class CreateUser
{
    /**
     * @throws Throwable
     */
    public function handle(
        string $name,
        string $email,
        #[SensitiveParameter] string $password,
    ): User {
        return DB::transaction(function () use ($name, $email, $password) {
            $user = User::query()->create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'is_admin' => false,
            ]);

            Auth::login($user);

            Session::regenerate();

            return $user;
        });
    }
}
