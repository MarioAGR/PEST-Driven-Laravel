<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageCourseDetailsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Course $course)
    {
        throw_unless($course->released_at, new NotFoundHttpException());

        $course->loadCount('videos');

        return view('pages.course-details', compact('course'));
    }
}
