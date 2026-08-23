<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $posts = Post::query()
            ->published()
            ->with(['category', 'tags'])
            ->when($request->filled('category'), fn ($q) => $q->whereHas(
                'category',
                fn ($c) => $c->where('slug', $request->string('category'))
            ))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%' . $request->string('q') . '%';
                $q->where(fn ($w) => $w->where('title', 'like', $term)
                    ->orWhere('excerpt', 'like', $term)
                    ->orWhere('body', 'like', $term));
            })
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('blog.index', [
            'posts' => $posts,
            'categories' => Category::query()
                ->withCount(['posts' => fn ($q) => $q->published()])
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function show(Post $post): View
    {
        abort_unless($post->status === 'published' && $post->published_at?->lte(now()), 404);

        $post->load(['category', 'tags', 'author']);

        $related = Post::query()
            ->published()
            ->where('id', '!=', $post->id)
            ->when($post->category_id, fn ($q) => $q->where('category_id', $post->category_id))
            ->latest('published_at')
            ->take(3)
            ->get();

        if ($related->isEmpty()) {
            $related = Post::query()->published()->where('id', '!=', $post->id)->latest('published_at')->take(3)->get();
        }

        return view('blog.show', [
            'post' => $post,
            'related' => $related,
        ]);
    }
}
