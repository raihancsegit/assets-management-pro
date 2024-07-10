<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->user = User::factory([
        'name' => 'admin',
    ])->create()->assignRole(Role::create(['name' => 'admin']));
    $this->actingAs($this->user);
});

test('it should see users list page', function () {
    $this->get(route('users.index'))
        ->assertStatus(200);

    expect($this->user->name)->toBe('admin');
});

test('it required confirm password to store new user', function () {
    $user = [
        'name' => 'customer',
        'email' => 'customer@admin.com',
        'password' => 'password',
    ];
    $this->post(route('users.store'), $user)
        ->assertStatus(302)
        ->assertInvalid(['password']);
});

test('it should store new user', function () {
    $user = [
        'name' => 'customer',
        'email' => 'customer@admin.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'manager',
    ];
    $this->post(route('users.store'), $user)
        ->assertStatus(302)
        ->assertRedirect(route('users.index'));

    $this->assertDatabaseHas('users', ['email' => 'customer@admin.com']);
    $this->assertDatabaseHas('roles', ['name' => 'manager']);

    $latestUser = User::latest('id')->first();
    expect($user['name'])->toBe($latestUser->name);
});

test('it should show validation error to store new user', function () {
    $this->post(route('users.store'))
        ->assertStatus(302)
        ->assertInvalid(['name', 'email', 'password', 'role']);
});
