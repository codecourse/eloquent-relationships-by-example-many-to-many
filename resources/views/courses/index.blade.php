@foreach ($courses as $course)
    <div class="course">
        <h3>{{ $course->title }}</h3>
        <div>
            @foreach ($course->topics as $topic)
                <span>{{ $topic->title }}</span>
            @endforeach
        </div>
    </div>
@endforeach
