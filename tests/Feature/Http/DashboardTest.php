<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('dashboard page can not be access without credential', function () {
    $response = $this->get('/dashboard');

    $response->assertRedirect('/login');
});

it('dashboard page can be accessed with credential', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/dashboard');
    $response->assertOk();
});
