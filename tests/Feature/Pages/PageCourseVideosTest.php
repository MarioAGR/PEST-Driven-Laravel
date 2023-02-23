<?php

use App\Http\Livewire\VideoPlayer;
use App\Models\Course;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Sequence;

use function Pest\Laravel\get;

it('cannot be accessed by guest', function () {
    // Arrange
    $course = Course::factory()->create();

    // Act & Assert
    get(route('page.course-videos', $course))
        ->assertRedirect(route('login'));
});

it('include video player', function () {
    // Arrange
    $course = Course::factory()->create();

    // Act & Assert
    loginAsUser();
    get(route('page.course-videos', $course))
        ->assertOk()
        ->assertSeeLivewire(VideoPlayer::class);
});

it('shows first course video by default', function () {
    // Arrange
    $course = Course::factory()
        ->has(Video::factory()->state([
            'title' => 'My video',
        ]))
        ->create();

    // Act & Assert
    loginAsUser();
    get(route('page.course-videos', $course))
        ->assertOk()
        ->assertSeeText('My video');
});

it('shows provided course vide', function () {
    // Arrange
    $course = Course::factory()
        ->has(Video::factory()->count(2)->state(
            new Sequence(
                ['title' => 'First video'],
                ['title' => 'Second video']
            )
        ))
        ->create();

    // Act & Assert
    loginAsUser();
    get(route('page.course-videos', [
        'course' => $course,
        'video' => $course->videos()->orderByDesc('id')->first()
    ]))
        ->assertOk()
        ->assertSeeText('Second video');
});
