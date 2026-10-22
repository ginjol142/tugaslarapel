<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('category')->latest()->paginate(10);
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'title' => 'required|string|max:200',
            'body' => 'required|string',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        $data['slug'] = Str::slug($data['title']);
        $data['published_at'] = $data['status'] === 'published'
            ? ($data['published_at'] ?? now()) : null;

        $base = $data['slug'];
        $i = 1;
        while (Post::withTrashed()->where('slug', $data['slug'])->exists()) {
            $data['slug'] = $base . '-' . $i++;
        }

        Post::create($data);

        return redirect()->route('posts.index');
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $categories = Category::all();
        return view('posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'title' => 'required|string|max:200',
            'body' => 'required|string',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        $data['slug'] = Str::slug($data['title']);

        $base = $data['slug'];
        $i = 1;
        while (Post::withTrashed()
            ->where('slug', $data['slug'])
            ->where('id', '!=', $post->id)->exists()) {
            $data['slug'] = $base . '-' . $i++;
        }

        $data['published_at'] = $data['status'] === 'published'
            ? ($data['published_at'] ?? $post->published_at ?? now())
            : null;

        $post->update($data);

        return redirect()->route('posts.index');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.index');
    }

    public function trash()
    {
        $posts = Post::onlyTrashed()->paginate(10);
        return view('posts.trash', compact('posts'));
    }

    public function restore($id)
    {
        Post::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('posts.trash');
    }

    public function forceDelete($id)
    {
        Post::withTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('posts.trash');
    }
}