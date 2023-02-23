<?php

use App\Models\Course;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
