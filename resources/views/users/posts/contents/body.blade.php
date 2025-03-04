{{-- Clickable image --}}
<div class="container p-0">
    <a href="{{ route('post.show', $post->id) }}">
        <img src="{{ $post->image }}" alt="Post ID {{ $post->id }}" class="w-100">
    </a>
</div>
<div class="card-body">
    <div class="row align-items-center">
        <div class="col-auto">
            @if ($post->isLiked())
                <form action="{{ route('like.destroy', $post->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm p-0"><i class="fa-solid fa-heart text-danger"></i></button>
                </form>
            @else
                <form action="{{ route('like.store', $post->id) }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-sm p-0"><i class="fa-regular fa-heart"></i></button>
                </form>
            @endif
        </div>
        <div class="col-auto px-0">
            <span>{{ $post->likes->count() }}</span>
        </div>
        <div class="col text-end">
            @forelse ($post->categoryPost as $category_post)
                <div class="badge bg-secondary bg-opacity-50">{{ $category_post->category->name }}</div>
            @empty
                <div class="badge bg-dark">Uncategorized</div>
            @endforelse
        </div>
    </div>

    <a href="{{ route('profile.show', $post->user_id) }}" class="text-decoration-none text-dark fw-bold">{{ $post->user->name }}</a>
    &nbsp;
    <p class="d-inline fw-light">{{ $post->description }}</p>
    <p class="text-uppercase text-secondary xsmall">
        {{ date('M j, Y', strtotime($post->created_at)) }}
        {{-- {{ $post->created_at->diffForHumans() }} --}}
    </p>

    {{--
        date(format, unix time) --- translates a unix time to a specific format
        strtotime(timestamp) --- string to time, translates a timestamp to unix time, timestamp is coming from the database

        Examples:
        (step 1) date('D, M d Y', strtotime('2024-07-08 10:59:56'))
        (step 2) date('D, M d Y', 1720436396)
        (step 3) Mon, Jul 08 2024
    --}}

    @include('users.posts.contents.comments')
</div>