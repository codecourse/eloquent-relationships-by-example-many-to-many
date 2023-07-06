<form action="{{ route('courses.topics.store', $course) }}" method="post">
    @csrf

    <select name="topic_id">
        @foreach ($topics as $topic)
            <option value="{{ $topic->id }}">{{ $topic->title }}</option>
        @endforeach
    </select>
    <input type="text" name="version">
    <button type="submit">Attach</button>
</form>
