<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }
    public function published() {
        $postsPublished = Post::where('published', '1')->get();
        return view('posts.published', compact('postsPublished'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $now = Carbon::now()->format('Y-m-d-H-i-s');
        $data['slug'] = Str::slug($data['title'] , '-') . $now;

        $validator = Validator::make($data, [
            'title' => 'required|string|max:150',
            'author' => 'required|string|max:100',
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('posts/create')
                ->withErrors($validator)
                ->withInput();
        }

        $post = new Post;
        //validation data with default
        if(empty($data['img'])) {
            unset($data['img']);
        }
        $post->fill($data);
        $saved = $post->save();

        if(!$saved) {
            dd('error saving record');
        }

        return redirect()->route('posts.show', $post->slug);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $post = Post::where('slug', $slug)->first();

        if(empty($post)){
            abort('404');
        }

        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        if(empty($post)) {
            abort('404');
        }

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if(empty($post)) {
            abort('404');
        }

        $data = $request->all();
        $now = Carbon::now()->format('Y-m-d-H-i-s');

        $data['slug'] = Str::slug($data['title'], '-') . $now;

        $validator = Validator::make($data, [
            'title' => 'required|string|max:150',
            'body' => 'required',
            'author' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->route('posts.edit', $post->id)
                ->withErrors($validator)
                ->withInput();
        }

        // if (empty($data['img'])) {
        //     unset($data['img']);
        // }

        $post->fill($data);
        $updated = $post->update();

        return redirect()->route('posts.show', $post->slug);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if (empty($post)) {
            abort('404');
        }

        $post->delete();

        return redirect()->route('posts.index');
    }
}
