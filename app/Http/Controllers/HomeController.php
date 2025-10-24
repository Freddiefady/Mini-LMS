<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Contracts\View\View;

final class HomeController extends Controller
{
    public function __invoke(): View
    {
        $courses = Course::published()
            ->with('level')
            ->get();

        return view('home', ['courses' => $courses]);
    }
}
