<?php

use App\Models\Branch;
use App\Models\Course;
use App\Models\Institute;
use App\Models\Program;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded within the "web" middleware group which includes
| sessions, cookie encryption, and more. Go build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/view-branch-cutoff-ranks', function () {
    return view('search-by-branch');
})->name('search-by-branch');

// NOTE: Deprecated route
Route::get('/search-by-branch', function (Request $request) {
    return redirect()->route('search-by-branch', $request->all(), 301);
});

Route::get('/view-institute-cutoff-ranks', function () {
    return view('search-by-institute');
})->name('search-by-institute');

// NOTE: Deprecated route
Route::get('/search-by-institute', function (Request $request) {
    return redirect()->route('search-by-institute', $request->all(), 301);
});

Route::get('/analyse-institute-cutoff-trends', function () {
    return view('institute-trends');
})->name('institute-trends');

// NOTE: Deprecated route
Route::get('/institute-trends', function (Request $request) {
    return redirect()->route('institute-trends', $request->all(), 301);
});

Route::get('/analyse-round-wise-cutoff-trends', function () {
    return view('round-trends');
})->name('round-trends');

// NOTE: Deprecated route
Route::get('/round-trends', function (Request $request) {
    return redirect()->route('round-trends', $request->all(), 301);
});

Route::get('/analyse-branch-cutoff-trends', function () {
    return view('branch-trends');
})->name('branch-trends');

// NOTE: Deprecated route
Route::get('/branch-trends', function (Request $request) {
    return redirect()->route('branch-trends', $request->all(), 301);
});

Route::prefix('/news')->group(function () {
    Route::get('/', function () {
        return view('news');
    })->name('news');

    Route::get('/using-the-josaa-analysis-tool', function () {
        return view('news.using-the-josaa-analysis-tool');
    })->name('news.using-the-josaa-analysis-tool');

    Route::prefix('/amp')->group(function () {
        Route::get('/using-the-josaa-analysis-tool', function () {
            return view('news.amp.using-the-josaa-analysis-tool');
        })->name('news.amp.using-the-josaa-analysis-tool');
    });
});

Route::prefix('/institutes')->group(function () {
    Route::get('/', function () {
        return view('institute-list');
    })->name('institute-list');

    Route::get('/{institute}', function (Institute $institute) {
        return view('institute-details', ['institute' => $institute]);
    })->name('institute-details');

    Route::get('/{institute}/cutoffs', function (Institute $institute) {
        $rank = $institute->type === 'iit' ? 'jee-advanced' : 'jee-main';

        return view('search-by-institute-proxy', ['rank' => $rank, 'institutes' => [$institute->id], 'hide_controls' => true]);
    })->name('search-by-institute-proxy');

    Route::get('/{institute}/trends', function (Institute $institute) {
        $rank = $institute->type === 'iit' ? 'jee-advanced' : 'jee-main';

        return view('institute-trends-proxy', ['rank' => $rank, 'institutes' => [$institute->id], 'hide_controls' => true]);
    })->name('institute-trends-proxy');

    Route::get('/{institute}/trends/{course}/{program}', function (Institute $institute, Course $course, Program $program) {
        $rank = $institute->type === 'iit' ? 'jee-advanced' : 'jee-main';

        return view('round-trends-proxy', ['rank' => $rank, 'institute' => $institute->id, 'course' => $course->id, 'program' => $program->id, 'hide_controls' => true]);
    })->name('round-trends-proxy');
});

Route::prefix('/branches')->group(function () {
    Route::get('/', function () {
        return view('branch-list');
    })->name('branch-list');

    Route::get('/{branch}', function (Branch $branch) {
        return view('branch-details', ['branch' => $branch]);
    })->name('branch-details');

    Route::get('/{branch}/cutoffs/{rank}', function (Branch $branch, string $rank) {
        return view('search-by-branch-proxy', ['rank' => $rank, 'branches' => [$branch->id], 'hide_controls' => true]);
    })->where(['rank' => 'jee-main|jee-advanced'])->name('search-by-branch-proxy');

    Route::get('/{branch}/trends/{rank}', function (Branch $branch, string $rank) {
        return view('branch-trends-proxy', ['rank' => $rank, 'branches' => [$branch->id], 'hide_controls' => true]);
    })->where(['rank' => 'jee-main|jee-advanced'])->name('branch-trends-proxy');
});

Route::get('/clear-cache', function (Request $request) {
    if ($request->query('appKey') === config('app.key')) {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        return response('Cache cleared', 200);
    }

    return response('Invalid app key', 403);
})->name('clear-cache');
