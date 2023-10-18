<?php

namespace App\Http\Controllers;

use App\Models\Article;

class ArticleController extends Controller
{
    public function all()
    {
        return Article::all()->load("tags");
    }
}
