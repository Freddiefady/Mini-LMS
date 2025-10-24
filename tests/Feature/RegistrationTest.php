<?php

declare(strict_types=1);

use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Volt\Volt;

test('user can register and receives welcome email', function () {
    Mail::fake();

    Volt::test('auth.register')
        ->set('name', 'John Doe')
        ->set('email', 'john@example.com')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->call('register')
        ->assertRedirect(route('dashboard'));

    $user = User::where('email', 'john@example.com')->first();
    expect($user)->not->toBeNull()
        ->and($user->name)->toBe('John Doe');

    Mail::assertQueued(WelcomeEmail::class, function ($mail) use ($user) {
        return $mail->user->id === $user->id;
    });
});

it('registration requires unique email', function () {
    User::factory()->create(['email' => 'existing@example.com']);

    Volt::test('auth.register')
        ->set('name', 'Jane Doe')
        ->set('email', 'existing@example.com')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->call('register')
        ->assertHasErrors(['email']);
});
