<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pays;

class PaysController extends Controller
{
    //Handle Countries

    public function DisplayAll()
    {
        $get =  Pays::all();

        return $get;
    }
}
