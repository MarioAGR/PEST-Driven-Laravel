<x-guest-layout :page-title="config('app.name') . ' - ' . $course->title">

    @push('social-meta')
        <meta name="description" content="{{ $course->description }}" />

        <!-- Open Graph / Meta / Facebook -->
        <meta property="og:type" content="website" />
        <meta property="og:url" content="{{ route('page.course-details', $course) }}" />
        <meta property="og:title" content="{{ $course->title }}" />
        <meta property="og:description" content="{{ $course->description }}" />
        <meta property="og:image" content="{{ asset("images/{$course->image_name}") }}" />

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image" />
    @endpush

    <h2>Title: {{ $course->title }}</h2>
    <h3>Tagline: {{ $course->tagline }}</h3>
    <p>Description: {{ $course->description }}</p>
    <p>{{ $course->videos_count }} videos</p>
    <ul>
        @foreach ($course->learnings as $learning)
            <li>{{ $learning }}</li>
        @endforeach
    </ul>
    <img src="{{ asset("images/$course->image_name") }}" alt="Image of the course {{ $course->title }}">

    <a href="#!" class="paddle_button" data-product="{{ $course->paddle_product_id }}">Buy Now!</a>
    <script src="https://cdn.paddle.com/paddle/paddle.js"></script>
    <script type="text/javascript">
        @env('local')
            Paddle.Environment.set('sandbox');
        @endenv
        Paddle.Setup({ vendor: '{{ config('services.paddle.vendor-id') }}' });
    </script>

</x-guest-layout>
