<?php

use App\Models\Course;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $courses = Course::with('topics')->latest()->get();

    // eager loading

    return view('courses.index', [
        'courses' => $courses
    ]);
});

Route::get('/attach', function () {
    $course = Course::find(2); // Learn Inertia
    $topic = Topic::find(1); // Laravel

    $course->topics()->attach($topic);
});

Route::get('/attach-many', function () {
    $course = Course::find(2); // Learn Inertia
    // $topics = Topic::get();

    $course->topics()->attach([1, 2, 3, 4]);
    // $course->topics()->attach($topics);
});

Route::get('/detach-many', function () {
    $course = Course::find(2); // Learn Inertia
    $topics = Topic::get();

    // $course->topics()->detach([3, 4]);
    $course->topics()->detach($topics);
});

Route::get('/courses/{course}/topics', function (Course $course, Request $request) {
    return view('courses.topics', [
        'topics' => Topic::get(),
        'course' => $course
    ]);
});

Route::post('/courses/{course}/topics', function (Course $course, Request $request) {
    // You would do this in your controller
    // $this->validate($request, [
    //     'topic_id' => ['exists:topics,id']
    // ]);

    $course->topics()->syncWithoutDetaching([$request->topic_id => [
        'version' => $request->version
    ]]);
})
    ->name('courses.topics.store');

Route::get('/detach', function () {
    $course = Course::find(2); // Learn Inertia
    $topic = Topic::find(2); // Laravel

    $course->topics()->detach($topic);
});

Route::get('/topics/{topic:slug}', function (Topic $topic, Request $request) {
    // $courses = $topic->courses()->with('topics')->wherePivot('version', '=', $request->version)->get();

    $topic->load([
        'courses' => function ($query) use ($request) {
            $query->with('topics')
                ->wherePivot('version', '=', $request->version);
        }
    ]);

    return view('topics.show', [
        'topic' => $topic,
        // 'courses' => $courses
    ]);
});

Route::get('/sync', function () {
    $course = Course::find(1);

    $course->topics()->syncWithoutDetaching([
        1 => ['version' => '10'],
        2 => ['version' => '9'],
    ]);
});
