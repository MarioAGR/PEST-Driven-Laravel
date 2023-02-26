<?php

use App\Services\Twitter\NullTwitterClient;
use App\Services\Twitter\TwitterClientInterface;

it('returns null twitter client for testing environment', function () {
    // Act & Assert
    expect(app(TwitterClientInterface::class))
        ->toBeInstanceOf(NullTwitterClient::class);
});
