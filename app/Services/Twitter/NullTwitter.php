<?php

namespace App\Services\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;

class NullTwitter implements TwitterClientInterface
{
    public function tweet(string $status): array
    {
        return [];
    }
}
