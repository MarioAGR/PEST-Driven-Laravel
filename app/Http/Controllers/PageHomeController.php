<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class PageHomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $courses = Course::query()
            ->released()
            ->orderByDesc('released_at')
            ->get();

        $pageTitle = config('app.name') . ' - Home';

        return view('pages.home', compact('courses', 'pageTitle'));
    }
}
