<?php

it('should redirect to login', function () {
    $response = $this->get('/');

    $response->assertRedirect('/login');
});
