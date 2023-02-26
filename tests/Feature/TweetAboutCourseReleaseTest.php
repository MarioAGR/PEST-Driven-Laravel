<?php

use App\Console\Commands\TweetAboutCourseReleaseCommand;
use App\Models\Course;

it('tweets about release for provided course', function () {
    // Arrange
    Twitter::fake();
    $course = Course::factory()->create();

    // Act
    $this->artisan(TweetAboutCourseReleaseCommand::class, [
        'courseId' => $course->id,
    ]);

    // Assert
    Twitter::assertTweetSent("[TESTING] I just release {$course->title} 🎉 Check it out on " . route('page.course-details', $course));
});
