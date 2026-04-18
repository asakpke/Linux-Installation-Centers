<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

it('returns a successful response for the support page', function () {
    $response = $this->get('/support-the-project');

    $response->assertStatus(200);
    $response->assertSee('Support the project', escape: false);
});
