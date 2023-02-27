<?php

use App\Models\Course;
use Carbon\Carbon;
use function Pest\Laravel\get;

it('show courses overview', function () {
    // Arrange
    $firstCourse = Course::factory()->released()->create();
    $secondCourse = Course::factory()->released()->create();
    $lastCourse = Course::factory()->released()->create();

    // Act & Assert
    get(route('page.home'))
        ->assertSeeText([
            $firstCourse->title,
            $firstCourse->description,
            $secondCourse->title,
            $secondCourse->description,
            $lastCourse->title,
            $lastCourse->description,
        ]);
});

it('shows only released courses', function () {
    // Arrange
    $releasedCourse = Course::factory()->released()->create();
    $notReleasedCourse = Course::factory()->create();

    // Act & Assert
    get(route('page.home'))
        ->assertSeeText($releasedCourse->title)
        ->assertDontSeeText($notReleasedCourse->title);
});

it('shows courses by release date', function () {
    // Arrange
    $releasedCourse = Course::factory()
        ->released(Carbon::yesterday())
        ->create();
    $newestReleasedCourse = Course::factory()
        ->released()
        ->create();

    // Act & Assert
    get(route('page.home'))
        ->assertSeeTextInOrder([
            $newestReleasedCourse->title,
            $releasedCourse->title,
        ]);
});

it('includes login if not logged in', function () {
    // Act & Assert
    get(route('page.home'))
        ->assertOk()
        ->assertSeeText('Login')
        ->assertSee(route('login'));
});

it('includes logout if logged in', function () {
    // Act & Assert
    loginAsUser();
    get(route('page.home'))
        ->assertOk()
        ->assertSeeText('Log out')
        ->assertSee(route('logout'));
});

it('includes courses links', function () {
    // Arrange
    $firstCourse = Course::factory()->released()->create();
    $secondCourse = Course::factory()->released()->create();
    $lastCourse = Course::factory()->released()->create();

    // Act & Assert
    get(route('page.home'))
        ->assertOk()
        ->assertSee([
            route('page.course-details', $firstCourse),
            route('page.course-details', $secondCourse),
            route('page.course-details', $lastCourse),
        ]);
});

it('includes title', function () {
    // Arrange
    $expectedTitle = config('app.name') . ' - Home';

    // Act & Assert
    get(route('page.home'))
        ->assertOk()
        ->assertSee("<title>$expectedTitle</title>", false);
});

it('includes social tags', function () {
    // Act & Assert
    get(route('page.home'))
        ->assertOk()
        ->assertSee([
            '<meta name="description" content="LaravelCasts description" />',
            '<meta property="og:type" content="website" />',
            '<meta property="og:url" content="' . route('page.home') . '" />',
            '<meta property="og:title" content="LaravelCasts" />',
            '<meta property="og:description" content="LaravelCasts description" />',
            '<meta property="og:image" content="' . asset('images/social.png') . '" />',
            '<meta name="twitter:card" content="summary_large_image" />',
        ], false);
});
