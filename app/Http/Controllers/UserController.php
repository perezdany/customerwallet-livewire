<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Utilisateur;

use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\EntrepriseController;

Use DB;

class UserController extends Controller
{
    //Handle Users

    public function GetById($id)
    { 
        //dd($id);  
        $get = DB::table('utilisateurs')
        ->join('departements', 'utilisateurs.id_departement', '=', 'departements.id')
        ->join('roles', 'utilisateurs.id_role', '=', 'roles.id')
        ->where('utilisateurs.id', '=', $id)
        ->get(['utilisateurs.*', 'departements.libele_departement', 'roles.intitule']);

       return $get;
    }
    
    public function GoProfil(Request $request)
    {
        //dd($request->id_user);
        
        return view('admin/profile',
            [
                'id_user' => $request->id_user,
            ]
        );
    }

    public function EditUser(Request $request)
    {
       
        $affected = DB::table('utilisateurs')
        ->where('utilisateurs.id', '=', $request->id_user)
       
        ->update(['login' => $request->email, 'nom_prenoms'=> $request->nom, 
        'id_departement'=> $request->departement, 'poste' => $request->poste,
            'id_role' => $request->role, ]);
        

        return redirect('welcome')->with('success', 'Modification Effectuée avec succès');
    }

    public function EditPassword(Request $request)
    {
        $user_password = Hash::make($request->password);

        $affected = DB::table('utilisateurs')
        ->where('id', $request->id)
        ->update(['password' =>  $user_password, ]);

        //dd($request->id);

        return redirect('utilisateurs')->with('success', 'Modification Effectuée avec succès');
    }

    public function EditPasswordFristLog(Request $request)
    {
        $user_password = Hash::make($request->password);

        //ON VA AUSSI CHANGER LE COUNT LOGIN DE LA PREMIERE
        $affected = DB::table('utilisateurs')
        ->where('id', $request->id)
        ->update(['password' =>  $user_password, 'count_login' => 1]);

        //dd($request->id);

        return redirect('login')->with('success', 'Modification Effectuée avec succès. Vueillez vous connecter à nouveau.');
    }

    public function DisableUser(Request $request)
    {
        $affected = DB::table('utilisateurs')
        ->where('id', $request->id_user)
        ->update(['active' =>  0, ]);

        return redirect('utilisateurs')->with('success', 'Utilisateur désactivé');
    }

    public function EnableUser(Request $request)
    {
        $affected = DB::table('utilisateurs')
        ->where('id', $request->id_user)
        ->update(['active' =>  1, ]);

        return redirect('utilisateurs')->with('success', 'Utilisateur activé');
    }
    
    public function GetAll()
    {
        $get = DB::table('utilisateurs')
        ->join('departements', 'utilisateurs.id_departement', '=', 'departements.id')
        ->get(['utilisateurs.*', 'departements.libele_departement']);

        return $get;

    }

    public function AddUser(Request $request)
    {
        $user_password = Hash::make($request->password);
        
        //dd($request->role);
        $Insert = Utilisateur::create([
            'login' => $request->login, 
            'password' => $user_password,
             'nom_prenoms' => $request->nom_prenoms, 
             'id_departement' => $request->departement, 
             'poste' => $request->poste, 
             'id_role' => $request->role,
             'active' => 1,
              'created_by' => auth()->user()->id,
              'count_user' => 0,
       ]);

       return redirect('utilisateurs')->with('success', 'Enregistrement effectué');
    }
}
