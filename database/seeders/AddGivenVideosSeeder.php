<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Video;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class AddGivenVideosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if ($this->isDataAlreadyGiven()) {
            return;
        }

        $laravelForBeginnersCourse = Course::where('title', 'Laravel For Beginners')->firstOrFail();
        $advancedLaravelCourse = Course::where('title', 'Advanced Laravel')->firstOrFail();
        $tddTheLaravelWayCourse = Course::where('title', 'TDD The Laravel Way')->firstOrFail();

        Video::factory()->count(7)->state(
            new Sequence(
                ['course_id' => $laravelForBeginnersCourse->id],
                ['course_id' => $laravelForBeginnersCourse->id],
                ['course_id' => $laravelForBeginnersCourse->id],

                ['course_id' => $advancedLaravelCourse->id],
                ['course_id' => $advancedLaravelCourse->id],
                ['course_id' => $advancedLaravelCourse->id],

                ['course_id' => $tddTheLaravelWayCourse->id],
            )
        )->create();
    }

    private function isDataAlreadyGiven(): bool
    {
        $laravelForBeginnersCourse = Course::where('title', 'Laravel For Beginners')->firstOrFail();
        $advancedLaravelCourse = Course::where('title', 'Advanced Laravel')->firstOrFail();
        $tddTheLaravelWayCourse = Course::where('title', 'TDD The Laravel Way')->firstOrFail();

        return $laravelForBeginnersCourse->videos()->count() === 3
            && $advancedLaravelCourse->videos()->count() === 3
            && $tddTheLaravelWayCourse->videos()->count() === 1;
    }
}
