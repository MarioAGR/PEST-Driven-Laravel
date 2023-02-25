<?php

use App\Jobs\HandlePaddlePurchaseJob;
use App\Mail\NewPurchaseMail;
use App\Models\Course;
use App\Models\PurchasedCourse;
use App\Models\User;
use Spatie\WebhookClient\Models\WebhookCall;

beforeEach(function () {
    $this->dummyWebhookCall = WebhookCall::create([
        'name' => 'default',
        'url' => 'some-url',
        'payload' => [
            'email' => 'test@test.com',
            'name' => 'Test User',
            'p_product_id' => '123456',
        ],
    ]);
});

it('stores paddle purchase', function () {
    // Assert
    $this->assertDatabaseCount(User::class, 0);
    $this->assertDatabaseCount(PurchasedCourse::class, 0);

    // Arrange
    Mail::fake();
    $course = Course::factory()->create([
        'paddle_product_id' => '123456'
    ]);

    // Act
    (new HandlePaddlePurchaseJob($this->dummyWebhookCall))->handle();

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
    Mail::fake();
    $user = User::factory()->create([
        'email' => 'test@test.com',
        'name' => 'Test User',
    ]);
    $course = Course::factory()->create([
        'paddle_product_id' => '123456'
    ]);

    // Act
    (new HandlePaddlePurchaseJob($this->dummyWebhookCall))->handle();

    // Assert
    $this->assertDatabaseCount(User::class, 1);
    $this->assertDatabaseHas(User::class, [
        'email' => $user->email,
        'name' => $user->name,
    ]);
    $this->assertDatabaseHas(PurchasedCourse::class, [
        'user_id' => $user->id,
        'course_id' => $course->id,
    ]);
});

it('sends out purchase email', function () {
    // Arrange
    Mail::fake();
    Course::factory()->create(['paddle_product_id' => '123456']);

    // Act
    (new HandlePaddlePurchaseJob($this->dummyWebhookCall))->handle();

    // Assert
    Mail::assertSent(NewPurchaseMail::class);
});
