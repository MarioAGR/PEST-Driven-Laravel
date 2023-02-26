<?php

namespace App\Console\Commands;

use App\Models\Course;
use Illuminate\Console\Command;
use Twitter;

class TweetAboutCourseReleaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tweet:about-course-release {courseId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tweet about a course release';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $course = Course::findOrFail($this->argument(('courseId')));

        Twitter::tweet("I just release {$course->title} 🎉 Check it out on " . route('page.course-details', $course));
    }
}
