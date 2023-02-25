<?php

use App\Jobs\HandlePaddlePurchaseJob;
use App\Models\Course;
use App\Models\PurchasedCourse;
use App\Models\User;
use Spatie\WebhookClient\Models\WebhookCall;

it('stores paddle purchase', function () {
    // Assert
    $this->assertDatabaseCount(User::class, 0);
    $this->assertDatabaseCount(PurchasedCourse::class, 0);

    // Arrange
    $course = Course::factory()->create([
        'paddle_product_id' => '123456'
    ]);
    $webhoolCall = WebhookCall::create([
        'name' => 'default',
        'url' => 'some-url',
        'payload' => [
            'email' => 'test@test.com',
            'name' => 'Test User',
            'p_product_id' => '123456',
        ],
    ]);

    // Act
    (new HandlePaddlePurchaseJob($webhoolCall))->handle();

    // Assert
    $this->assertDatabaseHas(User::class, [
        'email' => 'test@test.com',
        'name' => 'Test User',
    ]);
    $user = User::where('email', 'test@test.com')->first();
    $this->assertDatabaseHas(PurchasedCourse::class, [
        'user_id' => $user->id,
        'course_id' => $course->id,
    ]);
});

it('stores paddle purchased for given user', function () {
    // Arrange

    // Act

    // Assert

});

it('sends out purchase email', function () {
    // Arrange

    // Act

    // Assert

});
