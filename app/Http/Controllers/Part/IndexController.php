<?php

namespace App\Http\Controllers\Part;

use App\Http\Controllers\Controller;

final class IndexController extends Controller
{
    public function __invoke()
    {
        return view('part.index');
    }
}
