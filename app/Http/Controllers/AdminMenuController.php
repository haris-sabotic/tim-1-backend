<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Str;
use App\Models\Article;

class AdminMenuController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(EnsureUserIsAdmin::class);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.menu');
    }

    public function create(Request $request)
    {
        $name = $request->name;
        $price = $request->price;
        $description = $request->description;
        $ingredients = $request->ingredients;
        $photo = $request->file('photo');

        $photoName = null;

        if ($photo) {
            $photoName = time() . '_' . Str::uuid() . '.' . $photo->extension();
            $photo->move(public_path('photos'), $photoName);
        }

        Article::create([
            'name' => $name,
            'price' => $price,
            'description' => $description,
            'ingredients' => $ingredients,
            'photo' => $photoName,
        ]);

        $request->session()->flash('message', "Successfully created new article.");
        return redirect()->route('menu');
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $price = $request->price;
        $description = $request->description;
        $ingredients = $request->ingredients;
        $photo = $request->file('photo');

        $photoName = null;

        if ($photo) {
            $photoName = time() . '_' . Str::uuid() . '.' . $photo->extension();
            $photo->move(public_path('photos'), $photoName);
        }

        $article = Article::find($id);

        if ($name) {
            $article->name = $name;
        }
        if ($price) {
            $article->price = $price;
        }
        if ($description) {
            $article->description = $description;
        }
        if ($ingredients) {
            $article->ingredients = $ingredients;
        }
        if ($photoName) {
            $article->photo = $photoName;
        }

        $article->save();


        $request->session()->flash('message', "Successfully edited article.");
        return redirect()->route('menu');
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $article = Article::find($request->id);
        $name = $article->name;
        $article->delete();

        $request->session()->flash('message', "Successfully deleted \"" . $name . "\"");
        return redirect()->route('menu');
    }
}
