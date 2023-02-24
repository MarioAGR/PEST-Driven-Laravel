<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class AddGivenCoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if ($this->isDataAlreadyGiven()) {
            return;
        }

        Course::factory()->count(3)->state(
            new Sequence(
                ['title' => 'Laravel For Beginners'],
                ['title' => 'Advanced Laravel'],
                ['title' => 'TDD The Laravel Way'],
            )
        )->create();
    }

    private function isDataAlreadyGiven(): bool
    {
        return Course::where('title', 'Laravel For Beginners')->exists()
            && Course::where('title', 'Advanced Laravel')->exists()
            && Course::where('title', 'TDD The Laravel Way')->exists();
    }
}
