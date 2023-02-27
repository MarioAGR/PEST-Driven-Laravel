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
    $this->app['env'] = 'local'; // Dirty way to set up the environment as local
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

it('includes title', function () {
    // Arrange
    $course = Course::factory()->released()->create();
    $expectedTitle = config('app.name') . " - {$course->title}";

    // Act & Assert
    get(route('page.course-details', $course))
        ->assertOk()
        ->assertSee("<title>{$expectedTitle}</title>", false);
});

it('includes social tags', function () {
    // Arrange
    $course = Course::factory()->released()->create();

    // Act & Assert
    get(route('page.course-details', $course))
        ->assertOk()
        ->assertSee([
            '<meta name="description" content="' . $course->description . '" />',
            '<meta property="og:type" content="website" />',
            '<meta property="og:url" content="' . route('page.course-details', $course) . '" />',
            '<meta property="og:title" content="' . $course->title . '" />',
            '<meta property="og:description" content="' . $course->description . '" />',
            '<meta property="og:image" content="' . asset("images/{$course->image_name}") . '" />',
            '<meta name="twitter:card" content="summary_large_image" />',
        ], false);
});
