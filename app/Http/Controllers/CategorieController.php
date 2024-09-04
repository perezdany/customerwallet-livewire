<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Categorie;

class CategorieController extends Controller
{
    //Handle Categories

    public function DisplayAll()
    {
        $get = Categorie::all();

        return $get;
    }
}
