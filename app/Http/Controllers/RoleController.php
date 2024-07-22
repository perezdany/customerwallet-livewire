<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Role;

use DB;

class RoleController extends Controller
{
    //Handle Role

    public function GetAll()
    {
        return Role::All();

    }

    public function AddRole(Request $request)
    {
        
        $Insert = Typeprestation::create([
            'intitule' => $request->intitule,
            'specifite' => $request->specifite 
       
       ]);

       return back()->with('success', 'Enregistrement effectué');
    }
    
}
