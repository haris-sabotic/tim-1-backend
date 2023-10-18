<?php

namespace App\Http\Controllers;

use App\Models\Tag;

class TagController extends Controller
{
    public function all()
    {
        return Tag::all();
    }
}
