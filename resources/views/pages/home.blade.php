@guest
    <a href="{{ route('login') }}">Login</a>
@else
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Log out</button>
    </form>
@endguest

@foreach ($courses as $course)
    <a href="{{ route('page.course-details', $course) }}">
        <h2>{{ $course->title }}</h2>
    </a>
    <p>{{ $course->description }}</p>
@endforeach
