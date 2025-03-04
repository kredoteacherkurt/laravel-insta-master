@extends('layouts.app')

@section('title', $user->name)

@section('content')
    @include('users.profile.header')

    {{-- Show all posts here --}}
    <div style="margin-top: 100px">
        @if ($user->posts->isNotEmpty())
            <div class="row">
                @foreach ($user->posts as $post)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <a href="{{ route('post.show', $post->id) }}">
                            <img src="{{ $post->image }}" alt="Post ID {{ $post->id }}" class="grid-img">
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <h3 class="text-secondary text-center">No Posts Yet</h3>
        @endif
    </div>
@endsection