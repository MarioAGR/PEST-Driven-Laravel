<?php

namespace App\Services\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;

class NullTwitterClient implements TwitterClientInterface
{
    public function tweet(string $status): array
    {
        return [];
    }
}
