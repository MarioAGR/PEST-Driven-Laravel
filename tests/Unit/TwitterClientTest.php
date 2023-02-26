<?php

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Services\Twitter\TwitterClient;

it('call oauth client for a tweet', function () {
    // Assert
    $mock = mock(TwitterOAuth::class)
        ->shouldReceive('post')
        ->withArgs([
            'statuses/update',
            ['status' => 'My tweet message']
        ])
        ->andReturn(['status' => 'My tweet message'])
        ->getMock();

    // Act
    $twitterClient = new TwitterClient($mock);

    // Act & Assert
    expect($twitterClient->tweet('My tweet message'))
        ->toEqual(['status' => 'My tweet message']);
});
