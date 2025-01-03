<?php

use App\Models\User;

it('customer index page can not be accessed without credential', function () {
    $response = $this->get('/customers');
    $response->assertRedirect('/login');
});

it('customer index page can be accessed with credential', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/customers');
    $response->assertOk();
});
