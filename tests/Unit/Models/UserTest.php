<?php

use App\Models\User;

test('to array', function () {

    $user = User::factory()->create()->fresh();

    expect(array_keys($user->toArray()))->toBe([
        'id',
        'name',
        'email',
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ]);
});
