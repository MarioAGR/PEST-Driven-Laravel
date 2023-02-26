<?php

use App\Services\Twitter\NullTwitterClient;

it('returns empty array for a tweet call', function () {
    // Act & Assert
    expect(new NullTwitterClient())
        ->tweet('Out tweet')
        ->toBeArray()->toBeEmpty();
});
