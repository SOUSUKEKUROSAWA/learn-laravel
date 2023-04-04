<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }
    
    public function create()
    {
        return view('posts/create');
    }

    public function store()
    {
        $data = request()->validate([
            "caption" => ["required"],
            "image" => ["required", "image"],
        ]);

        $imagePath = request("image")->store("uploads", "public");

        // create data with user_id
        auth()->user()->posts()->create([
            "caption" => $data["caption"],
            "image" => $imagePath,
        ]);

        return redirect("/profiles/" . auth()->user()->id);
    }
}
