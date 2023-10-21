<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function all()
    {
        return Article::all()->load("tags");
    }

    public function single(Request $request)
    {
        $article = Article::find($request->route('id'));

        if ($article) {
            return $article->load("tags");
        } else {
            return [];
        }
    }
}
