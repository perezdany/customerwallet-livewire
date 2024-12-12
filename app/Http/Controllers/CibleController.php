<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Cible;

use DB;

class CibleController extends Controller
{
    //Les entreprise qu'on prévoit prospecter

    public function GetAll()
    {
       $get = DB::table('cibles')
       ->join('pays', 'cibles.id_pays', '=', 'pays.id')
       ->orderBy('nom', 'asc')
       ->get(['cibles.*', 'pays.nom_pays']);

       return $get;
    }

    public function GetById($id)
    {
        $get = DB::table('cibles')
        ->where('cibles.id', $id)
        ->join('pays', 'cibles.id_pays', '=', 'pays.id')
        ->orderBy('nom', 'asc')
        ->get(['cibles.*', 'pays.nom_pays']);

        return $get;
    }

    public function AddCible(Request $request)
    {
        $Insert = Cible::create([
           
            'nom'=> $request->nom,
            'adresse' => $request->adresse, 
            'contact' => $request->tel,
            'id_pays' => $request->pays,
            
            'created_by' => auth()->user()->id, 
        ]);

        //dd($request->chiffre);

      
        return redirect('cibles')->with('success', 'Enregistrement effectué');
    }

    public function EditCibleForm(Request $request)
    {
        //dd($request->id_entreprise);
        return view('dash/cibles',
            [
                'id_entreprise' => $request->id_entreprise,
            ]
        );
    }

    public function EditCible(Request $request)
    {
        $affected= DB::table('cibles')
        ->where('id', $request->id_entreprise)
        ->update([
           
           'nom'=> $request->nom,
            'adresse' => $request->adresse, 
            'contact' => $request->tel,
            'id_pays' => $request->pays,
             
        ]);
        return redirect('cibles')->with('success', 'Modification effectuée');
    }

    public function DeleteCible(Request $request)
    {
        $deleted = DB::table('cibles')->where('id', '=', $request->id_entreprise)->delete();

        return redirect('cibles')->with('success', 'Elément supprimé');
    }
}
