<?php

use App\Models\Course;
use App\Models\Video;
use function Pest\Laravel\get;

it('doest not find unreleased course', function () {
    // Arrange
    $course = Course::factory()->create();

    // Act & Assert
    get(route('page.course-details', $course))
        ->assertNotFound();
});

it('shows course details', function () {
    // Arrange
    $course = Course::factory()->released()->create();

    // Act & Assert
    get(route('page.course-details', $course))
        ->assertOk()
        ->assertSeeText([
            $course->title,
            $course->description,
            $course->tagline,
            ...$course->learnings,
        ])
        ->assertSee(asset("images/{$course->image}"));
});

it('it shows course video count', function () {
    // Arrange
    $course = Course::factory()
        ->has(Video::factory()->count(3))
        ->released()
        ->create();

    // Act & Assert
    get(route('page.course-details', $course))
        ->assertOk()
        ->assertSeeText('3 videos');
});

it('includes paddle checkout button', function () {
    // Arrange
    config()->set('services.paddle.vendor-id', 'vendor-id');
    $course = Course::factory()
        ->released()->create([
            'paddle_product_id' => 'product_id',
        ]);

    // Act & Assert
    get(route('page.course-details', $course))
        ->assertOk()
        ->assertSee('<script src="https://cdn.paddle.com/paddle/paddle.js"></script>', false)
        ->assertSee("Paddle.Environment.set('sandbox');", false)
        ->assertSee("Paddle.Setup({ vendor: 'vendor-id' });", false)
        ->assertSee('<a href="#!" class="paddle_button" data-product="product_id">Buy Now!</a>', false);
});
