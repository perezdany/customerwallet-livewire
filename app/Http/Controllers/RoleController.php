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

    public function EditRoleForm(Request $request)
    {
        return view('admin/roles',
            [
                'id_edit' => $request->id_role,
            ]
         );
    }

    public function GetById($id)
    {
        $get = Role::where('id', $id)->get();
        
        return $get;
    }

    public function EditRole(Request $request)
    {
        $affected = DB::table('roles')
        ->where('id', $request->id_role)
        ->update([
            'intitule' => $request->intitule,
            'specifite' => $request->specifite
        ]);

        return view('admin/roles')->with('success', 'modification effectuée');
    }
    
}
