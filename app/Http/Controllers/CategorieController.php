<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Categorie;

use DB;

class CategorieController extends Controller
{
    //Handle Categories

    public function DisplayAll()
    {
        $get = DB::table('categories')->orderBy('libele_categorie', 'asc')->get();

        return $get;
    }
}
