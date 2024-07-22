<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Typeprestation;

class TypePrestationController extends Controller
{
    //Handle Tpyeprestato=ion

    public function GetAll()
    {
        $get =  Typeprestation::All();

        return $get;
    }

    public function AddTypePrestation(Request $request)
    {
        
        $Insert = Typeprestation::create([
            'libele' => $request->libele, 
       
       ]);

       return back()->with('success', 'Enregistrement effectué');
    }
}
