<?php

use App\Models\Course;
use function Pest\Laravel\get;

it('gives back successful response for home page', function () {
    // Act & Assert
    get(route('page.home'))
        ->assertOk();
});

it('gives back successful response for course details page', function () {
    // Arrange
    $course = Course::factory()->released()->create();

    // Act & Assert
    get(route('page.course-details', $course))
        ->assertOk();
});

it('gives back successful response for dashboard page', function () {
    // Act & Assert
    loginAsUser();
    get(route('page.dashboard'))
        ->assertOk();
});
